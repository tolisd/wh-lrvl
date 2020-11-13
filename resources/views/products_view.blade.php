{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη | Όλα τα Προϊόντα')

@section('content_header')
    <div id="products-heading">
        <h1><strong>Αποθήκη/Warehouse</strong> | Όλα τα Προϊόντα</h1>
    </div>
    <div class="parallax"></div>
@stop


@section('content')
<style>
    .dt-buttons{
        margin-bottom: 10px;
        padding-bottom: 5px;
    }

    #products-heading{
        margin-bottom: 10px;
        padding-bottom: 5px;
    }

    #add-whqt-button-create{
        padding: 5px;
        margin: 5px;
    }

    .parallax {
        /* The image used */
        background-image: url("/images/pexels-tiger-lily-4483610-gamma.jpg");

        /* Set a specific height */
        min-height: 350px;

        /* Create the parallax scrolling effect */
        background-attachment: fixed;
        background-position: center;
        background-repeat: no-repeat;
        background-size: cover;
    }

</style>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        <div class="col-lg-12 col-xs-6">

            <p>Όλα τα Προϊόντα</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])
            <!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Αναγνωριστικό</th>
                        <th class="text-left">Όνομα</th>
                        <th class="text-left">Περιγραφή</th>
                        <th class="text-left">Κατηγορία</th>
                        <th class="text-left">Είδος</th>  <!-- product type -->
                        <!--  <th class="text-left">Ποσότητα</th> -->
                        <th class="text-left">Μονάδα</th>
                        <th class="text-left">Σχόλια</th>
                        <th class="text-left">Αποθήκη/-ες &amp; Ποσότητα</th>
                        <!--
                        <th class="text-left">Κωδικός Ανάθεσης</th>
                        --> <!-- assignment_code, nullable()? -->
                        <!--
                        <th class="text-left">Τύπος Ανάθεσης</th>
                        --> <!-- assignment_type -->
                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($products as $product)
                    <tr class="user-row" data-pid="{{ $product->id }}">  <!-- necessary additions -->
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                        <td>{{ $product->category->name }}</td> <!-- Was: $product->type, but now, via FK, cell gets its contents from category table -->
                        <td>{{ $product->type->name }}</td> <!-- eidos proiontos, product type -->
                        <!-- <td>{{ $product->quantity }}</td> -->
                        <td>{{ $product->measureunit->name }}</td> <!-- measureunit() in App\Product.php -->
                        <td>{{ $product->comments }}</td>
                        <td>
                            <ul>
                            @foreach($product->warehouses as $warehouse)
                                <li>{{ $warehouse->name }}&nbsp;({{ $warehouse->pivot->quantity }}&nbsp;{{ $product->measureunit->name }})</li>
                            @endforeach
                            </ul>
                        </td>

                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-pid="{{ $product->id }}"
                                    data-code="{{ $product->code }}"
                                    data-name="{{ $product->name }}"
                                    data-description="{{ $product->description }}"
                                    data-categoryid="{{ $product->category_id }}"
                                    data-typeid="{{ $product->type_id }}"
                                    data-typesall="{{ $types_all }}"
                                    data-measunitid="{{ $product->measunit_id }}"
                                    data-comments="{{ $product->comments }}"
                                    data-warehouses="{{ $product->warehouses }}"
                                    data-allwarehouses="{{ $warehouses }}"
                                    data-allquantities="{{ $quantities }}">
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal" id="add-product-btn">Προσθήκη Νέου Προϊόντος</button>

            <br/><br/>
            @endcanany <!-- isSuperAdmin, isCompanyCEO, isWarehouseForeman, isWarehouseWorker -->



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

            <!-- the Add/Create new Product, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Νέου Προϊόντος</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="add-form" class="form-horizontal" method="POST" novalidate>
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('POST')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <!-- this is where the errors will be displayed -->
                            <div class="alert alert-danger" style="display:none">
                            </div>

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
                                        <label class="col-form-label" for="modal-input-code-create">Αναγνωριστικό</label>
                                        <input type="text" name="modal-input-code-create" class="form-control" id="modal-input-code-create"
                                            value="" autofocus />
                                    </div>
                                    <!-- /code -->

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-create">Όνομα Προϊόντος</label>
                                        <input type="text" name="modal-input-name-create" class="form-control" id="modal-input-name-create"
                                            value="" />
                                    </div>
                                    <!-- /name -->

                                    <!-- description -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-create">Περιγραφή</label>
                                        <textarea rows="3" name="modal-input-description-create" class="form-control" id="modal-input-description-create"
                                            value=""></textarea>
                                    </div>
                                    <!-- /description -->

                                    <!-- product category -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-category-create">Κατηγορία</label>
                                        <select name="modal-input-category-create" id="modal-input-category-create" class="form-control">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /product category -->

                                    <!-- prod_type, eidos px Ergaleio(Tool), Psygeio ktl -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-type-create">Είδος</label>
                                        <select name="modal-input-type-create" id="modal-input-type-create" class="form-control">
                                        <!-- this select will receive its values via ajax below -->
                                        <!--
                                        @foreach($types as $type)
                                            <option value="{{ $type->id }}">{{ $type->name }}</option>
                                        @endforeach
                                        -->
                                        </select>
                                    </div>
                                    <!-- /prod_type -->


                                    <!-- quantity -->
                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-quantity-create">Ποσότητα</label>
                                        <input type="text" name="modal-input-quantity-create" class="form-control" id="modal-input-quantity-create"
                                            value="" />
                                    </div>
                                    -->
                                    <!-- /quantity -->

                                    <!-- measure_unit -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-measureunit-create">Μονάδα Μέτρησης</label>
                                        <select name="modal-input-measureunit-create" id="modal-input-measureunit-create" class="form-control">
                                        @foreach($measunits as $mu)
                                                <option value="{{ $mu->id }}">{{ $mu->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /measure_unit -->

                                    <!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-create">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-create" class="form-control" id="modal-input-comments-create"
                                            value=""></textarea>
                                    </div>
                                    <!-- /comments -->

                                    <!-- warehouses, to which warehouses this product belongs to -->
                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouses-create">Αποθήκη/-ες</label>
                                        <select name="modal-input-warehouses-create[]" id="modal-input-warehouses-create" class="form-control" multiple="multiple">
                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    -->
                                    <!-- /warehouses -->



                                    <!-- warehouse and corresponding quantity for this product -->

                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label text-right" for="modal-input-warehouse-create">Αποθήκη &amp; Ποσότητα</label>
                                    </div>

                                    <div class="form-group">

                                        @foreach($warehouses as $warehouse)
                                        <div class="form-row">

                                            <div class="col">
                                                <input type="text" name="modal-input-wh-create" class="form-control-plaintext" id="modal-input-wh-create"
                                                    value="{{ $warehouse->name }}" readonly/>
                                            </div>

                                            <div class="col">
                                                <input type="text" name="modal-input-qty-create" class="form-control" id="modal-input-qty-create"
                                                    value="" placeholder="Ποσότητα"/>
                                            </div>


                                        </div>
                                        @endforeach

                                        <div class="form-group col-lg-12">
                                            <button type="button" class="btn btn-info col-lg-12" id="add-whqt-button-create">[+] Προσθήκη Αποθήκης &amp; Ποσότητας</button>
                                        </div>
                                    </div>
                                    -->

                                    <div class="form-group">
                                        <div class="form-row">
                                            <div class="col">

                                                <div id="whqty-create">

                                                    <div class="form-group col-lg-12">
                                                        <label class="col-form-label text-right" for="modal-input-warehouse-create">Αποθήκη</label>
                                                        <select name="modal-input-warehouse-create[]" id="modal-input-warehouse-create" class="form-control selc-crt">
                                                        @foreach($warehouses as $warehouse)
                                                            <option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option>
                                                        @endforeach
                                                        </select>
                                                    </div>

                                                    <div class="form-group col-lg-12">
                                                        <label class="col-form-label text-right" for="modal-input-quantity-create">Ποσότητα</label>
                                                        <input type="text" name="modal-input-quantity-create[]" class="form-control qty" id="modal-input-quantity-create"
                                                            value="" />
                                                    </div>

                                                    <div class="form-group col-lg-12">
                                                        <button type="button" class="btn btn-info col-lg-12" id="add-whqt-button-create">
                                                            <strong>[+]</strong>&nbsp;Πρόσθεσε Αποθήκη</button>
                                                    </div>

                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <!-- /warehouse and quantity -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-product-button"
                                data-target="#add-modal">Πρόσθεσε Προϊόν</button>
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

                        <form id="edit-form" class="form-horizontal" method="POST" novalidate>
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('PUT')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <!-- this is where the errors will be displayed -->
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
                                    <input type="hidden" id="modal-input-pid-edit" name="modal-input-pid-edit" value="">

                                    <!-- code -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-code-edit">Αναγνωριστικό</label>
                                        <input type="text" name="modal-input-code-edit" class="form-control" id="modal-input-code-edit"
                                            value="" autofocus />
                                    </div>
                                    <!-- /code -->

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-edit">Όνομα Προϊόντος</label>
                                        <input type="text" name="modal-input-name-edit" class="form-control" id="modal-input-name-edit"
                                            value="" />
                                    </div>
                                    <!-- /name -->

                                    <!-- description -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-description-edit">Περιγραφή</label>
                                        <textarea rows="3" name="modal-input-description-edit" class="form-control" id="modal-input-description-edit"
                                            value=""></textarea>
                                    </div>
                                    <!-- /description -->

                                    <!-- product category -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-category-edit">Κατηγορία</label>
                                        <select name="modal-input-category-edit" id="modal-input-category-edit" class="form-control">
                                        @foreach($categories as $cat)
                                            <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /category -->

                                    <!-- prod_type, eidos px Ergaleio(Tool), Psygeio ktl., dependent on the above category, 1-to-N rel/ship -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-type-edit">Είδος</label>
                                        <select name="modal-input-type-edit" id="modal-input-type-edit" class="form-control">

                                            @foreach($types as $type)
                                                <option value="{{ $type->id }}">{{ $type->name }}</option>
                                            @endforeach

                                        </select>
                                    </div>
                                    <!-- /prod_type -->

                                    <!-- quantity -->
                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-quantity-edit">Ποσότητα</label>
                                        <input type="text" name="modal-input-quantity-edit" class="form-control" id="modal-input-quantity-edit"
                                            value="" />
                                    </div>
                                    -->
                                    <!-- /quantity -->

                                    <!-- measure_unit -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-measureunit-edit">Μονάδα Μέτρησης</label>
                                        <select name="modal-input-measureunit-edit" id="modal-input-measureunit-edit" class="form-control">
                                        @foreach($measunits as $mu)
                                                <option value="{{ $mu->id }}">{{ $mu->name }}</option>
                                        @endforeach
                                        </select>
                                    </div>
                                    <!-- /measure_unit -->

                                    <!-- comments -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-comments-edit">Σχόλια</label>
                                        <textarea rows="3" name="modal-input-comments-edit" class="form-control" id="modal-input-comments-edit"
                                            value=""></textarea>
                                    </div>
                                    <!-- /comments -->

                                    <!-- warehouses, to which warehouses this product belongs to -->
                                    <!--
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-warehouses-edit">Αποθήκη/-ες</label>
                                        <select name="modal-input-warehouses-edit[]" id="modal-input-warehouses-edit" class="form-control" multiple="multiple">

                                        @foreach($warehouses as $warehouse)
                                            <option value="{{ $warehouse->id }}" selected>{{ $warehouse->name }}</option>
                                        @endforeach

                                        </select>

                                    </div>-->
                                    <!-- /warehouses -->

                                    <!-- warehouse and corresponding quantity for this product -->

                                    <div class="form-group">

                                        <div class="form-row">

                                            <div class="col">

                                                <div class="whqt-edit-wrapper">
                                                <div id="whqty-edit">
                                                <!--
                                                    <div class="form-group col-lg-12">
                                                        <label class="col-form-label text-right" for="modal-input-warehouse-edit">Αποθήκη</label>
                                                        <select name="modal-input-warehouse-edit[]" id="modal-input-warehouse-edit" class="form-control">



                                                        </select>
                                                    </div>

                                                    <div class="form-group col-lg-12">
                                                        <label class="col-form-label text-right" for="modal-input-quantity-edit">Ποσότητα</label>
                                                        <input type="text" name="modal-input-quantity-edit[]" class="form-control" id="modal-input-quantity-edit"
                                                            value="" />
                                                    </div>

                                                    <div class="form-group col-lg-12">
                                                        <button type="button" class="btn btn-danger col-lg-12" id="del-whqt-button-edit">
                                                            <strong>[&ndash;]</strong>&nbsp;Διέγραψε Αποθήκη</button>
                                                    </div>
                                                -->

                                                </div>
                                                </div>


                                                <!--
                                                <div class="form-group col-lg-12">
                                                    <button type="button" class="btn btn-info col-lg-12" id="add-whqt-button-edit">[+] Πρόσθεσε Αποθήκη &amp; Ποσότητας</button>
                                                </div>
                                                -->

                                            </div>

                                        </div>

                                    </div>


                                    <!-- /warehouse and quantity -->


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
                            <button type="submit" class="btn btn-info" id="edit-button" name="edit-product-button"
                                data-target="#edit-modal" data-pid="">Διόρθωσε Προϊόν</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update/edit form -->

                    </div>
                </div>
            </div>




            <!-- the Delete existing Product, Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal" role="dialog">
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
                                        <label class="col-form-label" for="modal-input-code-del">Αναγνωριστικό Προϊόντος</label>
                                        <input type="text" name="modal-input-code-del" class="form-control-plaintext" id="modal-input-code-del"
                                            value="" readonly />
                                    </div>
                                    <!-- /name -->

                                    <!-- name -->
                                    <div class="form-group">
                                        <label class="col-form-label" for="modal-input-name-del">Όνομα</label>
                                        <input type="text" name="modal-input-name-del" class="form-control-plaintext" id="modal-input-name-del"
                                            value="" readonly />
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

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" /> -->
@stop

@section('js')

    <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.js" type="text/javascript" defer></script> -->

    <script type="text/javascript">
    //console.log('Hi!');

    $(document).ready(function(){

        //initialise the select2 components.
        // $('#modal-input-warehouse-create').select2();
        // $('#modal-input-warehouse-edit').select2();



        //configure & initialise the (Products) DataTable
        $('.table').DataTable({
            autoWidth: true,
            ordering: true,
            searching: true,
            select: true,

            // language: {
            //     "url": "resources/views/greek/greek.json", //404 isn't loading at all
            // },

            // language: {
            //     "sDecimal":           ",",
            //     "sEmptyTable":        "Δεν υπάρχουν δεδομένα στον πίνακα",
            //     "sInfo":              "Εμφανίζονται _START_ έως _END_ από _TOTAL_ εγγραφές",
            //     "sInfoEmpty":         "Εμφανίζονται 0 έως 0 από 0 εγγραφές",
            //     "sInfoFiltered":      "(φιλτραρισμένες από _MAX_ συνολικά εγγραφές)",
            //     "sInfoThousands":     ".",
            //     "sLengthMenu":        "Δείξε _MENU_ εγγραφές",
            //     "sLoadingRecords":    "Φόρτωση...",
            //     "sProcessing":        "Επεξεργασία...",
            //     "sSearch":            "Αναζήτηση:",
            //     "sSearchPlaceholder": "Αναζήτηση",
            //     "sThousands":         ".",
            //     "sZeroRecords":       "Δεν βρέθηκαν εγγραφές που να ταιριάζουν",
            //     "oPaginate": {
            //         "sFirst":    "Πρώτη",
            //         "sPrevious": "Προηγούμενη",
            //         "sNext":     "Επόμενη",
            //         "sLast":     "Τελευταία"
            //     },
            //     "oAria": {
            //         "sSortAscending":  ": ενεργοποιήστε για αύξουσα ταξινόμηση της στήλης",
            //         "sSortDescending": ": ενεργοποιήστε για φθίνουσα ταξινόμηση της στήλης"
            //     }
            // },

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
                                columns: [0,1,2,3,4,5,6,7]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Προϊόντα",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6,7]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Προϊόντα",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6,7]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Προϊόντα",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6,7]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [0,1,2,3,4,5,6,7]
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



        var count = {{ $wh_count }}; //the number of all the warehouses
        var x = 1;

        var html1 = '<div class="whqty"><div class="form-group col-lg-12"><label class="col-form-label text-right" for="modal-input-warehouse-create">Αποθήκη</label>'+
            '<select name="modal-input-warehouse-create[]" id="modal-input-warehouse-create" class="form-control selc-crt"> @foreach($warehouses as $warehouse)'+
            '<option value="{{ $warehouse->id }}">{{ $warehouse->name }}</option> @endforeach </select><div>';

        var html2 = '<div class="form-group col-lg-12"><label class="col-form-label text-right" for="modal-input-quantity-create">Ποσότητα</label>'+
            '<input type="text" name="modal-input-quantity-create[]" class="form-control qty" id="modal-input-quantity-create" value="" /></div></div>'+
            '<button type="button" class="btn btn-danger col-lg-12 minus-btn"><strong>[&ndash;]</strong>&nbsp;Αφαίρεσε Αποθήκη</button>';


        $('#add-whqt-button-create').on('click', function(e){
            e.preventDefault();
            //console.log(count);

            if(x < count){
                ++x;
                $('#whqty-create').append(html1 + html2);
                $('#add-form').find('select.selc-crt').val('');
                // $(this).remove();
                return false;
            }
            // var len = $('#whqty').length;
            else {
                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: 'Προσοχή!',
                    text: 'Δεν μπορείτε να προσθέσετε περισσότερες Αποθήκες!',
                });
                //x = 1;
            }

            //console.log($(this).siblings());
            // console.log($('#whqty').contents());
            // //check for duplicate warehouses!
            // $.each('.whqty', function(key,value){
            //     console.log(value);
            // });
        });



        $(document).on('click', '.minus-btn', function(e){
            e.preventDefault();
            // console.log(x);
            // console.log($(this));
            $(this).siblings().remove();
            $(this).remove();

            x--; //reset x. it works!
            // z--;
        });



        //Check for Duplicates!
        $(document).on('change', 'select.selc-crt', function(){
        //$("select.selc").change(function(){
            // check input ($(this).val()) for validity here
            console.log($(this).val());

            var valueOfChangedInput = $(this).val();
            var timeRepeated = 0;

            $("select.selc-crt").each(function(){
                //Inside each() check the 'valueOfChangedInput' with all other existing input
                if ($(this).val() == valueOfChangedInput) {
                    timeRepeated++; //this will be executed at least 1 time because of the input, which is changed just now
                }
                // if(timeRepeated > 1){
                //     //$('#add-form').find('select.selc').val('');
                // }
            });

            if(timeRepeated > 1) {
                //alert("Δηλώσατε ίδιες Αποθήκες!");
                $('#add-form').find('select.selc-crt,input.qty').val('');

                Swal.fire({
                    icon: "error",
                    type: "error",
                    title: 'Προσοχή!',
                    text: 'Οι Αποθήκες πρέπει να είναι διαφορετικές μεταξύ τους! Παρακαλώ επανεπιλέξτε Αποθήκες!',
                });
            }
            // else {
            //     alert("No Duplicates!");
            // }
        });


            //Check for Duplicates in update modal!
            //It will not allow you to enter an already "selected" warehouse, in any permutation/combination!
            $(document).on('change', 'select.selc1', function(){
                // check input ($(this).val()) for validity here
                // console.log('selc1_change',$(this));
                // console.log($(this).val());

                var valueOfChangedInput = $(this).val();
                // var valueOfChangedInput1 = $('select.selc').val();
                var timeRepeated = 0;
                var tR = 0;

                //"old" select VS. "new" select
                $("select.selc").each(function(){
                    // console.log('selc_each: ', $(this));
                    // console.log('this(selc1).val: ', $(this).val());
                    // console.log('valueOFChangedInput_selc', valueOfChangedInput);
                    // console.log($(this).val() == valueOfChangedInput);

                    //Inside each() check the 'valueOfChangedInput' with all other existing input
                    if ($(this).val() == valueOfChangedInput) {
                        timeRepeated++; //this will be executed at least 1 time because of the input, which is changed just now
                    }
                });

                //"new" select VS. "new" select
                $('select.selc1').each(function(){ //this == select.selc1
                    // console.log('selc1_each: ', $(this));
                    // console.log($(this).val());
                    // console.log('valueOFChangedInput_selc1', valueOfChangedInput1);
                    // console.log($(this).val() == valueOfChangedInput1);

                    //Inside each() check the 'valueOfChangedInput' with all other existing input
                    if ($(this).val() == valueOfChangedInput) {
                        tR++; //this will be executed at least 1 time because of the input, which is changed just now
                    }
                });

                if((timeRepeated >= 1) || (tR > 1)){ //changed from timeRepeated > 1 TO timeRepeated >= 1, and it worked!
                    $('#edit-form').find('select.selc1').val('');

                    Swal.fire({
                        icon: "error",
                        type: "error",
                        title: 'Προσοχή!',
                        text: 'Οι Αποθήκες πρέπει να είναι διαφορετικές μεταξύ τους! Παρακαλώ επανεπιλέξτε Αποθήκες!',
                    });
                }

                //console.log('timeRepeated_after: ', timeRepeated);
                // console.log('tR_after: ', tR);
            });







    });



    //the 3 modals follow::
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var pid = button.data('pid'); // Extract info from data-* attributes
            var code = button.data('code');
            var name = button.data('name');
            var description = button.data('description');

            var categoryid = button.data('categoryid');
            var typeid = button.data('typeid');
            var typesall = button.data('typesall');

            // var quantity = button.data('quantity');
            var measunitid = button.data('measunitid');
            var comments = button.data('comments');
            var warehouses = button.data('warehouses');
            var allwarehouses = button.data('allwarehouses'); //I need this variable so that i calculate the difference later on
            var allquantities = button.data('allquantities');

            // console.log('Warehouses: ', warehouses); //returns the warehouses which were previously added in create-form
            // console.log('All_Warehouses: ', allwarehouses);
            // console.log('warehousesCount', warehouses.length); //correct, returns the number of warehouses which were previously added in create-form

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-pid-edit').val(pid);
            modal.find('.modal-body #modal-input-code-edit').val(code);
            modal.find('.modal-body #modal-input-name-edit').val(name);
            modal.find('.modal-body #modal-input-description-edit').val(description);
            modal.find('.modal-body #modal-input-category-edit').val(categoryid);
            //modal.find('.modal-body #modal-input-type-edit').val(typeid);
            // modal.find('.modal-body #modal-input-quantity-edit').val(quantity);
            modal.find('.modal-body #modal-input-measureunit-edit').val(measunitid);
            modal.find('.modal-body #modal-input-comments-edit').val(comments);


            //console.log('type->id: ', typeid);
            //console.log('all_Types: ', typesall);
            //console.log('categoryid', categoryid);

            //types, AS subcategories, one-to-many
            modal.find('.modal-body #modal-input-type-edit').empty();

            $.each(typesall, function(key, val){
                //console.log(key);
                //console.log(val);
                //console.log(typesall['category_id']); //undefined
                if((categoryid == val['category_id']) && (typeid == val['id'])){
                    modal.find('.modal-body #modal-input-type-edit').append('<option selected value="'+ val['id'] +'">' + val['name'] + '</option>');
                } else if((categoryid == val['category_id'])){
                    modal.find('.modal-body #modal-input-type-edit').append('<option value="'+ val['id'] +'">' + val['name'] + '</option>');
                }
            });



            //foreach of the warehouses i want to display on screen the whole block, [warehouse + quantity]!
            //first, remove the old divs, because with each Update click there will be added again!
            // console.log($('.whqty-edit').length); //0
            // console.log($('#whqt-edit-wrapper').children().size());
            // if($('#whqty-edit').length > warehouses.length){
            //     $('#whqty-edit').remove();
            // }

            // console.log('Warehouses: ', warehouses);

            $.each(warehouses, function (key, val){

                // console.log('warehouses_value', val);

                var html1_edit = '<div class="whqty-edit"><div class="form-group col-lg-12"><label class="col-form-label text-right" for="modal-input-warehouse-edit">Αποθήκη</label>'+
                    '<select name="modal-input-warehouse-edit[]" id="modal-input-warehouse-edit" class="form-control selc">'+
                    '<option value="'+val.id+'">'+val.name+'</option></select><div>';

                var html2_edit = '<div class="form-group col-lg-12"><label class="col-form-label text-right" for="modal-input-quantity-edit">Ποσότητα</label>'+
                    '<input type="text" name="modal-input-quantity-edit[]" class="form-control qty" id="modal-input-quantity-edit" value="" /></div></div>'+
                    '<button type="button" class="btn btn-danger col-lg-12 minus-btn-edit"><strong>[&ndash;]</strong>&nbsp;Αφαίρεσε Αποθήκη</button>';


                //console.log($('#whqty-edit').length);
                // if($('#whqty-edit').length > warehouses.length){
                //     $('#whqty-edit').remove();
                // }

                $('#whqty-edit').append(html1_edit + html2_edit);

                // console.log($('#whqt-edit-wrapper > div.whqty-edit').length);

            });


            // console.log('after: ', $('.whqty-edit').length); //correct value
            //Add the "add new div" button
            $('#add-whqt-button-edit').remove(); //remove it, or else the buttons are adding up...

            var html3_edit = '<div class="form-group col-lg-12">'+
                             '<button type="button" class="btn btn-info col-lg-12" id="add-whqt-button-edit">'+
                             '<strong>[+]</strong>&nbsp;Πρόσθεσε Νέα Αποθήκη</button></div>';

            $('#whqty-edit').append(html3_edit); //Add the create new div Bkutton



            //Add the new div
            // [AllWarehouses - (Allocated)Warehouses = Difference], use this difference for the select boxes in add-whqt-button-edit

            var whcount = {{ $wh_count }}; //the number of all the warehouses
            var rest_wh_count = whcount - $('.whqty-edit').length;
            var z = 1;

            var html1_edit = '<div class="whqty-edit"><div class="form-group col-lg-12"><label class="col-form-label text-right" for="modal-input-warehouse-edit">Νέα Αποθήκη</label>'+
                    '<select name="modal-input-warehouse-edit[]" id="modal-input-warehouse-edit" class="form-control selc1">@foreach($warehouses as $wh)'+
                    '<option value="{{ $wh->id}}">{{ $wh->name }}</option>@endforeach</select><div>';

            var html2_edit = '<div class="form-group col-lg-12"><label class="col-form-label text-right" for="modal-input-quantity-edit">Νέα Ποσότητα</label>'+
                    '<input type="text" name="modal-input-quantity-edit[]" class="form-control qty" id="modal-input-quantity-edit" value="" /></div></div>'+
                    '<button type="button" class="btn btn-danger col-lg-12 minus-btn-edit"><strong>[&ndash;]</strong>&nbsp;Αφαίρεσε Αποθήκη</button>';



            //add a new div
            $('#add-whqt-button-edit').on('click', function(e){
                e.preventDefault();

                if(z <= rest_wh_count){

                    z++;

                    $('#whqty-edit').append(html1_edit + html2_edit);
                    $('#edit-form').find('select.selc1').val('');
                    //return false;

                } else {

                    Swal.fire({
                        icon: "error",
                        type: "error",
                        title: 'Προσοχή!',
                        text: 'Δεν μπορείτε να προσθέσετε περισσότερες Αποθήκες!',
                    });

                }

                // console.log('zPlus: ', z);
                // console.log('plus: ',$('.whqty-edit').length);
                // console.log('restWHcount_PLUS: ', rest_wh_count);
            });

            //remove am existing div
            $(document).on('click', '.minus-btn-edit', function(e){
                e.preventDefault();

                if(z >= rest_wh_count){
                //if($('.whqty-edit').length > 1){
                    $(this).siblings().remove();
                    $(this).remove();

                    z--; //reset the z count. it works!
                }
                // else {

                //     Swal.fire({
                //         icon: "error",
                //         type: "error",
                //         title: 'Προσοχή!',
                //         text: 'Πρέπει να δηλώσετε τουλάχιστον 1 Αποθήκη!',
                //     });

                // }

                // console.log('zMinus: ', z);
                // console.log('minus: ', $('.whqty-edit').length);
                // console.log('restWHcount_MINUS: ', rest_wh_count);
            });


            //also, populate the quantity fields!
            // console.log('all_Quantities: ', allquantities);
            // console.log('allocated_wareh: ', warehouses);

            //first store the quantities in an array..and afterwards only allocate them to the corresponding quantity fields
            var qt_arr = [];

            $.each(allquantities, function(key, val){
                $.each(warehouses, function(k,v){
                    if((val.product_id == v.pivot.product_id) && (val.warehouse_id == v.id)){
                        qt_arr[k] = val.quantity; //not push, not unshift either here!! it's mixing the results!!
                    }
                });
            });

            //populate the fields! done! notice, its byClass not byId, "input.qty"
            $('.modal-body .whqty-edit input.qty').each(function(i,obj){
                $(this).val(qt_arr[i]);
            });




            //warehouses, many-to-many with products
            // modal.find('.modal-body #modal-input-warehouse-edit').empty();

            // $.each(warehouses, function(key, val){
            //     //console.log('key: ', key);
            //     //console.log('val: ', val);
            //     //modal.find('.modal-body #modal-input-warehouses-edit').append('<option value="'+ val['id'] +'">' + val['name'] + '</option>');
            //     //modal.find('.modal-body #modal-input-warehouses-edit option[value="'+ val.id +'"]').attr('selected', true);
            //     modal.find('.modal-body #modal-input-warehouse-edit').append('<option selected value="'+ val['id'] +'">' + val['name'] + '</option>');
            //  });

            //the below for loop is OK at first, but I need UNIQUE names for the case when there are selected more than 2 warehouses in the above loop.
            /*
            for(const wh of warehouses){
                for(const warehouse of allwarehouses){
                    if(warehouse['id'] != wh['id']){
                        modal.find('.modal-body #modal-input-warehouses-edit').append('<option value="'+ warehouse['id'] +'">' + warehouse['name'] + '</option>');
                    }
                }
            }
            */

            // var rest_wh = [];
            // /*
            // rest_wh = allwarehouses.filter(function(n){
            //     for(let i = 0; i < warehouses.length; i++){
            //         if(n.id != warehouses[i].id){
            //           rest_wh.push(n.id);
            //         }
            //     }
            // });
            // */

            // //list1.filter(a => list2.some(b => a.userId === b.userId));
            // rest_wh = allwarehouses.filter(a => !warehouses.some(b => a.id === b.id)); //it worked!! finds the diff of the 2 arrays!
            // //rest_wh = allwarehouses['id'].filter((x) => !warehouses['id'].include(x));
            // console.log('rest_warehouses', rest_wh);

            // $.each(rest_wh, function(key,val){
            //     modal.find('.modal-body #modal-input-warehouses-edit').append('<option value="'+ val['id'] +'">' + val['name'] + '</option>');
            // });


            modal.find('.modal-footer #edit-button').attr("data-pid", pid);  //SET product id value in data-pid attribute


            //AJAX Update/Edit User
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(pid);
                // console.log(formData);

                //also, reset the error field(s).
                $('.alert-danger').hide();
                $('.alert-danger').html('');

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
                    error: function(xhr){
                        console.log('Error:', xhr);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(xhr.status == 500){
                            msg = 'Το προϊόν υπάρχει ήδη!';
                        } else if (xhr.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης προϊόντος!';
                        }  else if (xhr.status == 422){
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
                // console.log(formData);

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

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(response.status == 500){
                            msg = 'Το προϊόν υπάρχει ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής προϊόντος!';
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

            // console.log(formData);

            //also, reset the error field(s).
            $('.alert-danger').hide();
            $('.alert-danger').html('');

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
                error: function(xhr){
                    console.log('Error:', xhr);

                    var msg = 'Συνέβη κάποιο λάθος!';

                    if(xhr.status == 500){
                        msg = 'Το προϊόν υπάρχει ήδη!';
                    } else if (xhr.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας προϊόντος!';
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


        //ajax for dropdown lists in add and edit modals

        //ajax add modal
        $(document).on('change', '#modal-input-category-create', function(evt){

            var categ_id = evt.target.value;

            if(categ_id){
                $.ajax({
                    method: "GET",
                    dataType: "json",
                    url: '{{ url(request()->route()->getPrefix()) }}' + '/products/type/' + categ_id,

                    success: function(data){
                        $('#modal-input-type-create').empty();
                        $.each(data, function(key, value){
                            $('#modal-input-type-create').append('<option value="'+ value +'">'+ key +'</option>');
                            //console.log('key='+key+ ', value='+value);
                        });
                    },

                });
            } else {
                $('#modal-input-type-create').empty();
            }
        });


         //ajax edit modal
         $(document).on('change', '#modal-input-category-edit', function(evt){
            var categ_id = evt.target.value;
            //var data = evt.params.data;

            if(categ_id){
                $.ajax({
                    method: "GET",
                    dataType: "json",
                    url: '{{ url(request()->route()->getPrefix()) }}' + '/products/type/' + categ_id,

                    success: function(data){
                        // console.log('Data : ', data);
                        $('#modal-input-type-edit').empty();

                        $.each(data, function(key, value){
                            $('#modal-input-type-edit').append('<option value="'+ value +'">'+ key +'</option>');
                            //console.log('key='+key+ ', value='+value);
                        });
                    },

                });
            } else {
                $('#modal-input-type-edit').empty();
            }
        });






        //necessary addition
        /*
        $('#edit-modal').on('hidden.bs.modal', function() {
            $(this).find('#edit-form').off('click');
        });
        */

        //necessary additions for when the modals get hidden

        $('#edit-modal').on('hidden.bs.modal', function(evt){
            $(document).off('submit', '#edit-form');

            $('.whqty-edit').remove(); //remove all dynamically created the divs
        });

        $('#delete-modal').on('hidden.bs.modal', function(e){
            $(document).off('submit', '#delete-form');
        });

        //resets the create/add form. Re-use this code snippet in other blade views!
        $(document).on('click', '[data-dismiss="modal"]', function(e){
            $('#add-form').find("input,textarea,select").val('');

            //also, reset the error field(s).
            $('.alert-danger').hide();
            $('.alert-danger').html('');

            //empty the types select tag (hint: its already emptied)
            //$('#modal-input-type-edit').empty();
            //$('#modal-input-type-edit').val('');
        });

        $('#add-product-btn').on('click', function(evt){
            $('#add-form').find('select').val('');
            $('#add-form').find('select[name="modal-input-type-create"]').empty();

            // $('.whqty').remove();
            // x--; //x is not defined

        //    $('.whqty').empty();
        //    x = 1;
        //    console.log(x);

        });


        //the following is correct but cannot display the name!!

        $('#edit-modal').on('shown.bs.modal', function(evt){

            //console.log('evt: ', evt);
            /*
            //keep old value in type-edit, apparently it doesnt work...
            const typeedit_oldvalue = '{{ old("modal-input-type-edit") }}';
            console.log(typeedit_oldvalue);

            if(typeedit_oldvalue != ''){
                $('#modal-input-type-edit').val(typeedit_oldvalue);
            }
            */

            /*
            //var type_id = evt.target.attributes[8];
            var type_id = evt.relatedTarget.getAttribute("data-type"); //attributes[8].attr();
            console.log(type_id);
            //$('select[name="modal-input-type-edit"]').val(type_id);
            $('#modal-input-type-edit').empty();
            $('#modal-input-type-edit').append('<option value="'+ type_id +'">'+$(evt.relatedTarget).text()+'</option>');

            $('#modal-input-type-edit').val(evt.relatedTarget.attributes[8]);
            */
        });


        /*
        $('#edit-modal').on('shown.bs.modal', function(evt){
            console.log('Evt:: ',evt);


        });
        */







    </script>
@stop
