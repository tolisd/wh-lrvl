{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Dashboard')

@section('content_header')
    <h1>Warehouse / Κατηγορίες Προϊόντων</h1>
@stop


@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        <div class="col-lg-12 col-xs-6">

            <p>Κατηγορίες Προϊόντων</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])
            <!-- insert here the main product categories table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="5">
                <thead>
                    <tr>
                        <th class="text-left">Όνομα</th>
                        <th class="text-left">Περιγραφή</th>
                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($categories as $categ)
                    <tr class="user-row" data-cid="{{ $categ->id }}">  <!-- necessary additions -->
                        <td>{{ $categ->name }}</td>
                        <td>{{ $categ->description }}</td>
                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-cid="{{ $categ->id }}"
                                    data-name="{{ $categ->name }}"
                                    data-description="{{ $categ->description}}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-cid="{{ $categ->id }}"
                                    data-name="{{ $categ->name }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New Products Category button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Κατηγορίας Προϊόντος</button>

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


            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])
            <!-- The 3 Modals, Add/Update/Delete -->

            <!-- the Add/Create new products Category, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Νέας Κατηγορίας Προϊόντος</h4>
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
                                    <h2 class="m-0">Προσθήκη Κατηγορίας Προϊόντος</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-cid-create" name="modal-input-cid-create" value="">

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-create">Όνομα Κατηγορίας</label>
                                        <input type="text" name="modal-input-name-create" class="form-control" id="modal-input-name-create"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /name -->

                                    <!-- description -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-create">Περιγραφή</label>
                                        <textarea rows="3" name="modal-input-description-create" class="form-control" id="modal-input-description-create"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /description -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-category-button"
                                data-target="#add-modal" data-toggle="modal">Πρόσθεσε Κατηγορία Προϊόντος</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>



            <!-- the Edit/Update existing Products Category, Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Κατηγορίας Προϊόντος</h4>
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
                                    <h2 class="m-0">Μεταβολή Κατηγορίας Προϊόντος</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-cid-edit" name="modal-input-cid-edit" value="">

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Όνομα Κατηγορίας</label>
                                        <input type="text" name="modal-input-name-edit" class="form-control" id="modal-input-name-edit"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /name -->

                                    <!-- description -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-edit">Περιγραφή</label>
                                        <textarea rows="3" name="modal-input-description-edit" class="form-control" id="modal-input-description-edit"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /description -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-category-button"
                                data-target="#edit-modal" data-toggle="modal" data-cid="">Διόρθωσε Κατηγορία Προϊόντος</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update/edit form -->

                    </div>
                </div>
            </div>




            <!-- the Delete existing Products Category, Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Κατηγορίας Προϊόντος</h4>
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
                                    <h2 class="m-0">Διαγραφή Προϊόντος</h2>
                                </div>
                                -->
                                <div class="card-body">
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε την παρακάτω κατηγορία;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-cid-del" name="modal-input-cid-del" value="" />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-del">Όνομα</label>
                                        <input type="text" name="modal-input-name-del" class="form-control-plaintext" id="modal-input-name-del"
                                            value="" readonly required autofocus />
                                    </div>
                                    <!-- /name -->
                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-category-button"
                                data-target="#delete-modal" data-toggle="modal" data-cid="">Διέγραψε Κατηγορία Προϊόντος</button>
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />
@stop

@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript" defer></script>

    <script>
    //console.log('Hi!');

    $(document).ready(function(){

         //configure & initialise the (Products Categories) DataTable
         $('.table').DataTable({
            autoWidth: true,
            ordering: true,
            searching: true,
            select: true,
            //dom: "Brftip",
            /*
            buttons: [
                'copy',
                'excel',
                'csv',
                'pdf',
                'print',
            ],
            */
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

            var cid = button.data('cid'); // Extract info from data-* attributes
            var name = button.data('name');
            var description = button.data('description');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-pid-edit').val(pid);
            modal.find('.modal-body #modal-input-name-edit').val(name);
            modal.find('.modal-body #modal-input-description-edit').val(description);

            modal.find('.modal-footer #edit-button').attr("data-cid", cid);  //SET category id value in data-cid attribute



            //AJAX Update/Edit Products Category
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(cid);
                console.log(formData);

                $.ajax({
                    type: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/product_category/update/" + cid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Κατηγορίας!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Send Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/product_category/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Η κατηγορία υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης κατηγορίας!';
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

            var cid = button.data('cid'); // Extract info from data-* attributes
            var name = button.data('name');

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            modal.find('.modal-body .card .card-body #modal-input-cid-del').val(cid); //change the value to...
            modal.find('.modal-body .card .card-body #modal-input-name-del').val(name);

            modal.find('.modal-footer #delete-button').attr("data-cid", cid); //SET category id value in data-cid attribute


            //AJAX Delete existing Products Category
            //event delegation here..
            $(document).on("submit", "#delete-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(cid);
                console.log(formData);

                $.ajax({
                    method: "DELETE",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json", //added for debugging purposes...
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/product_category/delete/"+ cid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Κατηγορίας!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/product_category/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Η κατηγορία υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής κατηγορίας!';
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






        //AJAX for Add/Create New Products Category submit
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
                url: "{{ url(request()->route()->getPrefix()) }}" + "/product_category/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Κατηγορίας!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/product_category/view/";
                            }
                        });
                },
                error: function(response){
                    console.log('Error:', response);

                    var msg = 'Κάτι πήγε στραβά..!';

                    if(response.status == 500){
                        msg = 'Η κατηγορία υπάρχει ήδη!';
                    } else if (response.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας κατηγορίας!';
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





    </script>
@stop
