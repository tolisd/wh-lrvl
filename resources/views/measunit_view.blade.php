
@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Αποθήκη // Μονάδες Μέτρησης</h1>
@stop

<!-- Originally, NO sidebar section in this file! I added this section! Beware! -->

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token, necessary addition for $.ajax() in jQuery -->

    <div class="row">
        <div class="col-lg-6 col-xs-6">

        <p>Μονάδες Μέτρησης Υλικών/Προϊόντων</p>

        @canany(['isSuperAdmin','isCompanyCEO','isWarehouseForeman', 'isWarehouseWorker'])
            <!-- insert here the main my charged tools table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Μονάδα Μέτρησης</th>
                        <th class="text-left">Περιγραφή</th>  <!-- olografws, px "tetragwnika metra" -->
                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($measunits as $meas_unit)
                    <tr>
                        <td>{{ $meas_unit->name }}</td>
                        <td>{{ $meas_unit->description }}</td>
                        <td>
                            <button class="edit-modal btn btn-info"
                                data-toggle="modal" data-target="#edit-modal"
                                data-mid="{{ $meas_unit->id}}"
                                data-name="{{ $meas_unit->name }}"
                                data-descr="{{ $meas_unit->description }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                data-toggle="modal" data-target="#delete-modal"
                                data-mid="{{ $meas_unit->id }}"
                                data-code="{{ $meas_unit->name }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <br/><br/>

            <!--Create New Products Type button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Μονάδας Μέτρησης</button>

            <br/><br/>
            @endcanany <!-- isWarehouseForeman, isWarehouseWorker, isSuperAdmin, isCompanyCEO -->

            <br/><br/>




            @can('isSuperAdmin')
                <a href="{{ route('admin.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isCompanyCEO')
                <a href="{{ route('manager.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isWarehouseForeman')
                <a href="{{ route('foreman.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isWarehouseWorker')
                <a href="{{ route('worker.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])
            <!-- The 3 Modals, Add/Update/Delete -->

            <!-- the Add/Create new products Type, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Νέας Μονάδας Μέτρησης</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="add-form" class="form-horizontal" method="POST">
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('POST')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Προσθήκη Μονάδας Μέτρησης</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-mid-create" name="modal-input-mid-create" value="">

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-create">Όνομα Μονάδας Μέτρησης</label>
                                        <input type="text" name="modal-input-name-create" class="form-control" id="modal-input-name-create"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /name -->

                                    <!-- description -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-create">Περιγραφή Μονάδας Μέτρησης</label>
                                        <textarea rows="3" name="modal-input-description-create" class="form-control" id="modal-input-description-create"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /description -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-type-button"
                                data-target="#add-modal" data-toggle="modal">Πρόσθεσε Είδος Προϊόντος</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>



            <!-- the Edit/Update existing Products Type, Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Μονάδας Μέτρησης</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="edit-form" class="form-horizontal" method="POST">
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('PUT')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Μεταβολή Μονάδας Μέτρησης</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-mid-edit" name="modal-input-mid-edit" value="">

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Όνομα Μονάδας Μέτρησης</label>
                                        <input type="text" name="modal-input-name-edit" class="form-control" id="modal-input-name-edit"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /name -->

                                    <!-- description -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-edit">Περιγραφή Μονάδας Μέτρησης</label>
                                        <textarea rows="3" name="modal-input-description-edit" class="form-control" id="modal-input-description-edit"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /description -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-type-button"
                                data-target="#edit-modal" data-toggle="modal" data-mid="">Διόρθωσε Μονάδα Μέτρησης</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update/edit form -->

                    </div>
                </div>
            </div>




            <!-- the Delete existing Products Type, Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Μονάδας Μέτρησης</h4>
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
                                    <h2 class="m-0">Διαγραφή Μονάδας Μέτρησης</h2>
                                </div>
                                -->
                                <div class="card-body">
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε την παρακάτω μονάδα μέτρησης;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-mid-del" name="modal-input-mid-del" value="" />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-del">Όνομα Μονάδας Μέτρησης</label>
                                        <input type="text" name="modal-input-name-del" class="form-control-plaintext" id="modal-input-name-del"
                                            value="" readonly required autofocus />
                                    </div>
                                    <!-- /name -->
                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-type-button"
                                data-target="#delete-modal" data-toggle="modal" data-mid="">Διέγραψε Μονάδα Μέτρησης</button>
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
@stop

@section('js')
    <script type="text/javascript">
        //console.log('Hi!');
        $(document).ready(function(){
            $('.table').DataTable({
                autoWidth: true,
                ordering: true,
                searching: true,
                select: true,
            });

            //for all 3 modals/actions, POST, PUT, DELETE
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });


         //the 3 modals follow::
         $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var mid = button.data('mid'); // Extract info from data-* attributes
            var name = button.data('name');
            var description = button.data('description');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-mid-edit').val(mid);
            modal.find('.modal-body #modal-input-name-edit').val(name);
            modal.find('.modal-body #modal-input-description-edit').val(description);

            modal.find('.modal-footer #edit-button').attr("data-mid", mid);  //SET type id value in data-mid attribute



            //AJAX Update/Edit Products Type
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(mid);
                console.log(formData);

                $.ajax({
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/meas-units/update/" + mid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Μονάδας Μέτρησης!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/meas-units/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Η Μονάδα Μέτρησης υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης Μονάδα Μέτρησης!';
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

            var mid = button.data('mid'); // Extract info from data-* attributes
            var name = button.data('name');

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            modal.find('.modal-body .card .card-body #modal-input-mid-del').val(mid); //change the value to...
            modal.find('.modal-body .card .card-body #modal-input-name-del').val(name);

            modal.find('.modal-footer #delete-button').attr("data-mid", mid); //SET type id value in data-mid attribute


            //AJAX Delete existing Products Type
            //event delegation here..
            $(document).on("submit", "#delete-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(mid);
                console.log(formData);

                $.ajax({
                    method: "DELETE",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json", //added for debugging purposes...
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/meas-units/delete/"+ mid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Μονάδας Μέτρησης!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/meas-units/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Η Μονάδα Μέτρησης υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής Μονάδας Μέτρησης!';
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








        //AJAX for Add/Create New Products Type submit
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
                url: "{{ url(request()->route()->getPrefix()) }}" + "/meas-units/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Μονάδας Μέτρησης!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/meas-units/view/";
                            }
                        });
                },
                error: function(response){
                    console.log('Error:', response);

                    var msg = 'Κάτι πήγε στραβά..!';

                    if(response.status == 500){
                        msg = 'Η Μονάδα Μέτρησης υπάρχει ήδη!';
                    } else if (response.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας Μονάδας Μέτρησης!';
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
                //console.log('EDIT modal event, is off!')
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
