{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Πίνακας Ελέγχου')

@section('content_header')
    <h1>Αποθήκη / Όλα τα Εργαλεία</h1>
@stop


@section('content')
<style>
    .dt-buttons{
        margin-bottom: 10px;
        padding-bottom: 5px;
    }
</style>

    <meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token, necessary additoin for $.ajax() in jQuery -->

    <div class="row">
        <div class="col-lg-12 col-xs-6">

            <p>Διαχείριση Εργαλείων</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])
            <!-- insert here the main tools table-->

                <table class="table data-table display table-hover table-bordered"
                        data-order='[[ 0, "asc" ]]' data-page-length="10">
                    <thead>
                        <tr>
                            <th class="text-left">Κωδικός</th>
                            <th class="text-left">Όνομα</th>
                            <th class="text-left">Περιγραφή</th>
                            <th class="text-left">Σχόλια</th>
                            <th class="text-left">Ποσότητα (τμχ)</th>
                            <th class="text-left">Χρεωμένο?</th> <!-- boolean, is_charged [0,1] -->
                            <th class="text-left">Όνομα Χρεωμένου</th> <!-- user_id, FK add to products -->

                            <th class="text-left">Χρέωση</th>   <!-- button1 -->
                            <th class="text-left">Ξεχρέωση</th> <!-- button2 -->
                            <th class="text-left">Μεταβολή</th> <!-- button3 -->
                            <th class="text-left">Διαγραφή</th> <!-- button4 -->
                        </tr>
                    </thead>

                    <tbody>
                    @foreach($tools as $tool)
                        <tr class="user-row" data-pid="{{ $tool->id }}">
                            <td>{{ $tool->code }}</td>
                            <td>{{ $tool->name }}</td>
                            <td>{{ $tool->description }}</td>
                            <td>{{ $tool->comments }}</td>
                            <td>{{ $tool->quantity }}</td> <!--σε τεμάχια ALWAYS! 'τμχ.' -->
                            <td>{{ $tool->is_charged }}</td> <!-- boolean -->
                            <td>{{ $tool->user->name }}</td> <!-- by its user_id, FK add to products -->
                            <td>
                                @if($tool->is_charged == 0)
                                <button class="charge-modal btn btn-secondary"
                                    data-toggle="modal" data-target="#charge-modal"
                                    data-pid="{{ $tool->id }}"
                                    data-code="{{ $tool->code }}"
                                    data-name="{{ $tool->name }}"
                                    data-description="{{ $tool->description }}"
                                    data-comments="{{ $tool->comments }}"
                                    data-quantity="{{ $tool->quantity }}"
                                    data-ischarged="{{ $tool->is_charged }}"
                                    data-towhom="{{ $tool->user->name }}">
                                    <i class="fas fa-wrench" aria-hidden="true"></i>&nbsp;Χρέωση
                                </button>
                                @endif
                            </td>
                            <td>
                                @if($tool->is_charged == 1)
                                <button class="uncharge-modal btn btn-secondary"
                                    data-toggle="modal" data-target="#uncharge-modal"
                                    data-pid="{{ $tool->id }}"
                                    data-code="{{ $tool->code }}"
                                    data-name="{{ $tool->name }}"
                                    data-description="{{ $tool->description }}"
                                    data-comments="{{ $tool->comments }}"
                                    data-quantity="{{ $tool->quantity }}"
                                    data-ischarged="{{ $tool->is_charged }}"
                                    data-towhom="{{ $tool->user->name }}">
                                    <i class="fal fa-wrench" aria-hidden="true"></i>&nbsp;Ξεχρέωση
                                </button>
                                @endif
                            </td>
                            <td>
                                <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-pid="{{ $tool->id }}"
                                    data-code="{{ $tool->code }}"
                                    data-name="{{ $tool->name }}"
                                    data-description="{{ $tool->description }}"
                                    data-comments="{{ $tool->comments }}"
                                    data-quantity="{{ $tool->quantity }}"
                                    data-ischarged="{{ $tool->is_charged }}"
                                    data-towhom="{{ $tool->user->name }}">
                                    <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                                </button>
                            </td>
                            <td>
                                <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-pid="{{ $tool->id }}"
                                    data-code="{{ $tool->code }}"
                                    data-name="{{ $tool->name }}">
                                    <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                                </button>
                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>


            <br/><br/>
            <!--Create New Tool button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Νέου Εργαλείου</button>

            <br/><br/>
            @endcanany <!-- isSuperAdmin, isCompanyCEO, isWarehouseForeman -->






            @can('isSuperAdmin')
                <a href="{{ route('admin.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isCompanyCEO')
                <a href="{{ route('manager.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isWarehouseForeman')
                <a href="{{ route('foreman.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan



            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])
            <!-- The 2 Modals, Charging Tools / Uncharging Tools -->
            <!-- They are Both UPDATE//EDIT modals -->

             <!-- the Charge (uncharged)Tool, Modal popup window -->
             <div class="modal fade" id="charge-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Χρέωση Εργαλείου</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="charge-form" class="form-horizontal" method="POST">
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
                                    <input type="hidden" id="modal-input-pid-charge" name="modal-input-pid-charge" value="">

                                    <!-- tool-code, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-charge">Κωδικός Εργαλείου</label>
                                        <input type="text" name="modal-input-name-charge" class="form-control-plaintext" id="modal-input-name-charge"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /tool-code -->

                                    <!-- name, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-charge">Όνομα Εργαλείου</label>
                                        <input type="text" name="modal-input-name-charge" class="form-control-plaintext" id="modal-input-name-charge"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /name -->

                                    <!-- description, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-charge">Περιγραφή</label>
                                        <textarea rows="3" name="modal-input-description-charge" class="form-control-plaintext" id="modal-input-description-charge"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /description -->

                                    <!-- comments, editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-charge">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-charge" class="form-control" id="modal-input-comments-charge"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /comments -->


                                    <!-- quantity
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-quantity-charge">Ποσότητα</label>
                                        <input type="text" name="modal-input-quantity-charge" class="form-control" id="modal-input-quantity-charge"
                                            value="" required>
                                    </div>
                                    /quantity -->

                                    <!-- To Whom User/Employee the Tool is to be Charged-->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-towhom-charge">Όνομα Εργαζόμενου</label>
                                        <select name="modal-input-towhom-charge" id="modal-input-towhom-charge" class="form-control">
                                        <!-- ALL The Users of the Program! -->
                                            @foreach($users as $user)
                                                <option value="{{ $user->id }}">{{ $user->name }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <!-- /To Whom -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="charge-button" name="charge-tool-button"
                                data-target="#charge-modal" data-toggle="modal" data-pid="">Χρέωσε Εργαλείο</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end charge-tool form -->

                    </div>
                </div>
            </div>


             <!-- the Uncharge/Debit (charged)Tool, Modal popup window -->
             <div class="modal fade" id="uncharge-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Ξεχρέωση Εργαλείου</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="uncharge-form" class="form-horizontal" method="POST">
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
                                    <input type="hidden" id="modal-input-pid-uncharge" name="modal-input-pid-uncharge" value="">

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-uncharge">Όνομα Εργαλείου</label>
                                        <input type="text" name="modal-input-name-uncharge" class="form-control" id="modal-input-name-uncharge"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /name -->

                                    <!-- description -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-uncharge">Περιγραφή</label>
                                        <textarea rows="3" name="modal-input-description-uncharge" class="form-control" id="modal-input-description-uncharge"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /description -->

                                    <!-- prod_type, eidos px Ergaleio(Tool), Psygeio ktl -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-type-uncharge">Είδος</label>
                                        <input type="text" name="modal-input-type-uncharge" class="form-control" id="modal-input-type-uncharge"
                                           value="" required />
                                    </div>
                                    <!-- /prod_type -->

                                    <!-- quantity -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-quantity-uncharge">Ποσότητα</label>
                                        <input type="text" name="modal-input-quantity-uncharge" class="form-control" id="modal-input-quantity-uncharge"
                                            value="" required>
                                    </div>
                                    <!-- /quantity -->

                                    <!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-uncharge">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-uncharge" class="form-control" id="modal-input-comments-uncharge"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /comments -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="uncharge-button" name="uncharge-tool-button"
                                data-target="#uncharge-modal" data-toggle="modal" data-pid="">Ξεχρέωσε Εργαλείο</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end uncharge-tool form -->

                    </div>
                </div>
            </div>


            <!-- add new tool to DB form-->
            <div class="modal fade" id="create-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Δημιουργία Νέου Εργαλείου</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="create-form" class="form-horizontal" method="POST">
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('POST')

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
                                    <input type="hidden" id="modal-input-pid-create" name="modal-input-pid-create" value="">

                                    <!-- tool-code, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-create">Κωδικός Εργαλείου</label>
                                        <input type="text" name="modal-input-name-create" class="form-control-plaintext" id="modal-input-name-create"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /tool-code -->

                                    <!-- name, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-create">Όνομα Εργαλείου</label>
                                        <input type="text" name="modal-input-name-create" class="form-control-plaintext" id="modal-input-name-create"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /name -->

                                    <!-- description, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-create">Περιγραφή</label>
                                        <textarea rows="3" name="modal-input-description-create" class="form-control-plaintext" id="modal-input-description-create"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /description -->

                                    <!-- comments, editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-create">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-create" class="form-control" id="modal-input-comments-create"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /comments -->

                                    <!-- quantity -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-quantity-create">Ποσότητα</label>
                                        <input type="text" name="modal-input-quantity-create" class="form-control" id="modal-input-quantity-create"
                                            value="" required>
                                    </div>
                                    <!-- /quantity -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="create-button" name="create-tool-button"
                                data-target="#create-modal" data-toggle="modal" data-pid="">Δημιουργία Εργαλείου</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end create-tool form -->

                    </div>
                </div>
            </div>


            <!-- edit/update existing tool in DB form-->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διόρθωση Εργαλείου</h4>
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
                                    <input type="hidden" id="modal-input-pid-edit" name="modal-input-pid-edit" value="">

                                    <!-- tool-code, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Κωδικός Εργαλείου</label>
                                        <input type="text" name="modal-input-name-edit" class="form-control-plaintext" id="modal-input-name-edit"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /tool-code -->

                                    <!-- name, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Όνομα Εργαλείου</label>
                                        <input type="text" name="modal-input-name-edit" class="form-control-plaintext" id="modal-input-name-edit"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /name -->

                                    <!-- description, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-edit">Περιγραφή</label>
                                        <textarea rows="3" name="modal-input-description-edit" class="form-control-plaintext" id="modal-input-description-edit"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /description -->

                                    <!-- comments, editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-edit">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-edit" class="form-control" id="modal-input-comments-edit"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /comments -->

                                    <!-- quantity -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-quantity-edit">Ποσότητα</label>
                                        <input type="text" name="modal-input-quantity-edit" class="form-control" id="modal-input-quantity-edit"
                                            value="" required>
                                    </div>
                                    <!-- /quantity -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-tool-button"
                                data-target="#edit-modal" data-toggle="modal" data-pid="">Διόρθωσε Εργαλείο</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end edit-tool form -->

                    </div>
                </div>
            </div>



            <!-- delete existing tool from DB form-->
            <div class="modal fade" id="delete-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Εργαλείου</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="delete-form" class="form-horizontal" method="POST">
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('DELETE')

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
                                    <input type="hidden" id="modal-input-pid-delete" name="modal-input-pid-delete" value="">

                                    <!-- tool-code, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-delete">Κωδικός Εργαλείου</label>
                                        <input type="text" name="modal-input-name-delete" class="form-control-plaintext" id="modal-input-name-delete"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /tool-code -->

                                    <!-- name, non-editable -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-delete">Όνομα Εργαλείου</label>
                                        <input type="text" name="modal-input-name-delete" class="form-control-plaintext" id="modal-input-name-delete"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /name -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="delete-button" name="delete-tool-button"
                                data-target="#delete-modal" data-toggle="modal" data-pid="">Διέγραψε Εργαλείο</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end delete-tool form -->

                    </div>
                </div>
            </div>

            @endcanany  <!-- isSuperAdmin, isCompanyCEO, isWarehouseForeman -->

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

            //configure & initialise the (Products) DataTable
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
                            "title"  : "Εργαλεία"
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Εργαλεία"
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Εργαλεία"
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση"
                        },
                    ],
            });

            //for all 5 modals/actions, POST, PUT, DELETE
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

        });



        //The Charge Tools Modal, a kind of UPDATE request.
        $('#charge-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var pid = button.data('pid'); // Extract info from data-* attributes
            var code = button.data('code');
            var name = button.data('name');
            var description = button.data('description');
            var comments = button.data('comments');
            var quantity = button.data('quantity');
            var towhom = button.data('towhom');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.
            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-pid-edit').val(pid);
            modal.find('.modal-body #modal-input-code-charge').val(code);
            modal.find('.modal-body #modal-input-name-charge').val(name);
            modal.find('.modal-body #modal-input-description-charge').val(description);
            modal.find('.modal-body #modal-input-comments-charge').val(comments);
            modal.find('.modal-body #modal-input-quantity-charge').val(quantity);
            modal.find('.modal-body #modal-input-towhom-charge').val(towhom);

            modal.find('.modal-footer #charge-button').attr("data-pid", pid);  //SET product id value in data-pid attribute


            //AJAX Charge Tool to User
            //event delegation here...
            $(document).on("submit", "#charge-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(pid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/tools/charge-tool/" + pid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Χρέωση Εργαλείου!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/tools/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Η χρέωση υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα χρέωσης εργαλείου!';
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

////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //The Uncharge/Debit Tools Modal
        $('#uncharge-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var pid = button.data('pid'); // Extract info from data-* attributes
            var code = button.data('code');
            var name = button.data('name');
            var description = button.data('description');
            var comments = button.data('comments');
            var quantity = button.data('quantity');
            var towhom = button.data('towhom');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-pid-edit').val(pid);
            modal.find('.modal-body #modal-input-code-uncharge').val(code);
            modal.find('.modal-body #modal-input-name-uncharge').val(name);
            modal.find('.modal-body #modal-input-description-uncharge').val(description);
            modal.find('.modal-body #modal-input-comments-uncharge').val(comments);
            modal.find('.modal-body #modal-input-quantity-uncharge').val(quantity);
            modal.find('.modal-body #modal-input-towhom-uncharge').val(towhom);

            modal.find('.modal-footer #uncharge-button').attr("data-pid", pid);  //SET product id value in data-pid attribute



            //AJAX Uncharge/Debit Tool from User
            //event delegation here...
            $(document).on("submit", "#uncharge-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(pid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/tools/uncharge-tool/" + pid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Ξεχρέωση Εργαλείου!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/tools/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Το εργαλείο δεν είναι χρεωμένο!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα ξεχρέωσης εργαλείου!';
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

//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
            //AJAX Create New Tool from User
            //event delegation here...
            $(document).on("submit", "#create-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(pid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/tools/create/", //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Εργαλείου!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/tools/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Το εργαλείο υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα δημιουργίας εργαλείου!';
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


//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
        //The Update/Edit Existing Tool Modal
        $('#uncharge-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var pid = button.data('pid'); // Extract info from data-* attributes
            var code = button.data('code');
            var name = button.data('name');
            var description = button.data('description');
            var comments = button.data('comments');
            var quantity = button.data('quantity');
            var towhom = button.data('towhom');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-pid-edit').val(pid);
            modal.find('.modal-body #modal-input-code-edit').val(code);
            modal.find('.modal-body #modal-input-name-edit').val(name);
            modal.find('.modal-body #modal-input-description-edit').val(description);
            modal.find('.modal-body #modal-input-comments-edit').val(comments);
            modal.find('.modal-body #modal-input-quantity-edit').val(quantity);
            modal.find('.modal-body #modal-input-towhom-edit').val(towhom);

            modal.find('.modal-footer #edit-button').attr("data-pid", pid);  //SET product id value in data-pid attribute



            //AJAX Update/Edit Existing Tool
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(pid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/tools/update/" + pid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής ΔιόρθωσηΕργαλείου!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/tools/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Το εργαλείο δεν υπάρχει!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης εργαλείου!';
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////

        //The Delete Existing Tool Modal
        $('#delete-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var pid = button.data('pid'); // Extract info from data-* attributes
            var code = button.data('code');
            var name = button.data('name');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-pid-edit').val(pid);
            modal.find('.modal-body #modal-input-code-delete').val(code);
            modal.find('.modal-body #modal-input-name-delete').val(name);

            modal.find('.modal-footer #delete-button').attr("data-pid", pid);  //SET product id value in data-pid attribute



            //AJAX Delete Existing Tool
            //event delegation here...
            $(document).on("submit", "#delete-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(pid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/tools/delete/" + pid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Εργαλείου!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/tools/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Το εργαλείο δεν υπάρχει!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής εργαλείου!';
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
//////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////



        //necessary additions for when the modals get hidden

        $('#charge-modal').on('hidden.bs.modal', function(e){
            $(document).off('submit', '#charge-form');
        });

        $('#uncharge-modal').on('hidden.bs.modal', function(e){
            $(document).off('submit', '#uncharge-form');
        });

        $('#edit-modal').on('hidden.bs.modal', function(e){
            $(document).off('submit', '#edit-form');
        });

        $('#delete-modal').on('hidden.bs.modal', function(e){
            $(document).off('submit', '#delete-form');
        });

        $('#add-modal').on('hidden.bs.modal', function(e){
            $(document).off('submit', '#add-form');
        });




    </script>
@stop
