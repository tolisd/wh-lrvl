{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Πίνακας Ελέγχου')

@section('content_header')
    <h1>Warehouse / Όλες οι Εταιρείες</h1>
@stop


@section('content')
<style>
    .dt-buttons{
        margin-bottom: 10px;
        padding-bottom: 5px;
    }

    html, body{
        font-family: 'Lato', sans-serif;
        font-weight: 200;
    }


</style>

<meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token, necessary addition for $.ajax() in jQuery -->

    <div class="row">
        <div class="col-lg-12 col-xs-6">

            <p>Όλες οι Εταιρείες</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Όνομα Εταιρείας</th>
                        <th class="text-left">Α.Φ.Μ.</th>
                        <th class="text-left">Δ.Ο.Υ.</th>
                        <th class="text-left">Ταχ.Κωδ.</th>
                        <th class="text-left">Διεύθυνση</th>
                        <th class="text-left">Πόλη</th>
                        <th class="text-left">Τηλέφωνο</th>
                        <th class="text-left">E-mail</th>
                        <th class="text-left">Σχόλια</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($companies as $company)
                    <tr class="user-row" data-cid="{{ $company->id }}">  <!-- necessary additions -->
                        <td>{{ $company->name }}</td>
                        <td>{{ $company->AFM }}</td>
                        <td>{{ $company->DOY }}</td>
                        <td>{{ $company->postal_code }}</td>
                        <td>{{ $company->address }}</td>
                        <td>{{ $company->city }}</td>
                        <td>{{ $company->phone_number }}</td>
                        <td>{{ $company->email }}</td>
                        <td>{{ $company->comments }}</td>
                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-cid="{{ $company->id }}"
                                    data-name="{{ $company->name }}"
                                    data-afm="{{ $company->AFM }}"
                                    data-doy="{{ $company->DOY }}"
                                    data-pcode="{{ $company->postal_code }}"
                                    data-city="{{ $company->city }}"
                                    data-telno="{{ $company->phone_number }}"
                                    data-email="{{ $company->email }}"
                                    data-address="{{ $company->address }}"
                                    data-comments="{{ $company->comments }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-cid="{{ $company->id }}"
                                    data-name="{{ $company->name }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New Products Type button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Νέας Εταιρείας</button>

            <br/><br/>
            @endcanany <!-- ['isSuperAdmin', 'isCompanyCEO', 'isAccountant'] -->


            @can('isSuperAdmin')
                <a href="{{ route('admin.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isCompanyCEO')
                <a href="{{ route('manager.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isAccountant')
                <a href="{{ route('accountant.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan


            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])
            <!-- the Add/Create new Company, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Νέας Εταιρείας</h4>
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
                                    <input type="hidden" id="modal-input-cid-create" name="modal-input-cid-create" value="">

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-create">Όνομα</label>
                                        <input type="text" name="modal-input-name-create" class="form-control" id="modal-input-name-create"
                                            value="" required autofocus />
                                    </div>
                                    <!-- /name -->

                                    <!-- ΑΦΜ -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-afm-create">Α.Φ.Μ.</label>
                                        <input type="text" name="modal-input-afm-create" class="form-control" id="modal-input-afm-create"
                                           value="" required />
                                    </div>
                                    <!-- /ΑΦΜ -->

                                    <!-- ΔΟΥ -->
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-doy-create">Δ.Ο.Υ.</label>
                                        <input type="text" name="modal-input-doy-create" class="form-control" id="modal-input-doy-create"
                                           value="" required />
                                    </div>
                                    <!-- /ΔΟΥ -->

                                    <!-- postal_code -->
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-pcode-create">T.K.</label>
                                        <input type="text" name="modal-input-pcode-create" class="form-control" id="modal-input-pcode-create"
                                           value="" required />
                                    </div>
                                    <!-- /postal_code -->

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

                                    <!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-create">Σχόλια</label>
                                        <input type="text" name="modal-input-comments-create" class="form-control" id="modal-input-comments-create"
                                            value="" required />
                                    </div>
                                    <!-- /comments -->



                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-company-button"
                                data-target="#add-modal" data-toggle="modal">Πρόσθεσε Εταιρεία</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>



            <!-- the Edit/Update existing Company, Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Εταιρείας</h4>
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
                                    <input type="hidden" id="modal-input-cid-edit" name="modal-input-cid-edit" value="">

                                   <!-- name -->
                                   <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Όνομα</label>
                                        <input type="text" name="modal-input-name-edit" class="form-control" id="modal-input-name-edit"
                                            value="" required autofocus />
                                    </div>
                                    <!-- /name -->

                                    <!-- ΑΦΜ -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-afm-edit">Α.Φ.Μ.</label>
                                        <input type="text" name="modal-input-afm-edit" class="form-control" id="modal-input-afm-edit"
                                           value="" required />
                                    </div>
                                    <!-- /ΑΦΜ -->

                                    <!-- ΔΟΥ -->
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-doy-edit">Δ.Ο.Υ.</label>
                                        <input type="text" name="modal-input-doy-edit" class="form-control" id="modal-input-doy-edit"
                                           value="" required />
                                    </div>
                                    <!-- /ΔΟΥ -->

                                    <!-- postal_code -->
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-pcode-edit">T.K.</label>
                                        <input type="text" name="modal-input-pcode-edit" class="form-control" id="modal-input-pcode-edit"
                                           value="" required />
                                    </div>
                                    <!-- /postal_code -->

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

                                    <!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-edit">Σχόλια</label>
                                        <input type="text" name="modal-input-comments-edit" class="form-control" id="modal-input-comments-edit"
                                            value="" required />
                                    </div>
                                    <!-- /comments -->


                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-company-button"
                                data-target="#edit-modal" data-toggle="modal" data-cid="">Διόρθωσε Εταιρεία</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update/edit form -->

                    </div>
                </div>
            </div>




            <!-- the Delete existing Company, Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Εταιρείας</h4>
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
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε την παρακάτω Εταιρεία;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-cid-del" name="modal-input-cid-del" value="" />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-del">Όνομα Εταιρείας</label>
                                        <input type="text" name="modal-input-name-del" class="form-control-plaintext" id="modal-input-name-del"
                                            value="" readonly required />
                                    </div>
                                    <!-- /name -->
                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-company-button"
                                data-target="#delete-modal" data-toggle="modal" data-cid="">Διέγραψε Εταιρεία</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end delete form -->

                    </div>
                </div>
            </div>

            @endcanany <!-- ['isSuperAdmin', 'isCompanyCEO', 'isAccountant'] -->

        </div>
      </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">

    <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap" rel="stylesheet">

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />
@stop

@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript" defer></script>



    <script type="text/javascript">
    //console.log('Hi!');

        $(document).ready(function(){

            //configure & initialise the (Companies) DataTable
            $('.table').DataTable({
                autowidth: true,
                ordering: true,
                searching: true,
                select: true,
                dom: "Bfrtlip ",
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
                                columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Εταιρείες",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Εταιρείες",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Εταιρείες",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6 ]
                            }
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

            var cid = button.data('cid'); // Extract info from data-* attributes
            var name = button.data('name');
            var afm = button.data('afm');
            var doy = button.data('doy');
            var pcode = button.data('pcode');
            var city = button.data('city');
            var telno = button.data('telno');
            var email = button.data('email');
            var address = button.data('address');
            var comments = button.data('comments');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-cid-edit').val(cid);
            modal.find('.modal-body #modal-input-name-edit').val(name);
            modal.find('.modal-body #modal-input-afm-edit').val(afm);
            modal.find('.modal-body #modal-input-doy-edit').val(doy);
            modal.find('.modal-body #modal-input-pcode-edit').val(pcode);
            modal.find('.modal-body #modal-input-city-edit').val(city);
            modal.find('.modal-body #modal-input-telno-edit').val(telno);
            modal.find('.modal-body #modal-input-email-edit').val(email);
            modal.find('.modal-body #modal-input-address-edit').val(address);
            modal.find('.modal-body #modal-input-comments-edit').val(comments);

            modal.find('.modal-footer #edit-button').attr("data-cid", cid);  //SET company id value in data-cid attribute


            //AJAX Update/Edit Company Data
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(cid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/companies/update/" + cid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Εταιρείας!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/companies/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Η εταιρεία υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης εταιρείας!';
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

            modal.find('.modal-footer #delete-button').attr("data-cid", cid); //SET user id value in data-cid attribute


            //AJAX Delete existing Company
            //event delegation here..
            $(document).on("submit", "#delete-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(cid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/companies/delete/" + cid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Εταιρείας!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/companies/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Η εταιρεία υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής εταιρείας!';
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






        //AJAX for Add/Create New Company submit
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
                url: "{{ url(request()->route()->getPrefix()) }}" + "/companies/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Εταιρείας!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/companies/view/";
                            }
                        });
                },
                error: function(response){
                    console.log('Error:', response);

                    var msg = 'Κάτι πήγε στραβά..!';

                    if(response.status == 500){
                        msg = 'Η εταιρεία υπάρχει ήδη!';
                    } else if (response.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας εταιρείας!';
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
