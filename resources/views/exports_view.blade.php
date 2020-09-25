{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Πίνακας Ελέγχου')

@section('content_header')
    <h1>Warehouse / Στοιχεία Αναθέσεων Εξαγωγής</h1>
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

            <p>Στοιχεία Αναθέσεων Εξαγωγής</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant'])

			<!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
						<th class="text-left">Υπεύθυνος Παράδοσης</th>
                        <th class="text-left">Εταιρεία Παράδοσης</th>
                        <th class="text-left">Ημ/νία &amp; Ώρα Παράδοσης</th>
                        <th class="text-left">Αρ.Κυκλοφορίας Μεταφορικού Μέσου</th>
                        <th class="text-left">Μεταφορική Εταιρεία</th>
						<th class="text-left">Τόπος Αποστολής</th>
						<th class="text-left">Τόπος Προορισμού</th>
						<th class="text-left">Χρεώσιμες Ώρες Εργασίας</th>
						<th class="text-left">Ώρες Εργασίας</th>
						<th class="text-left">Δελτίο Αποστολής</th>
						<th class="text-left">Διακριτός Τίτλος Παραλαβής</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($exports as $export)
                    <tr class="user-row" data-eid="{{ $export->id }}">  <!-- necessary additions -->
                        <td>{{ $export->warehouse->name }}</td>


                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-eid="{{ $exportassignment->id }}"
									>
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-eid="{{ $exportassignment->id }}"
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Στοιχείων Ανάθεσης Εξαγωγής</button>

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





			@canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant'])
            <!-- The 3 Modals, Add/Update/Delete -->

            <!-- the Add/Create new Export Assignment, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Στοιχείων Ανάθεσης Εξαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="add-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('POST')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Προσθήκη Προϊόντος</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-eid-create" name="modal-input-eid-create" value="">

									 <!-- recipient name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-recipient-create">Υπεύθυνος Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-recipient-create" class="form-control" id="modal-input-recipient-create"
                                                value="" required />
                                        </div>
									</div>
									<!-- /recipient name -->

									<!-- import company name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-expco-create">Eταιρεία Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-expco-create" class="form-control" id="modal-input-expco-create"
                                                value="" required />
                                        </div>
									</div>
									<!-- /import company name -->

									<!-- date_time_delivered_on -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtdeliv-create">Ημ/νία &amp; Ώρα Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-dtdeliv-create" class="form-control" id="modal-input-dtdeliv-create"
                                                value="" autocomplete="off" required />
                                        </div>
									</div>
									<!-- /date_time_delivered_on -->

									<!-- vehicle_registration_no -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-vehicleregno-create">Αρ.Κυκλοφορίας Μεταφορικού Μέσου</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-vehicleregno-create" class="form-control" id="modal-input-vehicleregno-create"
                                                value="" required />
                                        </div>
									</div>
									<!-- /vehicle_registration_no -->

									<!-- shipping_company -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-shipco-create">Μεταφορική Εταιρεία</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-shipco-create" class="form-control" id="modal-input-shipco-create"
                                                value="" required />
                                        </div>
									</div>
									<!-- /shipping_company -->

							<!-- sendingplace -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-sendplace-create">Τόπος Αποστολής</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-sendplace-create" class="form-control" id="modal-input-sendplace-create"
                                                value="" required />
                                        </div>
									</div>
									<!-- /sendingplace -->

									<!-- destination -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-destin-create">Τόπος Προορισμού</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-destin-create" class="form-control" id="modal-input-destin-create"
                                                value="" required />
                                        </div>
									</div>
									<!-- /destination -->

									<!-- chargeable_work_hours -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-chargehrs-create">Χρεώσιμες Ώρες Εργασίας</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-chargehrs-create" class="form-control" id="modal-input-chargehrs-create"
                                                value="" required />
                                        </div>
									</div>
									<!-- /chargeable_work_hours -->

									<!-- work_hours -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-hours-create">Ώρες Εργασίας</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-hours-create" class="form-control" id="modal-input-hours-create"
                                                value="" required />
                                        </div>
									</div>
									<!-- /work_hours -->

									<!-- shipping_bulletin -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-bulletin-create">Δελτίο Αποστολής [αρχείο PDF]</label>
                                        <div class="col-lg-9">
                                            <input type="file" name="modal-input-bulletin-create" class="form-control" id="modal-input-bulletin-create"
                                                value="" required />
                                        </div>
									</div>
									<!-- /shipping_bulletin -->

									<!-- delivery_description -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtitle-create">Διακριτός Τίτλος Παραλαβής</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-dtitle-create" class="form-control" id="modal-input-dtitle-create"
                                                value="" required />
                                        </div>
									</div>
									<!-- /delivery_description -->




                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-export-button"
                                data-target="#add-modal" data-toggle="modal">Πρόσθεσε Στοιχεία Ανάθεσης Εξαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>



            <!-- the Edit/Update existing ExportAssignment, Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Στοιχείων Ανάθεσης Εξαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="edit-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('PUT')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Μεταβολή Ανάθεσης Εξαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-eid-edit" name="modal-input-eid-edit" value="">

									 <!-- recipient name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-recipient-edit">Υπεύθυνος Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-recipient-edit" class="form-control" id="modal-input-recipient-edit"
                                                value="" required />
                                        </div>
									</div>
									<!-- /recipient name -->

									<!-- import company name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-expco-edit">Eταιρεία Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-expco-edit" class="form-control" id="modal-input-expco-edit"
                                                value="" required />
                                        </div>
									</div>
									<!-- /import company name -->

									<!-- date_time_delivered_on -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtdeliv-edit">Ημ/νία &amp; Ώρα Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-dtdeliv-edit" class="form-control" id="modal-input-dtdeliv-edit"
                                                value="" autocomplete="off" required />
                                        </div>
									</div>
									<!-- /date_time_delivered_on -->

									<!-- vehicle_registration_no -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-vehicleregno-edit">Αρ.Κυκλοφορίας Μεταφορικού Μέσου</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-vehicleregno-edit" class="form-control" id="modal-input-vehicleregno-edit"
                                                value="" required />
                                        </div>
									</div>
									<!-- /vehicle_registration_no -->

									<!-- shipping_company -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-shipco-edit">Μεταφορική Εταιρεία</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-shipco-edit" class="form-control" id="modal-input-shipco-edit"
                                                value="" required />
                                        </div>
									</div>
									<!-- /shipping_company -->

							<!-- sendingplace -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-sendplace-edit">Τόπος Αποστολής</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-sendplace-edit" class="form-control" id="modal-input-sendplace-edit"
                                                value="" required />
                                        </div>
									</div>
									<!-- /sendingplace -->

									<!-- destination -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-destin-edit">Τόπος Προορισμού</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-destin-edit" class="form-control" id="modal-input-destin-edit"
                                                value="" required />
                                        </div>
									</div>
									<!-- /destination -->

									<!-- chargeable_work_hours -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-chargehrs-edit">Χρεώσιμες Ώρες Εργασίας</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-chargehrs-edit" class="form-control" id="modal-input-chargehrs-edit"
                                                value="" required />
                                        </div>
									</div>
									<!-- /chargeable_work_hours -->

									<!-- work_hours -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-hours-edit">Ώρες Εργασίας</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-hours-edit" class="form-control" id="modal-input-hours-edit"
                                                value="" required />
                                        </div>
									</div>
									<!-- /work_hours -->

									<!-- shipping_bulletin -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-bulletin-edit">Δελτίο Αποστολής [αρχείο PDF]</label>
                                        <div class="col-lg-9">
                                            <input type="file" name="modal-input-bulletin-edit" class="form-control" id="modal-input-bulletin-edit"
                                                value="" required />
                                        </div>
									</div>
									<!-- /shipping_bulletin -->

									<!-- delivery_description -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtitle-edit">Διακριτός Τίτλος Παραλαβής</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-dtitle-edit" class="form-control" id="modal-input-dtitle-edit"
                                                value="" required />
                                        </div>
									</div>
									<!-- /delivery_description -->



                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-export-button"
                                data-target="#edit-modal" data-toggle="modal" data-eid="">Διόρθωσε Ανάθεση Εξαγωγής</button>
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
                            <h4 class="modal-title">Διαγραφή Στοιχείων Ανάθεσης Εξαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>


                        <form id="delete-form" class="form-horizontal" method="POST">
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
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε τα παρακάτω Στοιχεία Ανάθεσης Εξαγωγής;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-eid-del" name="modal-input-eid-del" value="" />
                                    </div>

                                    <!-- date_time_delivered_on -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtdeliv-del">Ημ/νία &amp; Ώρα Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-dtdeliv-del" class="form-control-plaintext" id="modal-input-dtdeliv-del"
                                                value="" autocomplete="off" required />
                                        </div>
									</div>
									<!-- /date_time_delivered_on -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-export-button"
                                data-target="#delete-modal" data-toggle="modal" data-eid="">Διέγραψε Στοιχεία Ανάθεσης Εξαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end delete form -->

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
                                columns: [ 0, 1, 2, 3, 4, 5,6,7,8,9,10]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Στοιχεία Αναθέσεων Εξαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7,8,9,10]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Στοιχεία Αναθέσεων Εξαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7,8,9,10]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Στοιχεία Αναθέσεων Εξαγωγής",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7,8,9,10]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7,8,9,10]
                            }
                        },
                    ],
        });

        //for all 3 modals/actions, POST, PUT, DELETE
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });



    });


        $('#modal-input-dtdeliv-create').datetimepicker({
            format:'d-m-Y H:i',
            timepicker: true,
            datepicker: true,
            minDate: new Date()
            //lang: 'el',
        });

        $('#modal-input-dtdeliv-edit').datetimepicker({
            format:'d-m-Y H:i',
            timepicker: true,
            datepicker: true,
            minDate: new Date()
            //lang: 'el',
        });

        jQuery.datetimepicker.setLocale('el');



    //the 3 modals follow::
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var eid = button.data('eid'); // Extract info from data-* attributes
            var warehouse = button.data('warehouse');


            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-eid-edit').val(eid);
            modal.find('.modal-body #modal-input-warehouse-edit').val(warehouse);


            modal.find('.modal-footer #edit-button').attr("data-eid", eid);  //SET Export assignment id value in data-eid attribute


            //AJAX Update/Edit User
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
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
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/update/" + eid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Στοιχείων Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(response.status == 500){
                            msg = 'Τα Στοιχεία Ανάθεσης Εξαγωγής υπάρχουν ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης Στοιχείων Ανάθεσης Εξαγωγής!';
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


            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            modal.find('.modal-body .card .card-body #modal-input-eid-del').val(eid); //change the value to...
            modal.find('.modal-body .card .card-body #modal-input-text-del').val(export_text);

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
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/delete/" + eid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Στοιχείων Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(response.status == 500){
                            msg = 'Τα Στοιχεία Ανάθεσης Εξαγωγής υπάρχουν ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής Στοιχεώιν Ανάθεσης Εξαγωγής!';
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

            $.ajax({
                method: "POST",
                data: formData,
                cache: false,
                contentType: false, //do not set any content type header
                processData: false, //send non-processed data
                dataType: "json",
                url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Στοιχείων Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/view/";
                            }
                        });
                },
                error: function(response){
                    console.log('Error:', response);

                    var msg = 'Συνέβη κάποιο λάθος!';

                    if(response.status == 500){
                        msg = 'Τα Στοιχεία Ανάθεσης Εξαγωγής υπάρχει ήδη!';
                    } else if (response.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας Στοιχείων Ανάθεσης Εξαγωγής!';
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
        });


    </script>
@stop
