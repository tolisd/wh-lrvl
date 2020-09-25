{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Πίνακας Ελέγχου')

@section('content_header')
    <h1>Warehouse / Όλες οι Αναθέσεις Εισαγωγής</h1>
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

            <p>Όλες οι Αναθέσεις Εισαγωγής</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant', 'isNormalUser'])

			<!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Αποθήκη</th>
                        <th class="text-left">Κείμενο Ανάθεσης</th>
                        <th class="text-left">Deadline</th>
                        <th class="text-left">Επισυναπτόμενα Αρχεία</th>
                        <th class="text-left">Σχόλια</th>
						<th class="text-left">Ανοιχτή?</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($importassignments as $importassignment)
                    <tr class="user-row" data-iid="{{ $importassignment->id }}">  <!-- necessary additions -->
                        <td>{{ $importassignment->warehouse->name }}</td>
                        <td>{{ $importassignment->import_assignment_text }}</td>
                        <td>{{ $importassignment->import_deadline }}</td>
                        <td>{{ $importassignment->uploaded_files }}</td>
                        <td>{{ $importassignment->comments }}</td>
                        <td>
                            @if($importassignment->is_open == 1)
                                Ανοικτή
                            @elseif($importassignment->is_open == 0)
                                Κλειστή
                            @endif
                        </td>
                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-iid="{{ $importassignment->id }}"
									data-warehouse="{{ $importassignment->warehouse->name }}"
                                    data-text="{{ $importassignment->import_assignment_text }}"
                                    data-deadline="{{ $importassignment->deadline }}"
                                    data-files="{{ $importassignment->uploaded_files }}"
									data-comments="{{ $importassignment->comments }}"
									data-isopen="{{ $importassignment->is_open }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-iid="{{ $importassignment->id }}"
                                    data-text1="{{ $importassignment->import_assignment_text }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New User button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Νέας Ανάθεσης Εισαγωγής</button>

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

            <!-- the Add/Create new Import Assignment, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Νέας Ανάθεσης Εισαγωγής</h4>
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
                                    <h2 class="m-0">Προσθήκη Ανάθεσης Εισαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-iid-create" name="modal-input-iid-create" value="">

                                    <!-- warehouse name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-create">Αποθήκη</label>
                                        <select name="modal-input-warehouse-create" id="modal-input-warehouse-create" class="form-control" required>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /warehouse name -->

									<!-- assignment text -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-text-create">Κείμενο Ανάθεσης Εισαγωγής</label>
                                        <textarea rows="3" name="modal-input-text-create" class="form-control" id="modal-input-text-create"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /assignment text -->

									<!-- deadline datetime -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="date-time-picker-create">Deadline (Ημερομηνία &amp; Ώρα)</label>
                                        <input type="text" name="modal-input-picker-create" class="form-control" id="date-time-picker-create"
                                            value="" autocomplete="off" required />
                                    </div>
                                    <!-- /deadline datetime -->

									<!-- uploaded files -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-files-create">Επισυναπτόμενα Αρχεία</label>
                                        <input type="file" multiple name="modal-input-files-create[]" class="form-control" id="modal-input-files-create"
                                            value="" />
                                    </div>
                                    <!-- /uploaded files -->

									<!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-create">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-create" class="form-control" id="modal-input-comments-create"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /comments -->


                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-importassignment-button"
                                data-target="#add-modal" data-toggle="modal">Πρόσθεσε Ανάθεση Εισαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>



            <!-- the Edit/Update existing ImportAssignment, Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Ανάθεσης Εισαγωγής</h4>
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
                                    <h2 class="m-0">Μεταβολή Ανάθεσης Εισαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-iid-edit" name="modal-input-iid-edit" value="">

									<!-- warehouse name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-edit">Αποθήκη</label>
                                        <select name="modal-input-warehouse-edit" id="modal-input-warehouse-edit" class="form-control" required>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /warehouse name -->

									 <!-- assignment text -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-text-edit">Κείμενο Ανάθεσης Εισαγωγής</label>
                                        <textarea rows="3" name="modal-input-text-edit" class="form-control" id="modal-input-text-edit"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /assignment text -->

									<!-- deadline datetime -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="date-time-picker-edit">Deadline (Ημερομηνία &amp; Ώρα)</label>
                                        <input type="text" name="modal-input-picker-edit" class="form-control" id="date-time-picker-edit"
                                            value="" autocomplete="off" required />
                                    </div>
                                    <!-- /deadline datetime -->

									<!-- uploaded files -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-files-edit">Επισυναπτόμενα Αρχεία</label>
                                        <input type="file" multiple name="modal-input-files-edit[]" class="form-control" id="modal-input-files-edit"
                                            value="" />
                                    </div>
                                    <!-- /uploaded files -->

									<!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-edit">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-edit" class="form-control" id="modal-input-comments-edit"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /comments -->

									<!-- is Import Assignment Open -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-isopen-edit">Ανοιχτή?</label>
                                        <select name="modal-input-isopen-edit" id="modal-input-isopen-edit" class="form-control" required>
                                            <option value="1">Ανοιχτή</option>
                                            <option value="0">Κλειστή</option>
                                        </select>
                                    </div>
                                    <!-- /is Import Assignment Open -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-importassignment-button"
                                data-target="#edit-modal" data-toggle="modal" data-iid="">Διόρθωσε Ανάθεση Εισαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update/edit form -->

                    </div>
                </div>
            </div>




            <!-- the Delete existing Import Assignment, Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Ανάθεσης Εισαγωγής</h4>
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
                                    <h2 class="m-0">Διαγραφή Ανάθεσης Εισαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε την παρακάτω Ανάθεση Εισαγωγής;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-iid-del" name="modal-input-iid-del" value="" />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                       <label class="col-form-label" for="modal-input-text-del">Ανάθεση Εισαγωγής</label>
                                       <textarea rows="3" name="modal-input-text-del" class="form-control-plaintext" id="modal-input-text-del"
                                            value=""></textarea>
                                    </div>
                                    <!-- /name -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-importassignment-button"
                                data-target="#delete-modal" data-toggle="modal" data-iid="">Διέγραψε Ανάθεση Εισαγωγής</button>
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

         //configure & initialise the (Import Assignments) DataTable
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
                                columns: [ 0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Αναθέσεις Εισαγωγής",
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Αναθέσεις Εισαγωγής",
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Αναθέσεις Εισαγωγής",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5]
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



    //the 3 modals follow::
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var iid = button.data('iid'); // Extract info from data-* attributes
            var warehouse = button.data('warehouse');
            var import_text = button.data('text');
            var deadline = button.data('deadline');
			var files = button.data('files');
            var comments = button.data('comments');
			var isopen = button.data('isopen');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-iid-edit').val(iid);
            modal.find('.modal-body #modal-input-warehouse-edit').val(warehouse);
			modal.find('.modal-body #modal-input-text-edit').val(import_text);
			modal.find('.modal-body #modal-input-picker-edit').val(deadline);
            modal.find('.modal-body #modal-input-files-edit').val(files);
            modal.find('.modal-body #modal-input-comments-edit').val(comments);
			modal.find('.modal-body #modal-input-isopen-edit').val(isopen);

            modal.find('.modal-footer #edit-button').attr("data-iid", iid);  //SET import assignment id value in data-iid attribute


            //AJAX Update/Edit User
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(iid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/import-assignments/update/" + iid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Ανάθεσης Εισαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/import-assignments/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(response.status == 500){
                            msg = 'Η Ανάθεση Εισαγωγής υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης Ανάθεσης Εισαγωγής!';
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

            var iid = button.data('iid'); // Extract info from data-* attributes
            var import_text = button.data('text1');


            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            modal.find('.modal-body .card .card-body #modal-input-iid-del').val(iid); //change the value to...
            modal.find('.modal-body .card .card-body #modal-input-text-del').val(import_text);

            modal.find('.modal-footer #delete-button').attr("data-iid", iid); //SET user id value in data-iid attribute


            //AJAX Delete existing Product
            //event delegation here..
            $(document).on("submit", "#delete-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(iid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/import-assignments/delete/" + iid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Ανάθεσης Εισαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/import-assignments/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(response.status == 500){
                            msg = 'Η Ανάθεση Εισαγωγής υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής Ανάθεσης Εισαγωγής!';
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
                url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/import-assignments/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Ανάθεσης Εισαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/import-assignments/view/";
                            }
                        });
                },
                error: function(response){
                    console.log('Error:', response);

                    var msg = 'Συνέβη κάποιο λάθος!';

                    if(response.status == 500){
                        msg = 'Η Ανάθεση Εισαγωγής υπάρχει ήδη!';
                    } else if (response.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας Ανάθεσης Εισαγωγής!';
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
        });


    </script>
@stop
