{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Dashboard')

@section('content_header')
    <h1>Warehouse / Όλα τα Προϊόντα</h1>
@stop


@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        <div class="col-lg-12 col-xs-6">

            <p>Όλα τα Προϊόντα</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])
            <!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="5">
                <thead>
                    <tr>
                        <th class="text-left">Κωδικός</th>
                        <th class="text-left">Όνομα</th>
                        <th class="text-left">Είδος</th>  <!-- product type -->
                        <th class="text-left">Κατηγορία</th>
                        <th class="text-left">Περιγραφή</th>
                        <th class="text-left">Ποσότητα</th>
                        <th class="text-left">Μον.Μέτρ.</th>
                        <th class="text-left">Σχόλια</th>
                        <th class="text-left">Κωδικός Ανάθεσης</th> <!-- assignment_code, nullable()? -->
                        <th class="text-left">Τύπος Ανάθεσης</th> <!-- assignment_type -->

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($products as $product)
                    <tr class="user-row" data-pid="{{ $product->id }}">  <!-- necessary additions -->
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->type->name }}</td> <!-- eidos proiontos, product type -->
                        <td>{{ $product->category->name }}</td> <!-- Was: $product->type, but now, via FK, cell gets its contents from category table -->
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->quantity }}</td>
                        <td>{{ $product->measure_unit }}</td>
                        <td>{{ $product->comments }}</td>
                        <td>{{ $product->assignment->assignment_code }}</td> <!-- κωδικος αναθεσης -->
                        <td>{{ $product->assignment->assignment_type }}</td> <!-- τυπος αναθεσης, import OR export -->
                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-pid="{{ $product->id }}"
                                    data-code="{{ $product->code }}"
                                    data-name="{{ $product->name }}"
                                    data-type="{{ $product->type->name }}"
                                    data-category="{{ $product->category->name }}"
                                    data-description="{{ $product->description}}"
                                    data-quantity="{{ $product->quantity }}"
                                    data-measunit="{{ $product->measure_unit }}"
                                    data-comments="{{ $product->comments }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-pid="{{ $product->id }}"
                                    data-code="{{ $product->code }}"
                                    data-name="{{ $product->name }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New User button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Προϊόντος</button>

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

            <!-- the Add/Create new Product, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Νέου Προϊόντος</h4>
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
                                    <input type="hidden" id="modal-input-pid-create" name="modal-input-pid-create" value="">

                                    <!-- code -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-code-create">Κωδικός Προϊόντος</label>
                                        <input type="text" name="modal-input-code-create" class="form-control" id="modal-input-code-create"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /code -->

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-create">Όνομα</label>
                                        <input type="text" name="modal-input-name-create" class="form-control" id="modal-input-name-create"
                                            value="" required>
                                    </div>
                                    <!-- /name -->

                                    <!-- description -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-create">Περιγραφή</label>
                                        <textarea rows="3" name="modal-input-description-create" class="form-control" id="modal-input-description-create"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /description -->

                                    <!-- prod_type, eidos px Ergaleio(Tool), Psygeio ktl -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-type-create">Είδος</label>
                                        <input type="text" name="modal-input-type-create" class="form-control" id="modal-input-type-create"
                                           value="" required />
                                    </div>
                                    <!-- /prod_type -->

                                    <!-- product category -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-category-create">Τύπος</label>
                                        <select name="modal-input-category-create" id="modal-input-category-create" class="form-control" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /product category -->

                                    <!-- quantity -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-quantity-create">Ποσότητα</label>
                                        <input type="text" name="modal-input-quantity-create" class="form-control" id="modal-input-quantity-create"
                                            value="" required>
                                    </div>
                                    <!-- /quantity -->

                                    <!-- measure_unit -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-measureunit-create">Μονάδα Μέτρησης</label>
                                        <select name="modal-input-measureunit-create" id="modal-input-measureunit-create" class="form-control" required>
                                        @php
                                            $meas_unit = ['τμχ', 'm', 'm2', 'm3', 'kg'];
                                        @endphp
                                        @foreach($meas_unit as $mu)
                                            @if($mu == 'τμχ')
                                                <option value="τμχ">τεμάχια</option>
                                            @elseif($mu == 'm')
                                                <option value="m">μέτρα</option>
                                            @elseif($mu == 'm2')
                                                <option value="m2">τετραγωνικά μέτρα</option>
                                            @elseif($mu == 'm3')
                                                <option value="m3">κυβικά μέτρα</option>
                                            @elseif($mu == 'kg')
                                                <option value="kg">κιλά</option>
                                            @else
                                                <option value="{{ $mu }}">{{ $mu }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /measure_unit -->

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
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-product-button"
                                data-target="#add-modal" data-toggle="modal">Πρόσθεσε Προϊόν</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>



            <!-- the Edit/Update existing Product, Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Προϊόντος</h4>
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

                                    <!-- code -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-code-edit">Κωδικός Προϊόντος</label>
                                        <input type="text" name="modal-input-code-edit" class="form-control" id="modal-input-code-edit"
                                            value="" required autofocus>
                                    </div>
                                    <!-- /code -->

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Όνομα</label>
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

                                    <!-- prod_type, eidos px Ergaleio(Tool), Psygeio ktl -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-type-edit">Είδος</label>
                                        <input type="text" name="modal-input-type-edit" class="form-control" id="modal-input-type-edit"
                                           value="" required />
                                    </div>
                                    <!-- /prod_type -->

                                    <!-- product category -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-category-edit">Κατηγορία</label>
                                        <select name="modal-input-category-edit" id="modal-input-category-edit" class="form-control" required>
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /category -->

                                    <!-- quantity -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-quantity-edit">Ποσότητα</label>
                                        <input type="text" name="modal-input-quantity-edit" class="form-control" id="modal-input-quantity-edit"
                                            value="" required>
                                    </div>
                                    <!-- /quantity -->

                                    <!-- measure_unit -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-measureunit-edit">Μονάδα Μέτρησης</label>
                                        <select name="modal-input-measureunit-edit" id="modal-input-measureunit-edit" class="form-control" required>
                                        @php
                                            $meas_unit = ['τμχ', 'm', 'm2', 'm3', 'kg'];
                                        @endphp
                                        @foreach($meas_unit as $mu)
                                            @if($mu == 'τμχ')
                                                <option value="τμχ">τεμάχια</option>
                                            @elseif($mu == 'm')
                                                <option value="m">μέτρα</option>
                                            @elseif($mu == 'm2')
                                                <option value="m2">τετραγωνικά μέτρα</option>
                                            @elseif($mu == 'm3')
                                                <option value="m3">κυβικά μέτρα</option>
                                            @elseif($mu == 'kg')
                                                <option value="kg">κιλά</option>
                                            @else
                                                <option value="{{ $mu }}">{{ $mu }}</option>
                                            @endif
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /measure_unit -->

                                    <!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-edit">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-edit" class="form-control" id="modal-input-comments-edit"
                                            value="" required></textarea>
                                    </div>
                                    <!-- /comments -->

                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input--edit">Κωδικός Ανάθεσης</label>
                                        <input type="text" name="modal-input--edit" class="form-control" id="modal-input--edit"
                                           value="" required />
                                    </div>

                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input--edit">Τύπος Ανάθεσης</label>
                                        <input type="text" name="modal-input--edit" class="form-control" id="modal-input--edit"
                                           value="" required />
                                    </div>
                                    -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-product-button"
                                data-target="#edit-modal" data-toggle="modal" data-pid="">Διόρθωσε Προϊόν</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update/edit form -->

                    </div>
                </div>
            </div>




            <!-- the Delete existing Product, Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Προϊόντος</h4>
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
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε το παρακάτω προϊόν;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-pid-del" name="modal-input-pid-del" value="" />
                                    </div>

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-code-del">Κωδικός Προϊόντος</label>
                                        <input type="text" name="modal-input-code-del" class="form-control-plaintext" id="modal-input-code-del"
                                            value="" readonly required />
                                    </div>
                                    <!-- /name -->

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-del">Όνομα</label>
                                        <input type="text" name="modal-input-name-del" class="form-control-plaintext" id="modal-input-name-del"
                                            value="" readonly required />
                                    </div>
                                    <!-- /name -->
                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-product-button"
                                data-target="#delete-modal" data-toggle="modal" data-pid="">Διέγραψε Προϊόν</button>
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

    <script type="text/javascript">
    //console.log('Hi!');

    $(document).ready(function(){

         //configure & initialise the (Products) DataTable
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

            var pid = button.data('pid'); // Extract info from data-* attributes
            var code = button.data('code');
            var name = button.data('name');
            var type = button.data('type');
            var description = button.data('description');
            var category = button.data('category');
            var quantity = button.data('quantity');
            var unit = button.data('measunit');
            var comments = button.data('comments');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-pid-edit').val(pid);
            modal.find('.modal-body #modal-input-code-edit').val(code);
            modal.find('.modal-body #modal-input-name-edit').val(name);
            modal.find('.modal-body #modal-input-type-edit').val(type);
            modal.find('.modal-body #modal-input-description-edit').val(description);
            modal.find('.modal-body #modal-input-category-edit').val(category);
            modal.find('.modal-body #modal-input-quantity-edit').val(quantity);
            modal.find('.modal-body #modal-input-unit-edit').val(unit);
            modal.find('.modal-body #modal-input-comments-edit').val(comments);

            modal.find('.modal-footer #edit-button').attr("data-pid", pid);  //SET product id value in data-pid attribute


            //AJAX Update/Edit User
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
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/products/update/" + pid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Προϊόντος!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/products/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Το προϊόν υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης προϊόντος!';
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

            var pid = button.data('pid'); // Extract info from data-* attributes
            var code = button.data('code');
            var name = button.data('name');

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            modal.find('.modal-body .card .card-body #modal-input-pid-del').val(pid); //change the value to...
            modal.find('.modal-body .card .card-body #modal-input-code-del').val(code);
            modal.find('.modal-body .card .card-body #modal-input-name-del').val(name);

            modal.find('.modal-footer #delete-button').attr("data-pid", pid); //SET user id value in data-pid attribute


            //AJAX Delete existing Product
            //event delegation here..
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
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/products/delete/" + pid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Προϊόντος!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/products/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Κάτι πήγε στραβά..!';

                        if(response.status == 500){
                            msg = 'Το προϊόν υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής προιόντος!';
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
                url: "{{ url(request()->route()->getPrefix()) }}" + "/products/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Προϊόντος!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/products/view/";
                            }
                        });
                },
                error: function(response){
                    console.log('Error:', response);

                    var msg = 'Κάτι πήγε στραβά..!';

                    if(response.status == 500){
                        msg = 'Το προϊόν υπάρχει ήδη!';
                    } else if (response.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας προϊόντος!';
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


    </script>
@stop
