{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Πίνακας Ελέγχου')

@section('content_header')
    <h1>Warehouse / Όλες οι Αποθήκες</h1>
@stop


@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token, necessary additoin for $.ajax() in jQuery -->

    <div class="row">
        <div class="col-lg-12 col-xs-6">

            <p>Όλες οι Αποθήκες</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="5">
                <thead>
                    <tr>
                        <th class="text-left">Όνομα</th>
                        <th class="text-left">Διεύθυνση</th>
                        <th class="text-left">Πόλη</th>
                        <th class="text-left">Τηλέφωνο</th>
                        <th class="text-left">E-mail</th>
                        <th class="text-left">Όνομα Εταιρείας</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($warehouses as $warehouse)
                    <tr class="user-row" data-wid="{{ $warehouse->id }}">  <!-- necessary additions -->
                        <td>{{ $warehouse->name }}</td>
                        <td>{{ $warehouse->address }}</td>
                        <td>{{ $warehouse->city }}</td>
                        <td>{{ $warehouse->phone_number }}</td>
                        <td>{{ $warehouse->email }}</td>
                        <td>{{ $warehouse->company->name }}</td>
                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-wid="{{ $warehouse->id }}"
                                    data-name="{{ $warehouse->name }}"
                                    data-address="{{ $warehouse->address }}"
                                    data-city="{{ $warehouse->city }}"
                                    data-telno="{{ $warehouse->phone_number }}"
                                    data-email="{{ $warehouse->email }}"
                                    data-company="{{ $warehouse->company->name }}">  <!-- id instead of name, OR just name? -->
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-wid="{{ $warehouse->id }}"
                                    data-name="{{ $warehouse->name }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New Products Type button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Νέας Αποθήκης</button>

            <br/><br/>
            @endcanany  <!-- ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'] -->



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


            <!-- the 3 modals, PUT/DELETE/POST -->
            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])

            <!-- the Add/Create new Warehouse, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Νέας Αποθήκης</h4>
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
                                    <h2 class="m-0">Προσθήκη Προϊόντος</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-wid-create" name="modal-input-wid-create" value="">

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-create">Όνομα</label>
                                        <input type="text" name="modal-input-name-create" class="form-control" id="modal-input-name-create"
                                            value="" required autofocus />
                                    </div>
                                    <!-- /name -->

                                    <!-- address -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-address-create">Διεύθυνση</label>
                                        <input type="text" name="modal-input-address-create" class="form-control" id="modal-input-address-create"
                                           value="" required />
                                    </div>
                                    <!-- /address -->

                                    <!-- city -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-city-create">Πόλη</label>
                                        <input type="text" name="modal-input-city-create" class="form-control" id="modal-input-city-create"
                                           value="" required />
                                    </div>
                                    <!-- /city -->

                                    <!-- telno -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-telno-create">Τηλέφωνο</label>
                                        <input type="text" name="modal-input-telno-create" class="form-control" id="modal-input-telno-create"
                                            value="" required />
                                    </div>
                                    <!-- /telno -->

                                    <!-- email -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-email-create">E-mail</label>
                                        <input type="text" name="modal-input-email-create" class="form-control" id="modal-input-email-create"
                                            value="" required />
                                    </div>
                                    <!-- /email -->


                                    <!-- company -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-company-create">Εταιρεία</label>
                                        <select name="modal-input-company-create" id="modal-input-company-create" class="form-control" required>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /company -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-warehouse-button"
                                data-target="#add-modal" data-toggle="modal">Πρόσθεσε Αποθήκη</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>



            <!-- the Edit/Update existing Warehouse, Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Αποθήκης</h4>
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
                                    <h2 class="m-0">Μεταβολή Προϊόντος</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-wid-edit" name="modal-input-wid-edit" value="">

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Όνομα</label>
                                        <input type="text" name="modal-input-name-edit" class="form-control" id="modal-input-name-edit"
                                            value="" required autofocus />
                                    </div>
                                    <!-- /name -->

                                    <!-- address -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-address-edit">Διεύθυνση</label>
                                        <input type="text" name="modal-input-address-edit" class="form-control" id="modal-input-address-edit"
                                           value="" required />
                                    </div>
                                    <!-- /address -->

                                    <!-- city -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-city-edit">Πόλη</label>
                                        <input type="text" name="modal-input-city-edit" class="form-control" id="modal-input-city-edit"
                                           value="" required />
                                    </div>
                                    <!-- /city -->

                                    <!-- telno -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-telno-edit">Τηλέφωνο</label>
                                        <input type="text" name="modal-input-telno-edit" class="form-control" id="modal-input-telno-edit"
                                            value="" required />
                                    </div>
                                    <!-- /telno -->

                                    <!-- email -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-email-edit">E-mail</label>
                                        <input type="text" name="modal-input-email-edit" class="form-control" id="modal-input-email-edit"
                                            value="" required />
                                    </div>
                                    <!-- /email -->


                                    <!-- company -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-company-edit">Εταιρεία</label>
                                        <select name="modal-input-company-edit" id="modal-input-company-edit" class="form-control" required>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /company -->


                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-warehouse-button"
                                data-target="#edit-modal" data-toggle="modal" data-wid="">Διόρθωσε Αποθήκη</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update/edit form -->

                    </div>
                </div>
            </div>




            <!-- the Delete existing Warehouse, Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Αποθήκης</h4>
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
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε την παρακάτω Αποθήκη;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-wid-del" name="modal-input-wid-del" value="" />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-del">Όνομα Αποθήκης</label>
                                        <input type="text" name="modal-input-name-del" class="form-control-plaintext" id="modal-input-name-del"
                                            value="" readonly required />
                                    </div>
                                    <!-- /name -->
                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-warehouse-button"
                                data-target="#delete-modal" data-toggle="modal" data-wid="">Διέγραψε Αποθήκη</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end delete form -->

                    </div>
                </div>
            </div>


            @endcanany <!-- ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'] -->

        </div>
      </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript" defer></script>

    <script type="text/javascript">
    // console.log('Hi!');

    $(document).ready(function(){

        //configure & initialise the (Warehouses) DataTable
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
                            "text"   : "Αντιγραφή"
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Αποθήκες"
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Αποθήκες"
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Αποθήκες"
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση"
                        },
                    ],
        });

        //for all 3 modals/actions, POST, PUT, DELETE
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
            }
        });

    });



    //the 3 modals follow::
    $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var wid = button.data('wid'); // Extract info from data-* attributes
            var name = button.data('name');
            var address = button.data('address');
            var city = button.data('city');
            var telno = button.data('telno');
            var email = button.data('email');
            var company = button.data('company');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-wid-edit').val(wid);
            modal.find('.modal-body #modal-input-name-edit').val(name);
            modal.find('.modal-body #modal-input-address-edit').val(address);
            modal.find('.modal-body #modal-input-city-edit').val(city);
            modal.find('.modal-body #modal-input-telno-edit').val(telno);
            modal.find('.modal-body #modal-input-email-edit').val(email);
            modal.find('.modal-body #modal-input-company-edit').val(company);

            modal.find('.modal-footer #edit-button').attr("data-wid", wid);  //SET product id value in data-wid attribute


            //AJAX Update/Edit Warehouse Data
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(wid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/warehouses/update/" + wid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Αποθήκης!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/warehouses/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Η αποθήκη υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης αποθήκης!';
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

            var wid = button.data('wid'); // Extract info from data-* attributes
            var name = button.data('name');

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            modal.find('.modal-body .card .card-body #modal-input-wid-del').val(wid); //change the value to...
            modal.find('.modal-body .card .card-body #modal-input-name-del').val(name);

            modal.find('.modal-footer #delete-button').attr("data-wid", wid); //SET user id value in data-wid attribute


            //AJAX Delete existing Warehouse
            //event delegation here..
            $(document).on("submit", "#delete-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(wid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/warehouses/delete/" + wid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Αποθήκης!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/warehouses/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Η αποθήκη υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής αποθήκης!';
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






        //AJAX for Add/Create New Warehouse submit
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
                url: "{{ url(request()->route()->getPrefix()) }}" + "/warehouses/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Αποθήκης!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/warehouses/view/";
                            }
                        });
                },
                error: function(response){
                    console.log('Error:', response);

                    var msg = 'Κάτι πήγε στραβά..!';

                    if(response.status == 500){
                        msg = 'Η αποθήκη υπάρχει ήδη!';
                    } else if (response.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας αποθήκης!';
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


    </script>
@stop
