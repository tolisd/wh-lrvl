{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Πίνακας Ελέγχου')

@section('content_header')
    <h1>Warehouse/Αποθήκη | Όλες οι Μεταφορικές Εταιρείες</h1>
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

            <p>Όλες οι Μεταφορικές Εταιρείες</p>

            <!--
            @if($errors->any())
                <div class="alert alert-danger" style="display:none">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif
            -->


            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Όνομα Μεταφορικής</th>
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
                @foreach($transport_companies as $company)
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Νέας Μεταφορικής Εταιρείας</button>

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
                            <h4 class="modal-title">Προσθήκη Νέας Μεταφορικής Εταιρείας</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="add-form" class="form-horizontal" method="POST">
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('POST')

                        <!-- Modal body -->
                        <div class="modal-body">


                            <div class="alert alert-danger" style="display:none">
                            </div>

                            <!--
                            <div class="alert alert-danger" style="display:none">
                                <ul>
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                            -->

                            <!-- will display ALL errors on top.. -->
                            <!--
                            @if($errors->any())
                                <div class="alert alert-danger" style="display:none">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            -->


                            <!-- will display only the first error message it encounters -->
                            <!--
                            @if(!empty($errors->first()))
                                <div class="alert alert-danger">
                                    <ul>
                                        <li>{{ $errors->first() }}</li>
                                    </ul>
                                </div>
                            @endif
                            -->

                            <!--
                            <div class="alert alert-danger" style="display: {{ count($errors) > 0 ? 'block' : 'none' }}">
                                <ul>
                                @foreach($errors as $error)
                                    <li>{{$error['error']}}</li>
                                @endforeach
                                </ul>
                            </div>
                            -->

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
                                        <input type="text" name="modal-input-name-create" class="form-control" id="modal-input-name-create" value="" autofocus />
                                        <span class="text-danger" id="modal-input-name-create-error" style="display:none"></span>
                                    </div>
                                    <!-- /name -->

                                    <!-- ΑΦΜ -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-afm-create">Α.Φ.Μ.</label>
                                        <input type="text" name="modal-input-afm-create" class="form-control" id="modal-input-afm-create" value="" />
                                        <span class="text-danger" id="modal-input-afm-create-error" style="display:none"></span>
                                    </div>
                                    <!-- /ΑΦΜ -->

                                    <!-- ΔΟΥ -->
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-doy-create">Δ.Ο.Υ.</label>
                                        <input type="text" name="modal-input-doy-create" class="form-control" id="modal-input-doy-create" value="" />
                                        <span class="text-danger" id="modal-input-doy-create-error" style="display:none"></span>
                                    </div>
                                    <!-- /ΔΟΥ -->

                                    <!-- postal_code -->
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-pcode-create">T.K.</label>
                                        <input type="text" name="modal-input-pcode-create" class="form-control" id="modal-input-pcode-create" value="" />
                                        <span class="text-danger" id="modal-input-pcode-create-error" style="display:none"></span>
                                    </div>
                                    <!-- /postal_code -->

                                    <!-- address -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-address-create">Διεύθυνση</label>
                                        <input type="text" name="modal-input-address-create" class="form-control"
                                        id="modal-input-address-create" value="" />
                                        <span class="text-danger" id="modal-input-address-create-error" style="display:none"></span>
                                    </div>
                                    <!-- /address -->

                                    <!-- city -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-city-create">Πόλη</label>
                                        <input type="text" name="modal-input-city-create" class="form-control"
                                        id="modal-input-city-create" value="" />
                                        <span class="text-danger" id="modal-input-city-create-error" style="display:none"></span>
                                    </div>
                                    <!-- /city -->

                                    <!-- telno -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-telno-create">Τηλέφωνο</label>
                                        <input type="text" name="modal-input-telno-create" class="form-control"
                                        id="modal-input-telno-create" value="" />
                                        <span class="text-danger" id="modal-input-telno-create-error" style="display:none"></span>
                                    </div>
                                    <!-- /telno -->

                                    <!-- email -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-email-create">E-mail</label>
                                        <input type="text" name="modal-input-email-create" class="form-control"
                                        id="modal-input-email-create" value="" />
                                        <span class="text-danger" id="modal-input-email-create-error" style="display:none"></span>
                                    </div>
                                    <!-- /email -->

                                    <!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-create">Σχόλια (προαιρετικά)</label>
                                        <input type="text" name="modal-input-comments-create" class="form-control"
                                        id="modal-input-comments-create" value="" />
                                        <span class="text-danger" id="modal-input-comments-create-error" style="display:none"></span>
                                    </div>
                                    <!-- /comments -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-company-button"
                            data-target="#add-modal">Πρόσθεσε Μεταφορική Εταιρεία</button>
                             <!--   data-target="#add-modal" data-toggle="modal">Πρόσθεσε Μεταφορική Εταιρεία</button> -->
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
                            <h4 class="modal-title">Μεταβολή Μεταφορικής Εταιρείας</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="edit-form" class="form-horizontal" method="POST" novalidate>
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('PUT')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <!-- This is where the error fields will be printed out on the screen -->
                            <div class="alert alert-danger" style="display:none">
                            </div>


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
                                            value="" autofocus />
                                        <span class="text-danger" id="modal-input-name-edit-error" style="display:none"></span>
                                    </div>
                                    <!-- /name -->

                                    <!-- ΑΦΜ -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-afm-edit">Α.Φ.Μ.</label>
                                        <input type="text" name="modal-input-afm-edit" class="form-control" id="modal-input-afm-edit"
                                           value="" />
                                        <span class="text-danger" id="modal-input-afm-edit-error" style="display:none"></span>
                                    </div>
                                    <!-- /ΑΦΜ -->

                                    <!-- ΔΟΥ -->
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-doy-edit">Δ.Ο.Υ.</label>
                                        <input type="text" name="modal-input-doy-edit" class="form-control" id="modal-input-doy-edit"
                                           value="" />
                                        <span class="text-danger" id="modal-input-doy-edit-error" style="display:none"></span>
                                    </div>
                                    <!-- /ΔΟΥ -->

                                    <!-- postal_code -->
                                     <div class="form-group">
                                        <label class="col-form-label" for="modal-input-pcode-edit">T.K.</label>
                                        <input type="text" name="modal-input-pcode-edit" class="form-control" id="modal-input-pcode-edit"
                                           value="" />
                                        <span class="text-danger" id="modal-input-pcode-edit-error" style="display:none"></span>
                                    </div>
                                    <!-- /postal_code -->

                                    <!-- address -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-address-edit">Διεύθυνση</label>
                                        <input type="text" name="modal-input-address-edit" class="form-control" id="modal-input-address-edit"
                                           value="" />
                                        <span class="text-danger" id="modal-input-address-edit-error" style="display:none"></span>
                                    </div>
                                    <!-- /address -->

                                    <!-- city -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-city-edit">Πόλη</label>
                                        <input type="text" name="modal-input-city-edit" class="form-control" id="modal-input-city-edit"
                                           value="" />
                                        <span class="text-danger" id="modal-input-city-edit-error" style="display:none"></span>
                                    </div>
                                    <!-- /city -->

                                    <!-- telno -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-telno-edit">Τηλέφωνο</label>
                                        <input type="text" name="modal-input-telno-edit" class="form-control" id="modal-input-telno-edit"
                                            value="" />
                                        <span class="text-danger" id="modal-input-telno-edit-error" style="display:none"></span>
                                    </div>
                                    <!-- /telno -->

                                    <!-- email -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-email-edit">E-mail</label>
                                        <input type="text" name="modal-input-email-edit" class="form-control" id="modal-input-email-edit"
                                            value="" />
                                        <span class="text-danger" id="modal-input-email-edit-error" style="display:none"></span>
                                    </div>
                                    <!-- /email -->

                                    <!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-edit">Σχόλια (προαιρετικά)</label>
                                        <input type="text" name="modal-input-comments-edit" class="form-control" id="modal-input-comments-edit"
                                            value="" />
                                        <span class="text-danger" id="modal-input-comments-edit-error" style="display:none"></span>
                                    </div>
                                    <!-- /comments -->


                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-company-button"
                                data-target="#edit-modal" data-cid="">Διόρθωσε Μεταφορική Εταιρεία</button>
                            <!-- I removed from the above button, data-toggle="modal"  -->
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
                            <h4 class="modal-title">Διαγραφή Μεταφορικής Εταιρείας</h4>
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
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε την παρακάτω Μεταφορική Εταιρεία;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-cid-del" name="modal-input-cid-del" value="" />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-del">Όνομα Μεταφορικής Εταιρείας</label>
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
                                data-target="#delete-modal" data-toggle="modal" data-cid="">Διέγραψε Μεταφορική Εταιρεία</button>
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

    <!-- <link href="https://fonts.googleapis.com/css2?family=Lato:wght@300&display=swap" rel="stylesheet"> -->

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" /> -->
@stop

@section('js')

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript" defer></script> -->


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
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ,8 ]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Μεταφορικές Εταιρείες",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ,8 ]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Μεταφορικές Εταιρείες",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ,8 ]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Μεταφορικές Εταιρείες",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ,8 ]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5, 6, 7 ,8 ]
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
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/shipping-companies/update/" + cid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Μεταφορικής Εταιρείας!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/shipping-companies/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(response.status == 500){
                            msg = 'Η μεταφορική εταιρεία υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης μεταφορικής εταιρείας!';
                        } else if (response.status == 422){
                            msg = 'Δώσατε λάθος δεδομένα!';

                            var json_err = $.parseJSON(response.responseText); //responseJSON
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
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/shipping-companies/delete/" + cid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Μεταφορικής Εταιρείας!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/shipping-companies/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(response.status == 500){
                            msg = 'Η μεταφορική εταιρεία υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής μεταφορικής εταιρείας!';
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
            //var form = $('#add-form');
            //var formData = form.serialize();

            console.log(formData);

            $('.alert-danger').hide();
            $('.alert-danger').html('');

            //hide the individual fields, error messages
            $('#modal-input-name-create-error').hide();
            $('#modal-input-afm-create-error').hide();
            $('#modal-input-doy-create-error').hide();
            $('#modal-input-pcode-create-error').hide();
            $('#modal-input-address-create-error').hide();
            $('#modal-input-city-create-error').hide();
            $('#modal-input-telno-create-error').hide();
            $('#modal-input-email-create-error').hide();
            $('#modal-input-comments-create-error').hide();


            $.ajax({
                method: "POST",
                data: formData,
                cache: false,
                //contentType: "application/json",  //Setting contentType to false is used for forms that pass files, when false,
                                                    //no header will be added to the request, which is exactly what we want when submitting multipart/form-data.
                contentType: false, //do not set any content type header
                processData: false, //Send non-processed data. (If you want to send a DOMDocument, or other non-processed data, set this option to false.)
                                    //Setting processData (to:: false) makes it so your FormData is not converted to a string.
                dataType: "json",
                url: "{{ url(request()->route()->getPrefix()) }}" + "/shipping-companies/create/", //where to send the ajax request

                success: function(response){

                    console.log('Response_Data: '+response);
                    /*
                    if(response.success){
                        alert("ok!");
                    }
                    else if(response.errors){
                        $.each(response.errors, function(index, value) {
                            $("input[name='"+index+"']" ).css('border-color: #a94442;');
                            $("input[name='"+index+"']" ).parent().append(value[0]);
                        });
                    }
                    */
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Μεταφορικής Εταιρείας!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/shipping-companies/view/";
                            }
                    });
                },
                error: function(xhr){

                    console.log('Error: ' + xhr.responseJSON);

                    var msg = 'Συνέβη κάποιο λάθος!';

                    if(xhr.status == 500){
                        msg = 'Η μεταφορική εταιρεία υπάρχει ήδη!';
                    } else if (xhr.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας μεταφορικής εταιρείας!';
                    } else if (xhr.status == 422){
                        msg = 'Δώσατε λάθος δεδομένα!';

                        var json_err = $.parseJSON(xhr.responseText); //responseJSON
                        //console.log(json_err); //correct json!

                        $('.alert-danger').html('');
                        $('.alert-danger').show();
                         //I do not need this on top, as I now have individual error messages below
                        $.each(json_err.errors, function(key, value){
                            $('.alert-danger').show();
                            $('.alert-danger').append('<li>' + value[0] + '</li>');
                        });

                        //$('.alert-danger').hide();
                        //$('#add-modal').modal('hide');

                        //show individual field errors here
                        //first, delete the individual fields
                        $('#modal-input-name-create-error').html('');
                        $('#modal-input-afm-create-error').html('');
                        $('#modal-input-doy-create-error').html('');
                        $('#modal-input-pcode-create-error').html('');
                        $('#modal-input-address-create-error').html('');
                        $('#modal-input-city-create-error').html('');
                        $('#modal-input-telno-create-error').html('');
                        $('#modal-input-email-create-error').html('');
                        $('#modal-input-comments-create-error').html('');

                        /*
                        $.each(json_err.errors, function(key, value){
                            if(key == 'modal-input-name-create'){
                                $('#modal-input-name-create-error').show();
                                $('#modal-input-name-create-error').text(value[0]);
                            }
                            if(key == 'modal-input-afm-create'){
                                $('#modal-input-afm-create-error').show();
                                $('#modal-input-afm-create-error').text(value[0]);
                            }
                            if(key == 'modal-input-doy-create'){
                                $('#modal-input-doy-create-error').show();
                                $('#modal-input-doy-create-error').text(value[0]);
                            }
                            if(key == 'modal-input-pcode-create'){
                                $('#modal-input-pcode-create-error').show();
                                $('#modal-input-pcode-create-error').text(value[0]);
                            }
                            if(key == 'modal-input-address-create'){
                                $('#modal-input-address-create-error').show();
                                $('#modal-input-address-create-error').text(value[0]);
                            }
                            if(key == 'modal-input-city-create'){
                                $('#modal-input-city-create-error').show();
                                $('#modal-input-city-create-error').text(value[0]);
                            }
                            if(key == 'modal-input-telno-create'){
                                $('#modal-input-telno-create-error').show();
                                $('#modal-input-telno-create-error').text(value[0]);
                            }
                            if(key == 'modal-input-email-create'){
                                $('#modal-input-email-create-error').show();
                                $('#modal-input-email-create-error').text(value[0]);
                            }
                            if(key == 'modal-input-comments-create'){
                                $('#modal-input-comments-create-error').show();
                                $('#modal-input-comments-create-error').text(value[0]);
                            }
                        });
                        */

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

            $('.alert-danger').hide();
            $('.alert-danger').html('');

            //also, delete the individual fields|errors
            $('#modal-input-name-create-error').html('');
            $('#modal-input-afm-create-error').html('');
            $('#modal-input-doy-create-error').html('');
            $('#modal-input-pcode-create-error').html('');
            $('#modal-input-address-create-error').html('');
            $('#modal-input-city-create-error').html('');
            $('#modal-input-telno-create-error').html('');
            $('#modal-input-email-create-error').html('');
            $('#modal-input-comments-create-error').html('');
        });



    </script>
@stop
