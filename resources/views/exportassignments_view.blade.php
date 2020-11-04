{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη | Αναθέσεις Εξαγωγής')

@section('content_header')
    <h1>Αποθήκη/Warehouse | Όλες οι Αναθέσεις Εξαγωγής</h1>
@stop


@section('content')

<style>
    .dt-buttons{
        margin-bottom: 10px;
        padding-bottom: 5px;
    }
</style>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        <div class="col-lg-12 col-xs-6">

            <p>Όλες οι Αναθέσεις Εξαγωγής</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant', 'isNormalUser'])

			<!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Αποθήκη</th>
                        <th class="text-left">Κείμενο Ανάθεσης</th>
                        <th class="text-left">Deadline (Ημ/νία & Ώρα)</th>
                        <th class="text-left">Επισυναπτόμενα Αρχεία</th>
                        <th class="text-left">Σχόλια</th>
						<th class="text-left">Ανοιχτή?</th>
                        <th class="text-left">Άνοιγμα</th>
						<th class="text-left">Κλείσιμο</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($exportassignments as $exportassignment)
                    <tr class="user-row" data-eid="{{ $exportassignment->id }}">  <!-- necessary additions -->
                        <td>{{ $exportassignment->warehouse->name }}</td>
                        <td>{{ $exportassignment->export_assignment_text }}</td>
                        <td>{{ $exportassignment->export_deadline->format('l, d/m/Y @ H:i') }}</td>
                        @php
                            $attached_files = json_decode($exportassignment->uploaded_files, true);
                        @endphp
                        <td>
                            <ul>
                            @foreach($attached_files as $att_file)
                                <li>{{ basename($att_file) }}</li>
                            @endforeach
                            </ul>
                        </td>
                        <td>{{ $exportassignment->comments }}</td>
						<td>
                            @if($exportassignment->is_open == 1)
                                <i class="fas fa-lock-open" aria-hidden="true"></i>&nbsp; Ανοιχτή
                            @elseif($exportassignment->is_open == 0)
                                <i class="fas fa-lock" aria-hidden="true"></i>&nbsp; Κλειστή
                            @endif
                        </td>


                        <!-- Κουμπί Ανοίγματος Ανάθεσης Εξαγωγής -->
                        <td>
                            @if($exportassignment->is_open == 0)
                            <button class="open-modal btn btn-success"
                               data-toggle="modal" data-target="#open-modal"
                               data-eid="{{ $exportassignment->id }}"
                               data-warehousename="{{ $exportassignment->warehouse->name }}"
                               data-deadline="{{ $exportassignment->export_deadline->isoFormat('llll') }}"
                               data-text="{{ $exportassignment->export_assignment_text }}">
                            <i class="fas fa-lock-open" aria-hidden="true"></i>&nbsp; Άνοιγμα
                            </button>
                            @endif
                        </td>


                        <!-- Κουμπί Κλεισίματος Ανάθεσης Εξαγωγής -->
                        <td>
                            @if($exportassignment->is_open == 1)
                            <button class="close-modal btn btn-warning"
                                data-toggle="modal" data-target="#close-modal"
                                data-eid="{{ $exportassignment->id }}"
                                data-warehousename="{{ $exportassignment->warehouse->name }}"
                                data-deadline="{{ $exportassignment->export_deadline->isoFormat('llll') }}"
                                data-text="{{ $exportassignment->export_assignment_text }}">
                            <i class="fas fa-lock" aria-hidden="true"></i>&nbsp; Κλείσιμο
                            </button>
                            @endif
                        </td>

                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-eid="{{ $exportassignment->id }}"
									data-warehouse="{{ $exportassignment->warehouse_id }}"
                                    data-text="{{ $exportassignment->export_assignment_text }}"
                                    data-deadline="{{ $exportassignment->export_deadline->format('d-m-Y H:i') }}"
                                    data-files="{{ $exportassignment->uploaded_files }}"
									data-comments="{{ $exportassignment->comments }}"
									data-isopen="{{ $exportassignment->is_open }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-eid="{{ $exportassignment->id }}"
                                    data-warehousename="{{ $exportassignment->warehouse->name }}"
                                    data-deadline="{{ $exportassignment->export_deadline->isoFormat('llll') }}"
                                    data-text1="{{ $exportassignment->export_assignment_text }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New User button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal" id="add-expassgn-btn">Προσθήκη Νέας Ανάθεσης Εξαγωγής</button>

            <br/><br/>
            @endcanany <!-- isSuperAdmin, isCompanyCEO, isWarehouseForeman, isWarehouseWorker -->


			@can('isSuperAdmin')
                <a href="{{ route('admin.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isCompanyCEO')
                <a href="{{ route('manager.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isAccountant')
                <a href="{{ route('accountant.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isWarehouseForeman')
                <a href="{{ route('foreman.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

			@can('isNormalUser')
                <a href="{{ route('normaluser.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan



			@canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant', 'isNormalUser'])
            <!-- The 3 Modals, Add/Update/Delete -->

            <!-- the Add/Create new Export Assignment, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Νέας Ανάθεσης Εξαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="add-form" class="form-horizontal" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('POST')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <!-- this is where the error messages will be displayed -->
                            <div class="alert alert-danger" style="display:none">
                            </div>

                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Προσθήκη Προϊόντος</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-eid-create" name="modal-input-eid-create" value="">

                                    <!-- warehouse name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-create">Αποθήκη</label>
                                        <select name="modal-input-warehouse-create" id="modal-input-warehouse-create" class="form-control">
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /warehouse name -->

									<!-- assignment text -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-text-create">Κείμενο Ανάθεσης Εξαγωγής</label>
                                        <textarea rows="3" name="modal-input-text-create" class="form-control" id="modal-input-text-create"
                                            value=""></textarea>
                                    </div>
                                    <!-- /assignment text -->

									<!-- deadline datetime -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="date-time-picker-create">Deadline (Ημερομηνία &amp; Ώρα)</label>
                                        <input type="text" name="modal-input-picker-create" class="form-control" id="date-time-picker-create"
                                            value="" autocomplete="off" />
                                    </div>
                                    <!-- /deadline datetime -->

									<!-- uploaded files -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-files-create">Επισυναπτόμενα Αρχεία</label>
                                        <i class="fas fa-paperclip text-danger" aria-hidden="true"></i>
                                        <input type="file" multiple name="modal-input-files-create[]" class="form-control" id="modal-input-files-create"
                                            value="" />
                                    </div>
                                    <!-- /uploaded files -->

									<!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-create">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-create" class="form-control" id="modal-input-comments-create"
                                            value=""></textarea>
                                    </div>
                                    <!-- /comments -->


                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-exportassignment-button"
                                data-target="#add-modal">Πρόσθεσε Ανάθεση Εξαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>



            <!-- the Edit/Update existing ExportAssignment, Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Ανάθεσης Εξαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="edit-form" class="form-horizontal" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('PUT')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <!-- this is where the error messages will be displayed -->
                            <div class="alert alert-danger" style="display:none">
                            </div>


                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Μεταβολή Ανάθεσης Εξαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-eid-edit" name="modal-input-eid-edit" value="">

									<!-- warehouse name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-edit">Αποθήκη</label>
                                        <select name="modal-input-warehouse-edit" id="modal-input-warehouse-edit" class="form-control">
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /warehouse name -->

									 <!-- assignment text -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-text-edit">Κείμενο Ανάθεσης Εξαγωγής</label>
                                        <textarea rows="3" name="modal-input-text-edit" class="form-control" id="modal-input-text-edit"
                                            value=""></textarea>
                                    </div>
                                    <!-- /assignment text -->

									<!-- deadline datetime -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="date-time-picker-edit">Deadline (Ημερομηνία &amp; Ώρα)</label>
                                        <input type="text" name="modal-input-picker-edit" class="form-control" id="date-time-picker-edit"
                                            value="" autocomplete="off" />
                                    </div>
                                    <!-- /deadline datetime -->

									<!-- uploaded files -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-files-edit">Επισυναπτόμενα Αρχεία</label>
                                        <i class="fas fa-paperclip text-danger" aria-hidden="true"></i>

                                        <span id="arxeia"></span>

                                        <input type="file" multiple name="modal-input-files-edit[]" class="form-control" id="modal-input-files-edit"
                                            value="" />
                                    </div>
                                    <!-- /uploaded files -->

									<!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-edit">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-edit" class="form-control" id="modal-input-comments-edit"
                                            value=""></textarea>
                                    </div>
                                    <!-- /comments -->

									<!-- is Export Assignment Open -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-isopen-edit">Ανοιχτή?</label>
                                        <select name="modal-input-isopen-edit" id="modal-input-isopen-edit" class="form-control">
                                            <option value="1">Ανοιχτή</option>
                                            <option value="0">Κλειστή</option>
                                        </select>
                                    </div>
                                    <!-- /is Export Assignment Open -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-exportassignment-button"
                                data-target="#edit-modal" data-eid="">Διόρθωσε Ανάθεση Εξαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update/edit form -->

                    </div>
                </div>
            </div>




            <!-- the Delete existing Export Assignment, Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Ανάθεσης Εξαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>


                        <form id="delete-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('DELETE')  <!-- necessary fields for CSRF & Method type-->

                        <!-- Modal body -->
                        <div class="modal-body">

                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Διαγραφή Ανάθεσης Εξαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε την παρακάτω Ανάθεση Εξαγωγής;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-eid-del" name="modal-input-eid-del" value="" />
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-del">Αποθήκη</label>
                                        <input type="text" id="modal-input-warehouse-del" name="modal-input-warehouse-del"
                                          class="form-control-plaintext" value="" readonly />
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-deadline-del">Deadline Ανάθεσης Εισαγωγής (Ημ/νία &amp; Ώρα)</label>
                                        <input type="text" id="modal-input-deadline-del" name="modal-input-deadline-del"
                                          class="form-control-plaintext" value="" readonly />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                       <label class="col-form-label" for="modal-input-text-del">Ανάθεση Εξαγωγής</label>
                                       <textarea rows="3" name="modal-input-text-del" class="form-control-plaintext" id="modal-input-text-del"
                                            value="" readonly></textarea>
                                    </div>
                                    <!-- /name -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-exportassignment-button"
                                data-target="#delete-modal" data-toggle="modal" data-eid="">Διέγραψε Ανάθεση Εξαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end delete form -->

                    </div>
                </div>
            </div>


            <!-- Άνοιγμα Ανάθεσης Εξαγωγής -->
            <div class="modal modal-success fade" id="open-modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Άνοιγμα (Κλειστής) Ανάθεσης Εξαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>


                        <form id="open-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')  <!-- necessary fields for CSRF & Method type-->

                        <!-- Modal body -->
                        <div class="modal-body">

                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Διαγραφή Ανάθεσης Εξαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να ανοίξετε την παρακάτω Ανάθεση Εξαγωγής;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-eid-open" name="modal-input-eid-open" value="" />
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-open">Αποθήκη</label>
                                        <input type="text" id="modal-input-warehouse-open" name="modal-input-warehouse-open"
                                          class="form-control-plaintext" value="" readonly />
                                    </div>


                                    <div class="form-group">
                                    <label class="col-form-label" for="modal-input-deadline-open">Deadline Ανάθεσης Εξαγωγής (Ημ/νία &amp; Ώρα)</label>
                                        <input type="text" id="modal-input-deadline-open" name="modal-input-deadline-open"
                                          class="form-control-plaintext" value="" readonly />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                       <label class="col-form-label" for="modal-input-text-open">Ανάθεση Εξαγωγής</label>
                                       <textarea rows="3" name="modal-input-text-open" class="form-control-plaintext" id="modal-input-text-open"
                                            value="" readonly></textarea>
                                    </div>
                                    <!-- /name -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-success" id="open-button" name="open-exportassignment-button"
                                data-target="#open-modal" data-toggle="modal" data-eid="">Άνοιξε Ανάθεση Εξαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end open assignment form -->

                    </div>
                </div>
            </div>


            <!-- Κλείσιμο Ανάθεσης Εξαγωγής -->
            <div class="modal modal-warning fade" id="close-modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Κλείσιμο (Ανοιχτής) Ανάθεσης Εξαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>


                        <form id="close-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('PUT')  <!-- necessary fields for CSRF & Method type-->

                        <!-- Modal body -->
                        <div class="modal-body">

                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Διαγραφή Ανάθεσης Εξαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να κλείσετε την παρακάτω Ανάθεση Εξαγωγής;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-eid-close" name="modal-input-eid-close" value="" />
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-close">Αποθήκη</label>
                                        <input type="text" id="modal-input-warehouse-close" name="modal-input-warehouse-close"
                                          class="form-control-plaintext" value="" readonly />
                                    </div>


                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-deadline-close">Deadline Ανάθεσης Εξαγωγής (Ημ/νία &amp; Ώρα)</label>
                                        <input type="text" id="modal-input-deadline-close" name="modal-input-deadline-close"
                                          class="form-control-plaintext" value="" readonly />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                       <label class="col-form-label" for="modal-input-text-close">Ανάθεση Εξαγωγής</label>
                                       <textarea rows="3" name="modal-input-text-close" class="form-control-plaintext" id="modal-input-text-close"
                                            value="" readonly></textarea>
                                    </div>
                                    <!-- /name -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-warning" id="close-button" name="close-exportassignment-button"
                                data-target="#close-modal" data-toggle="modal" data-eid="">Κλείσε Ανάθεση Εξαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end open assignment form -->

                    </div>
                </div>
            </div>

            @endcanany  <!-- isSuperAdmin, isCompanyCEO, isWarehouseForeman, isWarehouseWorker -->

        </div>
      </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" /> -->
@stop

@section('js')

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript" defer></script> -->

    <script type="text/javascript">
    //console.log('Hi!');

    $(document).ready(function(){

         //configure & initialise the (Export Assignments) DataTable
         $('.table').DataTable({
            autoWidth: true,
            ordering: true,
            searching: true,
            select: true,
            dom: "Bfrtlip",
            /*
            buttons: [
                'copy',
                'excel',
                'csv',
                'pdf',
                'print',
            ],
            */
            buttons: [
                        {
                            "extend" : "copy",
                            "text"   : "Αντιγραφή",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5 ]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Αναθέσεις Εξαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Αναθέσεις Εξαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Αναθέσεις Εξαγωγής",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5]
                            }
                        },
                    ],
        });

        //for all 3 modals/actions, POST, PUT, DELETE
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                //"Content-Type": "application/json",
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
            }
        });



    });



        $('#date-time-picker-create').datetimepicker({
            format:'d-m-Y H:i',
            timepicker: true,
            datepicker: true,
            minDate: new Date()
            //lang: 'el',
        });

        $('#date-time-picker-edit').datetimepicker({
            format:'d-m-Y H:i',
            timepicker: true,
            datepicker: true,
            minDate: new Date()
            //lang: 'el',
        });
        jQuery.datetimepicker.setLocale('el');



        //Two helper functions for getting the filename plus the extension from the full file path
        function baseName(str)
        {
            var base = new String(str).substring(str.lastIndexOf('/') + 1);

            if(base.lastIndexOf(".") != -1){
                base = base.substring(0, base.lastIndexOf("."));
            }

            return base;
        }

        function base_name(path) {
            return path.split('/').reverse()[0];
        }





    //the 3 modals follow::
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var eid = button.data('eid'); // Extract info from data-* attributes
            var warehouse = button.data('warehouse');
            var export_text = button.data('text');
            var deadline = button.data('deadline');
			var files = button.data('files');
            var comments = button.data('comments');
			var isopen = button.data('isopen');


            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-eid-edit').val(eid);
            modal.find('.modal-body #modal-input-warehouse-edit').val(warehouse);
			modal.find('.modal-body #modal-input-text-edit').val(export_text);
			modal.find('.modal-body #date-time-picker-edit').val(deadline);
            modal.find('.modal-body #modal-input-comments-edit').val(comments);
			modal.find('.modal-body #modal-input-isopen-edit').val(isopen);

            modal.find('.modal-body #arxeia').empty();
            $.each(files, function(k, v){
                modal.find('.modal-body #arxeia').append('<li>' + base_name(v) + '</li>');
            });
            //modal.find('.modal-body #modal-input-files-edit').val(files);

            modal.find('.modal-footer #edit-button').attr("data-eid", eid);  //SET Export assignment id value in data-eid attribute


            //AJAX Update/Edit User
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(eid);
                console.log(formData);

                //reset the error field.
                $('.alert-danger').hide();
                $('.alert-danger').html('');

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/export/update/" + eid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/export/view/";
                            }
                        });
                    },
                    error: function(xhr){
                        console.log('Error:', xhr);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(xhr.status == 500){
                            msg = 'Η Ανάθεση Εξαγωγής υπάρχει ήδη!';
                        } else if (xhr.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης Ανάθεσης Εξαγωγής!';
                        } else if (xhr.status == 422){
                            msg = 'Δώσατε λάθος δεδομένα!';

                            var json_err = $.parseJSON(xhr.responseText); //responseJSON
                            $('.alert-danger').html('');

                            $.each(json_err.errors, function(key, value){
                                $('.alert-danger').show();
                                $('.alert-danger').append('<li>'+value+'</li>');
                            });
                        }

                        Swal.fire({
                            icon: "error",
                            type: "error",
                            title: 'Oops...',
                            text: msg,
                        });
                    }
                });
            });

        });


        //open modal edit/update
        $('#open-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var eid = button.data('eid'); // Extract info from data-* attributes
            var import_text = button.data('text');
            var deadline = button.data('deadline');
            var warehouse = button.data('warehousename');

            var modal = $(this);

			modal.find('.modal-body #modal-input-text-open').val(import_text);
			modal.find('.modal-body #modal-input-deadline-open').val(deadline);
            modal.find('.modal-body #modal-input-warehouse-open').val(warehouse);

            modal.find('.modal-footer #open-button').attr("data-eid", eid);  //SET import assignment id value in data-iid attribute




            //event delegation
            $(document).on("submit", "#open-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(eid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/export/open/" + eid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχές Άνοιγμα Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/export/view/";
                            }
                        });
                    },
                    error: function(xhr){
                        console.log('Error:', xhr);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(xhr.status == 500){
                            msg = 'Η Ανάθεση Εξαγωγής είναι ήδη ανοιχτή!';
                        } else if (xhr.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα ανοίγματος Ανάθεσης Εξαγωγής!';
                        }

                        Swal.fire({
                            icon: "error",
                            type: "error",
                            title: 'Oops...',
                            text: msg,
                        });
                    }
                });
            });

        });


        //close modal edit/update
        $('#close-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var eid = button.data('eid'); // Extract info from data-* attributes
            var import_text = button.data('text');
            var deadline = button.data('deadline');
            var warehouse = button.data('warehousename');

            var modal = $(this);

			modal.find('.modal-body #modal-input-text-close').val(import_text);
			modal.find('.modal-body #modal-input-deadline-close').val(deadline);
            modal.find('.modal-body #modal-input-warehouse-close').val(warehouse);

            modal.find('.modal-footer #close-button').attr("data-eid", eid);  //SET import assignment id value in data-iid attribute



            //event delegation
            $(document).on("submit", "#close-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(eid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/export/close/" + eid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχές Κλείσιμο Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/export/view/";
                            }
                        });
                    },
                    error: function(xhr){
                        console.log('Error:', xhr);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(xhr.status == 500){
                            msg = 'Η Ανάθεση Εξαγωγής είναι ήδη κλειστή!';
                        } else if (xhr.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα κλεισίματος Ανάθεσης Εξαγωγής!';
                        }

                        Swal.fire({
                            icon: "error",
                            type: "error",
                            title: 'Oops...',
                            text: msg,
                        });
                    }
                });
            });

        });




    //do not put the following inside $(document).ready()!
    $('#delete-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var eid = button.data('eid'); // Extract info from data-* attributes
            var export_text = button.data('text1');
            var deadline = button.data('deadline');
            var warehouse = button.data('warehousename');


            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            modal.find('.modal-body .card .card-body #modal-input-eid-del').val(eid); //change the value to...
            modal.find('.modal-body .card .card-body #modal-input-text-del').val(export_text);
            modal.find('.modal-body .card #modal-input-deadline-del').val(deadline);
            modal.find('.modal-body .card #modal-input-warehouse-del').val(warehouse);

            modal.find('.modal-footer #delete-button').attr("data-eid", eid); //SET user id value in data-eid attribute


            //AJAX Delete existing Product
            //event delegation here..
            $(document).on("submit", "#delete-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(eid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/export/delete/" + eid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/export/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(response.status == 500){
                            msg = 'Η Ανάθεση Εξαγωγής υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής Ανάθεσης Εξαγωγής!';
                        }

                        Swal.fire({
                            icon: "error",
                            type: "error",
                            title: 'Oops...',
                            text: msg,
                        });
                    }
                });
            });
        });



        //AJAX for Add/Create New Product submit
        //event delegation here..
        $(document).on("submit", "#add-form", function(evt){
            evt.preventDefault();
            var formData = new FormData(this);

            console.log(formData);

            //reset the error field.
            $('.alert-danger').hide();
            $('.alert-danger').html('');

            $.ajax({
                method: "POST",
                data: formData,
                cache: false,
                contentType: false, //do not set any content type header
                processData: false, //send non-processed data
                dataType: "json",
                url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/export/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/export/view/";
                            }
                        });
                },
                error: function(xhr){
                    console.log('Error:', xhr);

                    var msg = 'Συνέβη κάποιο λάθος!';

                    if(xhr.status == 500){
                        msg = 'Η Ανάθεση Εξαγωγής υπάρχει ήδη!';
                    } else if (xhr.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας Ανάθεσης Εξαγωγής!';
                    }  else if (xhr.status == 422){
                        msg = 'Δώσατε λάθος δεδομένα!';

                        var json_err = $.parseJSON(xhr.responseText); //responseJSON
                        $('.alert-danger').html('');

                        $.each(json_err.errors, function(key, value){
                            $('.alert-danger').show();
                            $('.alert-danger').append('<li>'+value+'</li>');
                        });
                    }

                    Swal.fire({
                        icon: "error",
                        type: "error",
                        title: 'Oops...',
                        text: msg,
                    });
                }
            });

        });


        //necessary addition
        /*
        $('#edit-modal').on('hidden.bs.modal', function() {
            $(this).find('#edit-form').off('click');
        });
        */

        //necessary additions for when the modals get hidden

        $('#edit-modal').on('hidden.bs.modal', function(e){
            $(document).off('submit', '#edit-form');
        });

        $('#delete-modal').on('hidden.bs.modal', function(e){
            $(document).off('submit', '#delete-form');
        });

        //resets the create/add form. Re-use this code snippet in other blade views!
        $(document).on('click', '[data-dismiss="modal"]', function(e){
            $('#add-form').find("input,textarea,select").val('');

            //reset the error field.
            $('.alert-danger').hide();
            $('.alert-danger').html('');
        });

        $('#add-expassgn-btn').on('click', function(evt){
            $('#add-form').find('select').val('');
        });


    </script>
@stop
