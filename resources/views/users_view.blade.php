{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη | Χρήστες')

@section('content_header')
    <h1>Αποθήκη/Warehouse | Όλοι οι Χρήστες</h1>
@stop


@section('content')
<style>
    .dt-buttons{
        margin-bottom: 10px;
        padding-bottom: 5px;
    }

    img{
        max-width: 15%;
        max-height: 15%;
    }

</style>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        <div class="col-lg-9 col-xs-6">  <!-- was col-lg-3, too narrow table... -->

            <p>Δες Χρήστες Συστήματος</p>

            <br/>

            @canany(['isSuperAdmin', 'isCompanyCEO'])
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">ID</th>
                        <th class="text-left">Όνομα</th>
                        <th class="text-left">E-mail</th>
                        <th class="text-left">Τύπος χρήστη</th>
                        <th class="text-left">Φωτογραφία</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($users as $user)
                        <tr class="user-row" data-uid="{{ $user->id }}">  <!-- necessary additions -->
                            <td>{{ $user->id }}</td>
                            <td>{{ $user->name }}</td>
                            <td>{{ $user->email }}</td>

                            @if($user->user_type == 'super_admin')
								<td>Διαχειριστής</td>
							@elseif($user->user_type == 'company_ceo')
								<td>Διευθυντής</td>
							@elseif($user->user_type == 'accountant')
								<td>Λογιστήριο</td>
							@elseif($user->user_type == 'warehouse_foreman')
								<td>Προϊστάμενος Αποθήκης</td>
							@elseif($user->user_type == 'warehouse_worker')
								<td>Αποθηκάριος</td>
                            @elseif($user->user_type == 'technician')
								<td>Τεχνίτης</td>
							@elseif($user->user_type == 'normal_user')
								<td>Απλός Χρήστης</td>
							@else
								<td>{{ $user->user_type }}</td>
							@endif

                            <!--
                            <td><img src="{{ Storage::url('app/images/profile/' . $user->photo_url) }}" alt=""></td>
                            -->
                            <td>
                                <img src="{{ $user->photo_url }}" alt=""></img>
                                <br>
                                {{ basename($user->photo_url) }}
                            </td>
                            <!-- <td>{{ $user->photo_url }}</td> -->

                            <td>
                                <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-uid="{{ $user->id }}"
                                    data-name="{{ $user->name }}"
                                    data-email="{{ $user->email }}"
                                    data-photo="{{ $user->photo_url }}"
                                    data-usertype="{{ $user->user_type }}">
                                    <!-- <span class="glyphicon glyphicon-edit"></span> -->
                                    <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                                </button>
                            </td>
                            <td>
                            <!-- no need for other checks here, as other types Accountant etc, CANNOT see this screen -->
                            @if(!($user->id == \Auth::user()->id)) <!-- NB.: The User CANNOT delete him/her SELF! -->
                                <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-uid="{{ $user->id }}"
                                    data-name="{{ $user->name }}">
                                    <!-- <span class="glyphicon glyphicon-trash"></span> Διαγραφή -->
                                    <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                                </button>
                            @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <br/><br/>

            <!--
            @foreach($users as $user)
                <img src="{{ $user->photo_url }}" alt=""></img>
            @endforeach
            -->

            <!--Create New User button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Χρήστη</button>

            <br/><br/>
            @endcanany


            @can('isSuperAdmin')
                <a href="{{ route('admin.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isCompanyCEO')
                <a href="{{ route('manager.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @canany(['isSuperAdmin', 'isCompanyCEO'])
            <!-- the Edit Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Χρήστη</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>


                        <form id="edit-form" class="form-horizontal" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf
                        @method('PUT')
                        <!-- necessary fields for CSRF & Method type-->

                        <!-- Modal body -->
                        <div class="modal-body">

                            <!-- this is the div where the error messages will be displayed -->
                            <div class="alert alert-danger" style="display:none">
                            </div>

                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Μεταβολή Χρήστη</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-uid-edit" name="modal-input-uid-edit" value="">
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Όνομα</label>
                                        <input type="text" name="modal-input-name-edit" class="form-control" id="modal-input-name-edit"
                                            value="" autofocus />
                                    </div>
                                    <!-- /name -->

                                    <!-- email -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-email-edit">E-mail</label>
                                        <input type="text" name="modal-input-email-edit" class="form-control" id="modal-input-email-edit"
                                            value="" />
                                    </div>
                                    <!-- /email -->


                                    <!-- password -->
                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-passwd-edit">Συνθηματικό (Password)</label>
                                        <input type="password" name="modal-input-passwd-edit" class="form-control" id="modal-input-passwd-edit"
                                            value="" />
                                    </div>
                                    -->
                                    <!-- /password -->


                                    <!-- usertype -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-usertype-edit">Τύπος χρήστη</label>
                                        <!--
                                        <input type="text" name="modal-input-usertype-edit" class="form-control" id="modal-input-usertype-edit"
                                            value="" required>
                                        -->
                                        <select name="modal-input-usertype-edit" class="form-control" id="modal-input-usertype-edit">
                                        @php
                                            $usrtype = ['super_admin','company_ceo','warehouse_foreman','accountant','warehouse_worker','technician' ,'normal_user'];
                                        @endphp
                                        @foreach($usrtype as $ut)
                                            @if($ut == 'super_admin')
                                                <option value="super_admin">Διαχειριστής</option>
                                            @elseif($ut == 'company_ceo')
                                                <option value="company_ceo">Διευθυντής</option>
                                            @elseif($ut == 'warehouse_foreman')
                                                <option value="warehouse_foreman">Προϊστάμενος Αποθήκης</option>
                                            @elseif($ut == 'accountant')
                                                <option value="accountant">Λογιστήριο</option>
                                            @elseif($ut == 'warehouse_worker')
                                                <option value="warehouse_worker">Αποθηκάριος</option>
                                            @elseif($ut == 'technician')
                                                <option value="technician">Τεχνίτης</option>
                                            @elseif($ut == 'normal_user')
                                                <option value="normal_user">Απλός Χρήστης</option>
                                            @else
                                                <option value="{{ $ut }}">{{ $ut }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /usertype -->

                                    <!-- photo profile -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-photo-edit">Φωτογραφία (προαιρετική)</label>
                                        <input type="file" name="modal-input-photo-edit" class="form-control" id="modal-input-photo-edit"
                                            value="" />
                                    </div>
                                    <!-- /photo profile -->

                                </div>
                            </div>


                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-info" id="edit-button" name="edit-user-button"
                               data-target="#edit-modal" data-uid="">Διόρθωσε Χρήστη</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update form -->

                    </div>
                </div>
            </div>


            <!-- the Delete Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Χρήστη</h4>
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
                                    <h2 class="m-0">Διαγραφή Χρήστη</h2>
                                </div>
                                -->
                                <div class="card-body">
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε τον παρακάτω χρήστη;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-uid-del" name="modal-input-uid-del" value="" />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-del">Όνομα</label>
                                        <input type="text" name="modal-input-name-del" class="form-control-plaintext" id="modal-input-name-del"
                                            value="" readonly />
                                    </div>
                                    <!-- /name -->
                                    <!--
                                        <div class="form-group">
                                            <label class="col-form-label" for="modal-input-email-del">E-mail</label>
                                            <input type="text" name="modal-input-email-del" class="form-control" id="modal-input-email-del" required>
                                        </div>

                                        <div class="form-group">
                                            <label class="col-form-label" for="modal-input-usertype-del">Τύπος χρήστη</label>
                                            <input type="text" name="modal-input-usertyp-del" class="form-control" id="modal-input-usertype-del" required>
                                        </div>
                                        -->


                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-user-button"
                                data-target="#delete-modal"
                                data-toggle="modal" data-uid="">Διέγραψε Χρήστη</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end delete form -->

                    </div>
                </div>
            </div>




            <!-- the Add/Create new User, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Νέου Χρήστη</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="add-form" class="form-horizontal" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('POST')

                        <!-- Modal body -->
                        <div class="modal-body">

                                <!-- this is the div where the error messages will be displayed -->
                                <div class="alert alert-danger" style="display:none">
                                </div>


                                <div class="card text-white bg-white mb-0">
                                    <!--
                                    <div class="card-header">
                                        <h2 class="m-0">Μεταβολή Χρήστη</h2>
                                    </div>
                                    -->
                                    <div class="card-body">

                                         <!-- added hidden input for ID -->
                                         <input type="hidden" id="modal-input-uid-create" name="modal-input-uid-create" value="">

                                        <!-- name -->
                                        <div class="form-group">
                                            <label class="col-form-label" for="modal-input-name-create">Όνομα</label>
                                            <input type="text" name="modal-input-name-create" class="form-control" id="modal-input-name-create"
                                               value="" autofocus />
                                        </div>
                                        <!-- /name -->

                                        <!-- email -->
                                        <div class="form-group">
                                            <label class="col-form-label" for="modal-input-email-create">E-mail</label>
                                            <input type="text" name="modal-input-email-create" class="form-control" id="modal-input-email-create"
                                               value="" />
                                        </div>
                                        <!-- /email -->

                                        <!-- password -->
                                        <div class="form-group">
                                            <label class="col-form-label" for="modal-input-passwd-create">Συνθηματικό (Password)</label>
                                            <input type="password" name="modal-input-passwd-create" class="form-control" id="modal-input-passwd-create"
                                               value="" />
                                        </div>
                                        <!-- /password -->

                                        <!-- usertype --> <!-- ToDo: Change this to a <select> input type, as it is an enum in the DB -->
                                        <div class="form-group">
                                            <label class="col-form-label" for="modal-input-usertype-create">Τύπος χρήστη</label>
                                            <!--
                                            <input type="text" name="modal-input-usertype-create" class="form-control" id="modal-input-usertype-create"
                                               value="" required>
                                            -->
                                            <select name="modal-input-usertype-create" class="form-control" id="modal-input-usertype-create">
                                            @php
                                                $usrtype = ['super_admin','company_ceo','warehouse_foreman','accountant','warehouse_worker', 'technician', 'normal_user'];
                                            @endphp
                                            @foreach($usrtype as $ut)
                                                @if($ut == 'super_admin')
                                                    <option value="super_admin">Διαχειριστής</option>
                                                @elseif($ut == 'company_ceo')
                                                    <option value="company_ceo">Διευθυντής</option>
                                                @elseif($ut == 'warehouse_foreman')
                                                    <option value="warehouse_foreman">Προϊστάμενος Αποθήκης</option>
                                                @elseif($ut == 'accountant')
                                                    <option value="accountant">Λογιστήριο</option>
                                                @elseif($ut == 'warehouse_worker')
                                                    <option value="warehouse_worker">Αποθηκάριος</option>
                                                @elseif($ut == 'technician')
                                                    <option value="technician">Τεχνίτης</option>
                                                @elseif($ut == 'normal_user')
                                                    <option value="normal_user">Απλός Χρήστης</option>
                                                @else
                                                    <option value="{{ $ut }}">{{ $ut }}</option>
                                                @endif
                                            @endforeach
                                            </select>
                                        </div>
                                        <!-- /usertype -->


                                        <!-- photo profile -->
                                        <div class="form-group">
                                            <label class="col-form-label" for="modal-input-photo-create">Φωτογραφία (προαιρετική)</label>
                                            <input type="file" name="modal-input-photo-create" class="form-control" id="modal-input-photo-create"
                                               value="" />
                                        </div>
                                        <!-- /photo profile -->



                                    </div>
                                </div>



                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-user-button"
                                data-target="#add-modal">Πρόσθεσε Χρήστη</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>
            @endcanany <!-- isSuperAdmin, isCompanyCEO -->



        </div>
      </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">

    <!--
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" />
    -->
@stop

@section('js')
    <!--
        <script> console.log('Hi!'); </script>
    -->

    <!--
    <script src="https://cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js" type="text/javascript" defer></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript" defer></script>
    -->

    <script type="text/javascript">

        $(document).ready(function(){

            $('.table').DataTable({
                autoWidth: true,
                ordering: true,
                searching: true,
                select: true,
                /*
                    processing: true,
                    serverSide: true,
                    ajax: {
                        url: {{ route('admin.users.view') }},
                        type: "GET",
                    },
                */

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
                                columns: [1, 2, 3]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Χρήστες Εφαρμογής",
                            exportOptions: {
                                columns: [1, 2, 3]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Χρήστες Εφαρμογής",
                            exportOptions: {
                                columns: [1, 2, 3]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Χρήστες Εφαρμογής",
                            "orientation" : "portrait",
                            exportOptions: {
                                columns: [1, 2, 3]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [1, 2, 3]
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

        });//end document-ready



        //do not put the following code inside $(document).ready()!

        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var uid = button.data('uid'); // Extract info from data-* attributes
            var name = button.data('name');
            var email = button.data('email');
            var usertype = button.data('usertype');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-uid-edit').val(uid);
            modal.find('.modal-body #modal-input-name-edit').val(name);
            modal.find('.modal-body #modal-input-email-edit').val(email);
            modal.find('.modal-body #modal-input-usertype-edit').val(usertype);

            modal.find('.modal-footer #edit-button').attr("data-uid", uid);  //SET user id value in data-uid attribute



            //AJAX Update/Edit User
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(uid);
                console.log(formData);

                //reset the error field(s).
                $('.alert-danger').hide();
                $('.alert-danger').html('');


                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/users/update/" + uid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Χρήστη!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Send Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/users/view/";
                            }
                        });
                    },
                    error: function(xhr){
                        console.log('Error:', xhr);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(xhr.status == 500){
                            msg = 'Ο χρήστης υπάρχει ήδη!';
                        } else if (xhr.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης χρήστη!';
                        } else if (xhr.status == 422){
                            msg = 'Δώσατε λάθος δεδομένα!';

                            var json_err = $.parseJSON(xhr.responseText); //responseJSON
                            //console.log(json_err); //correct json!

                            $('.alert-danger').html('');
                            $('.alert-danger').show();

                            $.each(json_err.errors, function(key, value){
                                $('.alert-danger').show();
                                $('.alert-danger').append('<li>' + value[0] + '</li>');
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

            var uid = button.data('uid'); // Extract info from data-* attributes
            var name = button.data('name');

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            modal.find('.modal-body .card .card-body #modal-input-uid-del').val(uid); //change the value to...
            modal.find('.modal-body .card .card-body #modal-input-name-del').val(name);

            modal.find('.modal-footer #delete-button').attr("data-uid", uid); //SET user id value in data-uid attribute


            //AJAX Delete existing User (..but NOT himself??)
            //event delegation here..
            $(document).on("submit", "#delete-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(uid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/users/delete/" + uid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Χρήστη!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Send Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/users/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(response.status == 500){
                            msg = 'Ο χρήστης υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής χρήστη!';
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






        //AJAX for Add/Create New User submit
        //event delegation here..
        $(document).on("submit", "#add-form", function(evt){
            evt.preventDefault();
            var formData = new FormData(this);

            console.log(formData);

            //reset the error field(s).
            $('.alert-danger').hide();
            $('.alert-danger').html('');

            $.ajax({
                method: "POST",
                data: formData,
                cache: false,
                contentType: false, //do not set any content type header
                processData: false, //send non-processed data
                url: "{{ url(request()->route()->getPrefix()) }}" + "/users/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Χρήστη!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Send Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/users/view/";
                            }
                        });
                },
                error: function(xhr){
                    console.log('Error:', xhr);

                    var msg = 'Κάτι πήγε στραβά..!';

                    if(xhr.status == 500){
                        msg = 'Ο χρήστης υπάρχει ήδη!';
                    } else if (xhr.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας χρήστη!';
                    } else if (xhr.status == 422){
                        msg = 'Δώσατε λάθος δεδομένα!';

                        var json_err = $.parseJSON(xhr.responseText); //responseJSON
                        //console.log(json_err); //correct json!

                        $('.alert-danger').html('');
                        $('.alert-danger').show();

                        $.each(json_err.errors, function(key, value){
                            $('.alert-danger').show();
                            $('.alert-danger').append('<li>' + value[0] + '</li>');
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

            //reset the error field(s).
            $('.alert-danger').hide();
            $('.alert-danger').html('');
        });




    </script>


@stop
