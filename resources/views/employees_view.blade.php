{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη | Εργαζόμενοι')

@section('content_header')
    <h1>Αποθήκη/Warehouse | Όλοι οι Εργαζόμενοι</h1>
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
                        <!-- <th class="text-left">Φωτο</th> -->
                        <th class="text-left">Εταιρεία</th>
                        <th class="text-left">Αποθήκη/-ες</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($employees as $employee)
                    <tr class="user-row" data-eid="{{ $employee->id }}">  <!-- necessary additions -->
                        <td>{{ $employee->user->name }}</td>  <!-- get name via User -->

                        <!-- <td>{{ $employee->user->user_type }}</td> --> <!-- get role via User -->
                        @if($employee->user->user_type == 'super_admin')
                            <td>Διαχειριστής</td>
                        @elseif($employee->user->user_type == 'company_ceo')
                            <td>Διευθυντής</td>
                        @elseif($employee->user->user_type == 'warehouse_foreman')
                            <td>Προϊστάμενος Αποθήκης</td>
                        @elseif($employee->user->user_type == 'accountant')
                            <td>Λογιστήριο</td>
                        @elseif($employee->user->user_type == 'warehouse_worker')
                            <td>Αποθηκάριος</td>
                        @elseif($employee->user->user_type == 'technician')
                            <td>Τεχνίτης</td>
                        @elseif($employee->user->user_type == 'normal_user')
                            <td>Απλός Χρήστης</td>
                        @else
                            <td></td>
                        @endif

                        <td>{{ $employee->address }}</td>
                        <td>{{ $employee->phone_number }}</td>
                        <td>{{ $employee->user->email }}</td>
                        <td>{{ $employee->company->name }}</td>

                        <td>
                            <ul> <!-- Many to Many relationship -->
                            @foreach($employee->warehouses as $warehouse)
                                <li>{{ $warehouse->name }}</li>
                            @endforeach
                            </ul>
                        </td>

                        <!-- <td>{{ $employee->user->email }}</td> -->
                        <!-- <td>{{ $employee->user->photo_url }}</td> -->
                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-eid="{{ $employee->id }}"
                                    data-userid="{{ $employee->user_id }}"
                                    data-username="{{ $employee->user }}"
                                    data-address="{{ $employee->address }}"
                                    data-telno="{{ $employee->phone_number }}"
                                    data-companyid="{{ $employee->company_id }}"
                                    data-warehouses="{{ $employee->warehouses }}"
                                    data-warehousesall="{{ $warehouses }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-eid="{{ $employee->id }}"
                                    data-user="{{ $employee->user->name }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New Employee button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal" id="add-employee-btn">Προσθήκη Νέου Εργαζόμενου</button>

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

                        <form id="add-form" class="form-horizontal" method="POST" novalidate>
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('POST')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <!-- This is where the validation errors will show up -->
                            <div class="alert alert-danger" style="display:none" role="alert">
                            </div>


                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Προσθήκη Εργαζόμενου</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden inputs for ID,company,warehouse -->
                                    <input type="hidden" id="modal-input-eid-create" name="modal-input-eid-create" value="">

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-create">Όνομα</label>
                                        <!--
                                        <input type="text" name="modal-input-name-create" class="form-control" id="modal-input-name-create"
                                            value=""  autofocus />
                                        -->
                                        <select name="modal-input-name-create" class="form-control" id="modal-input-name-create">
                                        @foreach($users as $user)
                                            <option value="{{ $user->id }}">{{ $user->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /name -->

                                    <!-- user_type -->
                                    <!--
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-role-create">Ρόλος/Θέση</label>
                                        <select name="modal-input-role-create" class="form-control" id="modal-input-role-create" required>
                                        @php
                                            $usrtype = ['company_ceo','warehouse_foreman','accountant','warehouse_worker'];
                                        @endphp
                                        @foreach($usrtype as $ut)

                                            @if($ut == 'company_ceo')
                                                <option value="company_ceo">Διευθυντής</option>
                                            @elseif($ut == 'accountant')
                                                <option value="accountant">Λογιστής</option>
                                            @elseif($ut == 'warehouse_foreman')
                                                <option value="warehouse_foreman">Προϊστάμενος Αποθήκης</option>
                                            @elseif($ut == 'warehouse_worker')
                                                <option value="warehouse_worker">Εργάτης Αποθήκης</option>
                                            @else
                                                <option value="{{ $ut }}">{{ $ut }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                    -->
                                    <!-- /user_type -->

                                    <!-- address -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-address-create">Διεύθυνση</label>
                                        <input type="text" name="modal-input-address-create" class="form-control" id="modal-input-address-create"
                                           value="" />
                                    </div>
                                    <!-- /address -->

                                    <!-- telno -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-telno-create">Τηλέφωνο</label>
                                        <input type="text" name="modal-input-telno-create" class="form-control" id="modal-input-telno-create"
                                            value="" />
                                    </div>
                                    <!-- /telno -->

                                    <!-- email -->
                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-email-create">E-mail</label>
                                        <input type="text" name="modal-input-email-create" class="form-control" id="modal-input-email-create"
                                            value="" required />
                                    </div>
                                    -->
                                    <!-- /email -->

                                    <!-- photo -->
                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-photo-create">Φωτο</label>
                                        <input type="file" name="modal-input-photo-create" class="form-control" id="modal-input-photo-create"
                                            value="" />
                                    </div>
                                    -->
                                    <!-- /photo -->

                                    <!-- company -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-company-create">Εταιρεία</label>
                                        <select name="modal-input-company-create" class="form-control" id="modal-input-company-create">
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /company -->

                                    <!-- warehouse -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-create">Αποθήκη/-ες</label>
                                        <select name="modal-input-warehouse-create[]" class="form-control" id="modal-input-warehouse-create" multiple="multiple">
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
                                data-target="#add-modal">Πρόσθεσε Εργαζόμενο</button>
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

                            <!-- This is where the validation errors will show up -->
                            <div class="alert alert-danger" style="display:none" role="alert">
                            </div>


                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Μεταβολή Προϊόντος</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-eid-edit" name="modal-input-eid-edit" value="">

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Όνομα</label>
                                        <!--
                                        <input type="text" name="modal-input-name-edit" class="form-control-plaintext" id="modal-input-name-edit"
                                            value="" readonly />
                                        -->

                                        <select name="modal-input-name-edit" class="form-control" id="modal-input-name-edit">
                                        @foreach($employees as $emp)
                                            <option value="{{ $emp->user->id }}">{{ $emp->user->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /name -->

                                    <!-- user_type -->
                                    <!--
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-role-edit">Ρόλος/Θέση</label>
                                        <select name="modal-input-role-edit" class="form-control" id="modal-input-role-edit" required>
                                        @php
                                            $usrtype = ['company_ceo','accountant','warehouse_foreman','warehouse_worker'];
                                        @endphp
                                        @foreach($usrtype as $ut)
                                            @if($ut == 'company_ceo')
                                                <option value="company_ceo">Διευθυντής</option>
                                            @elseif($ut == 'accountant')
                                                <option value="accountant">Λογιστής</option>
                                            @elseif($ut == 'warehouse_foreman')
                                                <option value="warehouse_foreman">Προϊστάμενος Αποθήκης</option>
                                            @elseif($ut == 'warehouse_worker')
                                                <option value="warehouse_worker">Εργάτης Αποθήκης</option>
                                            @else
                                                <option value="{{ $ut }}">{{ $ut }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                    -->
                                    <!-- /user_type -->

                                    <!-- address -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-address-edit">Διεύθυνση</label>
                                        <input type="text" name="modal-input-address-edit" class="form-control" id="modal-input-address-edit"
                                           value="" />
                                    </div>
                                    <!-- /address -->

                                    <!-- telno -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-telno-edit">Τηλέφωνο</label>
                                        <input type="text" name="modal-input-telno-edit" class="form-control" id="modal-input-telno-edit"
                                            value="" />
                                    </div>
                                    <!-- /telno -->

                                    <!-- email -->
                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-email-edit">E-mail</label>
                                        <input type="text" name="modal-input-email-edit" class="form-control" id="modal-input-email-edit"
                                            value="" required />
                                    </div>
                                    -->
                                    <!-- /email -->

                                    <!-- photo -->
                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-photo-edit">Φωτο</label>
                                        <input type="file" name="modal-input-photo-edit" class="form-control" id="modal-input-photo-edit"
                                            value="" />
                                    </div>
                                    -->
                                    <!-- /photo -->

                                    <!-- company -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-company-edit">Εταιρεία</label>
                                        <select name="modal-input-company-edit" class="form-control" id="modal-input-company-edit">
                                        @foreach($companies as $company)
                                            <option value="{{ $company->id }}">{{ $company->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /company -->

                                    <!-- warehouse -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouse-edit">Αποθήκη/-ες</label>
                                        <select name="modal-input-warehouse-edit[]" class="form-control" id="modal-input-warehouse-edit" multiple="mulitple">
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
                                data-target="#edit-modal" data-eid="">Διόρθωσε Εργαζόμενο</button>
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

   <!--  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" /> -->
@stop

@section('js')

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript" defer></script> -->

    <script type="text/javascript">
        //console.log('Hi!');

        $(document).ready(function(){

            //initialise the select2 components.
            $('#modal-input-warehouse-create').select2();
            $('#modal-input-warehouse-edit').select2();


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
                            "text"   : "Αντιγραφή",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Εργαζόμενοι",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Εργαζόμενοι",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Εργαζόμενοι",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6]
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



            //the 3 modals follow::
            $('#edit-modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal

                var eid = button.data('eid'); // Extract info from data-* attributes
                var userid = button.data('userid');
                var username = button.data('username'); //422 unprocessable entity, when I use this value (username.name) iot have a FIXED name..!

                var address = button.data('address');
                var telno = button.data('telno');
                //var email = button.data('email');
                // var warehouseid = button.data('warehouseid');
                var companyid = button.data('companyid');
                //var photo = button.data('photo');
                //var uid = button.data('uid');
                var warehousesall = button.data('warehousesall');
                var warehouses = button.data('warehouses');

                // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
                // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
                var modal = $(this);
                //modal.find('.modal-title').text('New message to ' + recipient);
                //modal.find('.card-body #modal-input-eid-edit').val(eid);
                modal.find('.modal-body #modal-input-name-edit').val(userid); //it will be replaced by the actual name of the employee
                modal.find('.modal-body #modal-input-address-edit').val(address);
                modal.find('.modal-body #modal-input-telno-edit').val(telno);
                //modal.find('.modal-body #modal-input-email-edit').val(email);
                modal.find('.modal-body #modal-input-company-edit').val(companyid);
                // modal.find('.modal-body #modal-input-warehouse-edit').val(warehouseid);
                 //modal.find('.modal-body #modal-input-photo-edit').val(photo);
                //modal.find('.modal-body #modal-input-uid-edit').val(uid);


                modal.find('.modal-footer #edit-button').attr("data-eid", eid);  //SET product id value in data-eid attribute



                // modal.find('.modal-body #modal-input-name-edit').empty();
                // console.log('Users Name: ',username);
                // modal.find('.modal-body #modal-input-name-edit').val(user.id);


                // this code will be replaced, as warehouse_id doesnt exist anymore (neither does warehouse_id)

                //1-to-many relationship, no longer the case
                // $.each(warehousesall, function(key, val){
                //     //console.log(key);
                //     //console.log(val);
                //     if((companyid == val['company_id']) && (warehouseid == val['id'])){
                //         modal.find('.modal-body #modal-input-warehouse-edit').append('<option selected value="'+ val['id'] +'">' + val['name'] + '</option>');
                //     } else if((companyid == val['company_id'])){
                //         modal.find('.modal-body #modal-input-warehouse-edit').append('<option value="'+ val['id'] +'">' + val['name'] + '</option>');
                //     }
                // });

                modal.find('.modal-body #modal-input-warehouse-edit').empty();

                console.log('warehousesall',warehousesall);
                console.log('warehouses',warehouses);


                var warehouses_in_company = [];
                warehouses_in_company = warehousesall.filter(a => a.company_id === companyid);
                // //console.log('warehousesall', warehousesall)
                // console.log('warehouses_in_company',warehouses_in_company);
                // console.log('warehouses',warehouses);

                var wh_in_comp = [];
                wh_in_comp = warehouses.filter(a => a.company_id === companyid);
                // console.log('wh_in_comp', wh_in_comp);

                $.each(wh_in_comp, function(key, val){
                    modal.find('.modal-body #modal-input-warehouse-edit').append('<option selected value="'+ val['id'] +'">' + val['name'] + '</option>');
                });


                var diff = [];
                // diff = warehouses.filter(a => !warehouses_in_company.some(b=> a.company_id === b.id));
                diff = warehouses_in_company.filter(a => !wh_in_comp.some(b => a.id === b.id)); //Correct!
                // console.log('diff',diff);

                $.each(diff, function(key,val){
                    modal.find('.modal-body #modal-input-warehouse-edit').append('<option value="'+ val['id'] +'">' + val['name'] + '</option>');
                });



                //AJAX Update/Edit Warehouse Data
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
                        error: function(xhr){
                            console.log('Error:', xhr);

                            var msg = 'Συνέβη κάποιο λάθος!';

                            if(xhr.status == 500){
                                msg = 'Ο εργαζόμενος υπάρχει ήδη!';
                            } else if (xhr.status == 403){
                                msg = 'Δεν έχετε to δικαίωμα διόρθωσης εργαζόμενου!';
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







            //do not put the following inside $(document).ready()!
            $('#delete-modal').on('show.bs.modal', function(event) {
                var button = $(event.relatedTarget); // Button that triggered the modal

                var eid = button.data('eid'); // Extract info from data-* attributes
                var name = button.data('user');

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
                        error: function(xhr){
                            console.log('Error:', xhr);

                            var msg = 'Συνέβη κάποιο λάθος!';

                            if(xhr.status == 500){
                                msg = 'Ο εργαζόμενος υπάρχει ήδη!';
                            } else if (xhr.status == 403){
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
                    error: function(xhr){
                        console.log('Error:', xhr);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(xhr.status == 500){
                            msg = 'Ο εργαζόμενος υπάρχει ήδη!';
                        } else if (xhr.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα δημιουργίας εργαζόμενου!';
                        } else if (xhr.status == 422){
                            msg = 'Δώσατε λάθος δεδομένα!';

                            var json_err = $.parseJSON(xhr.responseText); //xhr.responseJSON
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



        //AJAX for dropdown lists in add and edit modals

        //ajax add modal
        $(document).on('change', '#modal-input-company-create', function(evt){

            var company_id = evt.target.value;

            if(company_id){
                $.ajax({
                    method: "GET",
                    dataType: "json",
                    url: '{{ url(request()->route()->getPrefix()) }}' + '/employees/company/' + company_id,

                    success: function(data){
                        $('#modal-input-warehouse-create').empty();
                        $.each(data, function(key, value){
                            $('#modal-input-warehouse-create').append('<option value="'+ value +'">'+ key +'</option>');
                            //console.log('key='+key+ ', value='+value);
                        });
                    },

                });
            } else {
                $('#modal-input-warehouse-create').empty();
            }
        });


            //ajax edit modal
        $(document).on('change', '#modal-input-company-edit', function(evt){
            var company_id = evt.target.value;
            //var data = evt.params.data;

            if(company_id){
                $.ajax({
                    method: "GET",
                    dataType: "json",
                    url: '{{ url(request()->route()->getPrefix()) }}' + '/employees/company/' + company_id,

                    success: function(data){
                        console.log('Data : ', data);
                        $('#modal-input-warehouse-edit').empty();
                        $.each(data, function(key, value){
                            $('#modal-input-warehouse-edit').append('<option value="'+ value +'">'+ key +'</option>');
                            //console.log('key='+key+ ', value='+value);
                        });
                    },

                });
            } else {
                $('#modal-input-warehouse-edit').empty();
            }
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

                $('.alert-danger').hide();
                $('.alert-danger').html('');
            });

            $('#add-employee-btn').on('click', function(evt){
                $('#add-form').find('select').val('');
                $('#add-form').find('select[name="modal-input-warehouse-create"]').empty();
            });






    </script>
@stop
