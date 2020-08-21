{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Πίνακας Ελέγχου')

@section('content_header')
    <h1>Warehouse / Όλοι οι Εργαζόμενοι</h1>
@stop


@section('content')
<style>
    .dt-buttons{
        margin-bottom: 10px;
        padding-bottom: 5px;
    }
</style>

    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token, necessary addition for $.ajax() in jQuery -->

    <div class="row">
        <div class="col-lg-12 col-xs-6">

            <p>Όλοι οι Εργαζόμενοι</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Όνομα Εργαζομένου</th>
                        <th class="text-left">Ρόλος/Θέση</th>
                        <th class="text-left">Διεύθυνση</th>
                        <th class="text-left">Τηλέφωνο</th>
                        <th class="text-left">E-mail</th>
                        <th class="text-left">Αποθήκη</th>
                        <th class="text-left">Εταιρεία</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($employees as $employee)
                    <tr class="user-row" data-eid="{{ $employee->id }}">  <!-- necessary additions -->
                        <td>{{ $employee->user->name }}</td>  <!-- get name via User -->
                        <td>{{ $employee->user->user_type }}</td> <!-- get role via User -->
                        <td>{{ $employee->address }}</td>
                        <td>{{ $employee->phone_number }}</td>
                        <td>{{ $employee->email }}</td>
                        <td>{{ $employee->warehouse->name }}</td>
                        <td>{{ $employee->company->name }}</td>
                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-eid="{{ $employee->id }}"
                                    data-usr="{{ $employee->user_id }}"
                                    data-address="{{ $employee->address }}"
                                    data-telno="{{ $employee->phone_number }}"
                                    data-email="{{ $employee->email }}"
                                    data-warehouse="{{ $employee->warehouse_id }}"
                                    data-company="{{ $employee->company_id }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-eid="{{ $employee->id }}"
                                    data-name="{{ $employee->name }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New Employee button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Νέου Εργαζόμενου</button>

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
            <!-- the Add/Create new Employee, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Νέου Εργαζόμενου</h4>
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
                                    <h2 class="m-0">Προσθήκη Εργαζόμενου</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden inputs for ID,company,warehouse -->
                                    <input type="hidden" id="modal-input-eid-create" name="modal-input-eid-create" value="">
                                    <!-- company_id, where he works -->
                                    <input type="hidden" id="modal-input-cid-create" name="modal-input-cid-create" value="">
                                    <!-- warehouse_id, where he works -->
                                    <input type="hidden" id="modal-input-wid-create" name="modal-input-wid-create" value="">


                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-create">Όνομα</label>
                                        <input type="text" name="modal-input-name-create" class="form-control" id="modal-input-name-create"
                                            value="" required autofocus />
                                    </div>
                                    <!-- /name -->

                                    <!-- user_type -->
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-role-create">Ρόλος/Θέση</label>
                                        <select name="modal-input-role-create" class="form-control" id="modal-input-role-create" required>
                                        @php
                                            $usrtype = ['super_admin','company_ceo','accountant','warehouse_foreman','warehouse_worker', 'normal_user'];
                                        @endphp
                                        @foreach($usrtype as $ut)
                                            @if($ut == 'super_admin')
                                                <option value="super_admin">Διαχειριστής</option>
                                            @elseif($ut == 'company_ceo')
                                                <option value="company_ceo">Διευθυντής</option>
                                            @elseif($ut == 'accountant')
                                                <option value="accountant">Λογιστής</option>
                                            @elseif($ut == 'warehouse_foreman')
                                                <option value="warehouse_foreman">Προϊστάμενος Αποθήκης</option>
                                            @elseif($ut == 'warehouse_worker')
                                                <option value="warehouse_worker">Εργάτης Αποθήκης</option>
                                            @elseif($ut == 'normal_user')
                                                <option value="normal_user">Απλός Χρήστης</option>
                                            @else
                                                <option value="{{ $ut }}">{{ $ut }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /user_type -->

                                    <!-- address -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-address-create">Διεύθυνση</label>
                                        <input type="text" name="modal-input-address-create" class="form-control" id="modal-input-address-create"
                                           value="" required />
                                    </div>
                                    <!-- /address -->

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
                                        <select name="modal-input-company-create" class="form-control" id="modal-input-company-create" required>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /company -->

                                    <!-- warehouse -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-create">Αποθήκη</label>
                                        <select name="modal-input-warehouse-create" class="form-control" id="modal-input-warehouse-create" required>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /warehouse -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-company-button"
                                data-target="#add-modal" data-toggle="modal">Πρόσθεσε Εργαζόμενο</button>
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
                            <h4 class="modal-title">Μεταβολή Εργαζόμενου</h4>
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
                                    <input type="hidden" id="modal-input-eid-edit" name="modal-input-eid-edit" value="">

                                   <!-- company_id, where he works -->
                                   <input type="hidden" id="modal-input-cid-edit" name="modal-input-cid-edit" value="">
                                    <!-- warehouse_id, where he works -->
                                    <input type="hidden" id="modal-input-wid-edit" name="modal-input-wid-edit" value="">


                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Όνομα</label>
                                        <input type="text" name="modal-input-name-edit" class="form-control" id="modal-input-name-edit"
                                            value="" required autofocus />
                                    </div>
                                    <!-- /name -->

                                    <!-- user_type -->
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-role-edit">Ρόλος/Θέση</label>
                                        <select name="modal-input-role-edit" class="form-control" id="modal-input-role-edit" required>
                                        @php
                                            $usrtype = ['super_admin','company_ceo','accountant','warehouse_foreman','warehouse_worker', 'normal_user'];
                                        @endphp
                                        @foreach($usrtype as $ut)
                                            @if($ut == 'super_admin')
                                                <option value="super_admin">Διαχειριστής</option>
                                            @elseif($ut == 'company_ceo')
                                                <option value="company_ceo">Διευθυντής</option>
                                            @elseif($ut == 'accountant')
                                                <option value="accountant">Λογιστής</option>
                                            @elseif($ut == 'warehouse_foreman')
                                                <option value="warehouse_foreman">Προϊστάμενος Αποθήκης</option>
                                            @elseif($ut == 'warehouse_worker')
                                                <option value="warehouse_worker">Εργάτης Αποθήκης</option>
                                            @elseif($ut == 'normal_user')
                                                <option value="normal_user">Απλός Χρήστης</option>
                                            @else
                                                <option value="{{ $ut }}">{{ $ut }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /user_type -->

                                    <!-- address -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-address-edit">Διεύθυνση</label>
                                        <input type="text" name="modal-input-address-edit" class="form-control" id="modal-input-address-edit"
                                           value="" required />
                                    </div>
                                    <!-- /address -->

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
                                        <select name="modal-input-company-edit" class="form-control" id="modal-input-company-edit" required>
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /company -->

                                    <!-- warehouse -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-edit">Αποθήκη</label>
                                        <select name="modal-input-warehouse-edit" class="form-control" id="modal-input-warehouse-edit" required>
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /warehouse -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-company-button"
                                data-target="#edit-modal" data-toggle="modal" data-eid="">Διόρθωσε Εργαζόμενο</button>
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
                            <h4 class="modal-title">Διαγραφή Εργαζόμενου</h4>
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
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε τον παρακάτω Εργαζόμενο;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-eid-del" name="modal-input-eid-del" value="" />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-del">Όνομα Εργαζόμενου</label>
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
                                data-target="#delete-modal" data-toggle="modal" data-eid="">Διέγραψε Εργαζόμενο</button>
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

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />
@stop

@section('js')

    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript" defer></script>

    <script type="text/javascript">
        //console.log('Hi!');

        $(document).ready(function(){

            //configure & initialise the (Warehouses) DataTable
            $('.table').DataTable({
                autoeidth: true,
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
                            "title"  : "Εργαζόμενοι"
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Εργαζόμενοι"
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Εργαζόμενοι"
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

                var eid = button.data('eid'); // Extract info from data-* attributes
                var usr = button.data('usr');
                var address = button.data('address');
                var telno = button.data('telno');
                var email = button.data('email');
                var warehouse = button.data('warehouse');
                var company = button.data('company');
                //var uid = button.data('uid');

                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                var modal = $(this);
                //modal.find('.modal-title').text('New message to ' + recipient);
                //modal.find('.card-body #modal-input-eid-edit').val(eid);
                modal.find('.modal-body #modal-input-name-edit').val(usr);
                modal.find('.modal-body #modal-input-address-edit').val(address);
                modal.find('.modal-body #modal-input-telno-edit').val(telno);
                modal.find('.modal-body #modal-input-email-edit').val(email);
                modal.find('.modal-body #modal-input-warehouse-edit').val(warehouse);
                modal.find('.modal-body #modal-input-company-edit').val(company);
                //modal.find('.modal-body #modal-input-uid-edit').val(uid);

                modal.find('.modal-footer #edit-button').attr("data-eid", eid);  //SET product id value in data-eid attribute


                //AJAX Update/Edit Warehouse Data
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
                        url: "{{ url(request()->route()->getPrefix()) }}" + "/employees/update/" + eid, //where to send the ajax request
                        success: function(){
                            Swal.fire({
                                icon: "success",
                                type: "success",
                                text: "Επιτυχής Διόρθωση Εργαζόμενου!",
                                buttons: [false, "OK"],
                                closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                            }).then(function(isConfirm){
                                if (isConfirm){
                                    console.log("Sent PUT Request ..");
                                    window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/employees/view/";
                                }
                            });
                        },
                        error: function(response){
                            console.log('Error:', response);

                            var msg = 'Κάτι πήγε στραβά..!';

                            if(response.status == 500){
                                msg = 'Ο εργαζόμενος υπάρχει ήδη!';
                            } else if (response.status == 403){
                                msg = 'Δεν έχετε to δικαίωμα διόρθωσης εργαζόμενου!';
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
                var name = button.data('name');

                var modal = $(this);
                //modal.find('.modal-title').text('New message to ' + recipient);
                modal.find('.modal-body .card .card-body #modal-input-eid-del').val(eid); //change the value to...
                modal.find('.modal-body .card .card-body #modal-input-name-del').val(name);

                modal.find('.modal-footer #delete-button').attr("data-eid", eid); //SET user id value in data-eid attribute


                //AJAX Delete existing Warehouse
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
                        url: "{{ url(request()->route()->getPrefix()) }}" + "/employees/delete/" + eid, //where to send the ajax request
                        success: function(){
                            Swal.fire({
                                icon: "success",
                                type: "success",
                                text: "Επιτυχής Διαγραφή Εργαζόμενου!",
                                buttons: [false, "OK"],
                                closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                            }).then(function(isConfirm){
                                if (isConfirm){
                                    console.log("Sent DELETE Request ..");
                                    window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/employees/view/";
                                }
                            });
                        },
                        error: function(response){
                            console.log('Error:', response);

                            var msg = 'Κάτι πήγε στραβά..!';

                            if(response.status == 500){
                                msg = 'Ο εργαζόμενος υπάρχει ήδη!';
                            } else if (response.status == 403){
                                msg = 'Δεν έχετε to δικαίωμα διαγραφής εργαζόμενου!';
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
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/employees/create/", //where to send the ajax request
                    success: function(){
                        Swal.fire({
                                icon: "success",
                                type: "success",
                                text: "Επιτυχής Δημιουργία Εργαζόμενου!",
                                buttons: [false, "OK"],
                                closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                            }).then(function(isConfirm){
                                if (isConfirm){
                                    console.log("Sent POST Request ..");
                                    window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/employees/view/";
                                }
                            });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Ο εργαζόμενος υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα δημιουργίας εργαζόμενου!';
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
