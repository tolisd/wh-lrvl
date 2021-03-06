{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη | Στοιχεία Αναθέσεων Εξαγωγής')

@section('content_header')
    <h1>Αποθήκη/Warehouse | Στοιχεία Αναθέσεων Εξαγωγής</h1>
@stop


@section('content')

<style>
    .dt-buttons{
        margin-bottom: 10px;
        padding-bottom: 5px;
    }
</style>

    <meta name="csrf-token" content="{{ csrf_token() }}">

    <div class="row">
        <div class="col-lg-12 col-xs-6">

            <p>Στοιχεία Αναθέσεων Εξαγωγής</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])

			<!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Ανάθεση Εξαγωγής</th>
						<th class="text-left">Υπεύθυνος Παράδοσης</th>
                        <th class="text-left">Εταιρεία Παράδοσης</th>
                        <th class="text-left">Ημ/νία &amp; Ώρα Παράδοσης</th>
                        <th class="text-left">Αρ.Κυκλ. Μετ.Μέσου</th>
                        <th class="text-left">Μεταφορική Εταιρεία</th>
						<th class="text-left">Τόπος Αποστολής</th>
						<th class="text-left">Τόπος Προορισμού</th>
						<th class="text-left">ΩΕ/ ΧρΩΕ</th>
						<!-- <th class="text-left">Δελτίο Αποστολής</th> -->
						<th class="text-left">Διακριτός Τίτλος Παραλαβής</th>
                        <th class="text-left">Εξαγόμενα Προϊόντα</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($exports as $export)
                    <tr class="user-row" data-eid="{{ $export->id }}">  <!-- necessary additions -->
                        <td>[{{ $export->export_assignment->export_assignment_code }}] <br/>
                            [{{ $export->export_assignment->warehouse->name }}] <br/>
                            [{{ $export->export_assignment->export_deadline->isoFormat('llll') }}]</td>
                        <td>{{ $export->employee->user->name }}</td>
                        <td>{{ $export->company->name }}</td>
                        <td>{{ $export->delivered_on->isoFormat('llll') }}</td>
                        <td>{{ $export->vehicle_reg_no }}</td>
                        <td>{{ $export->transport->name }}</td>
                        <td>{{ $export->shipment_address }}</td>
                        <td>{{ $export->destination_address }}</td>
                        <td>{{ $export->hours_worked }}/{{ $export->chargeable_hours_worked }}</td>
                        <!-- <td>{{ substr(basename($export->shipment_bulletin), 15) }}</td> attached pdf file -->
                        <td>{{ $export->item_description }}</td>

                        <td>
                            <ul>
                            @foreach($export->products as $product)
                                <li>{{ $product->name }}:&nbsp;{{ $product->pivot->quantity }}&nbsp;{{ $product->measureunit->name }}</li>
                            @endforeach
                            </ul>
                        </td>

                        <!-- <td>{{ $export->export_assignment->export_assignment_text }}</td> -->

                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-eid="{{ $export->id }}"
                                    data-employeeid="{{ $export->employee_id }}"
                                    data-employeesperwarehouse="{{ $employees_per_warehouse }}"
                                    data-warehouseid="{{ $export->export_assignment->warehouse_id }}"
                                    data-companyid="{{ $export->company_id }}"
                                    data-transportid="{{ $export->transport_id }}"
                                    data-vehicleregno="{{ $export->vehicle_reg_no }}"
                                    data-deliveredon="{{ $export->delivered_on->format('d-m-Y H:i') }}"
                                    data-shipmentaddress="{{ $export->shipment_address }}"
                                    data-destinationaddress="{{ $export->destination_address }}"
                                    data-chargeablehours="{{ $export->chargeable_hours_worked }}"
                                    data-hours="{{ $export->hours_worked }}"
                                    data-shipmentbulletin="{{ $export->shipment_bulletin }}"
                                    data-itemdescription="{{ $export->item_description }}"
                                    data-products="{{ $export->products }}"
                                    data-allproducts="{{ $products }}"
                                    data-productsinwarehouse="{{ $products_in_warehouse }}"
                                    data-exportassignmentid="{{ $export->exportassignment_id }}"
                                    data-exportassignment="{{ $export->export_assignment }}"
                                    data-eacode="{{ $export->export_assignment->export_assignment_code }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-eid="{{ $export->id }}"
                                    data-warehouse="{{ $export->export_assignment->warehouse->name }}"
                                    data-deliveredon="{{ $export->delivered_on->format('l, d-m-Y H:i') }}"
                                    data-eacode="{{ $export->export_assignment->export_assignment_code }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New User button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal" id="add-export-btn">Προσθήκη Στοιχείων Ανάθεσης Εξαγωγής</button>

            <br/><br/>
            @endcanany <!-- isSuperAdmin, isCompanyCEO, isAccountant  -->




            @canany(['isWarehouseForeman', 'isWarehouseWorker'])

            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Ανάθεση Εξαγωγής</th>
						<th class="text-left">Υπεύθυνος Παράδοσης</th>
                        <th class="text-left">Εταιρεία Παράδοσης</th>
                        <th class="text-left">Ημ/νία &amp; Ώρα Παράδοσης</th>
                        <th class="text-left">Αρ.Κυκλ. Μετ.Μέσου</th>
                        <th class="text-left">Μεταφορική Εταιρεία</th>
						<th class="text-left">Τόπος Αποστολής</th>
						<th class="text-left">Τόπος Προορισμού</th>
						<th class="text-left">ΩΕ/ ΧρΩΕ</th>
						<!-- <th class="text-left">Δελτίο Αποστολής</th> -->
						<th class="text-left">Διακριτός Τίτλος Παραλαβής</th>
                        <th class="text-left">Εξαγόμενα Προϊόντα</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($exports as $export)
                @foreach($exportassignments as $exportassignment)
                @foreach($warehouses as $warehouse)
                @foreach($warehouse->employees as $employee)

                    @if(($export->exportassignment_id == $exportassignment->id)
                     && ($exportassignment->warehouse_id == $warehouse->id)
                     && (\Auth::user()->id == $employee->user_id)
                     && (($employee->user->user_type == 'warehouse_foreman') || ($employee->user->user_type == 'warehouse_worker')))



                    <tr class="user-row" data-eid="{{ $export->id }}">  <!-- necessary additions -->
                        <td>[{{ $export->export_assignment->export_assignment_code }}] <br/>
                            [{{ $export->export_assignment->warehouse->name }}] <br/>
                            [{{ $export->export_assignment->export_deadline->isoFormat('llll') }}]</td>
                        <td>{{ $export->employee->user->name }}</td>
                        <td>{{ $export->company->name }}</td>
                        <td>{{ $export->delivered_on->isoFormat('llll') }}</td>
                        <td>{{ $export->vehicle_reg_no }}</td>
                        <td>{{ $export->transport->name }}</td>
                        <td>{{ $export->shipment_address }}</td>
                        <td>{{ $export->destination_address }}</td>
                        <td>{{ $export->hours_worked }}/{{ $export->chargeable_hours_worked }}</td>
                        <!-- <td>{{ substr(basename($export->shipment_bulletin), 15) }}</td> attached pdf file -->
                        <td>{{ $export->item_description }}</td>

                        <td>
                            <ul>
                            @foreach($export->products as $product)
                                <li>{{ $product->name }}:&nbsp;{{ $product->pivot->quantity }}&nbsp;{{ $product->measureunit->name }}</li>
                            @endforeach
                            </ul>
                        </td>

                        <!-- <td>{{ $export->export_assignment->export_assignment_text }}</td> -->

                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-eid="{{ $export->id }}"
                                    data-employeeid="{{ $export->employee_id }}"
                                    data-employeesperwarehouse="{{ $employees_per_warehouse }}"
                                    data-warehouseid="{{ $export->export_assignment->warehouse_id }}"
                                    data-companyid="{{ $export->company_id }}"
                                    data-transportid="{{ $export->transport_id }}"
                                    data-vehicleregno="{{ $export->vehicle_reg_no }}"
                                    data-deliveredon="{{ $export->delivered_on->format('d-m-Y H:i') }}"
                                    data-shipmentaddress="{{ $export->shipment_address }}"
                                    data-destinationaddress="{{ $export->destination_address }}"
                                    data-chargeablehours="{{ $export->chargeable_hours_worked }}"
                                    data-hours="{{ $export->hours_worked }}"
                                    data-shipmentbulletin="{{ $export->shipment_bulletin }}"
                                    data-itemdescription="{{ $export->item_description }}"
                                    data-products="{{ $export->products }}"
                                    data-allproducts="{{ $products }}"
                                    data-productsinwarehouse="{{ $products_in_warehouse }}"
                                    data-exportassignmentid="{{ $export->exportassignment_id }}"
                                    data-exportassignment="{{ $export->export_assignment }}"
                                    data-eacode="{{ $export->export_assignment->export_assignment_code }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-eid="{{ $export->id }}"
                                    data-warehouse="{{ $export->export_assignment->warehouse->name }}"
                                    data-deliveredon="{{ $export->delivered_on->format('l, d-m-Y H:i') }}"
                                    data-eacode="{{ $export->export_assignment->export_assignment_code }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                    @endif

                @endforeach
                @endforeach
                @endforeach
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New User button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal" id="add-export-btn">Προσθήκη Στοιχείων Ανάθεσης Εξαγωγής</button>

            <br/><br/>

            @endcanany
            <!-- isWarehouseWorker, isWarehouseForeman -->






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

            @can('isWarehouseWorker')
                <a href="{{ route('worker.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan





			@canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant', 'isWarehouseWorker'])
            <!-- The 3 Modals, Add/Update/Delete -->

            <!-- the Add/Create new Export Assignment, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Στοιχείων Ανάθεσης Εξαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="add-form" class="form-horizontal" method="POST" enctype="multipart/form-data" novalidate>
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
                                    <input type="hidden" id="modal-input-eid-create" name="modal-input-eid-create" value="">

                                    <!-- export_assignment -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-exportassignment-create">Ανάθεση Εξαγωγής</label>
                                        <div class="col-lg-9">
                                            <select name="modal-input-exportassignment-create" id="modal-input-exportassignment-create" class="form-control">
                                            @foreach($open_export_assignments_frmn_wrkr as $expassgnm)
                                                <option value="{{ $expassgnm->id }}">[{{ $expassgnm->export_assignment_code }}]&nbsp;/&nbsp;[{{ $expassgnm->warehouse->name }}],&nbsp;[{{ $expassgnm->export_deadline->isoFormat('llll') }}]</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div>
									<!-- /export_assignment -->

									 <!-- recipient name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-recipient-create">Υπεύθυνος Παράδοσης</label>
                                        <div class="col-lg-9">
                                        <!--
                                            <input type="text" name="modal-input-recipient-create" class="form-control" id="modal-input-recipient-create"
                                                value="" />
                                        -->
                                            <select name="modal-input-recipient-create" id="modal-input-recipient-create" class="form-control">

                                            </select>
                                        </div>
									</div>
									<!-- /recipient name -->

									<!-- import company name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-expco-create">Eταιρεία Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <!--
                                            <input type="text" name="modal-input-expco-create" class="form-control" id="modal-input-expco-create"
                                                value="" />
                                            -->
                                            <select name="modal-input-expco-create" id="modal-input-expco-create" class="form-control">
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div>
									<!-- /import company name -->

									<!-- date_time_delivered_on -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtdeliv-create">Ημ/νία &amp; Ώρα Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-dtdeliv-create" class="form-control" id="modal-input-dtdeliv-create"
                                                value="" autocomplete="off" />
                                        </div>
									</div>
									<!-- /date_time_delivered_on -->

									<!-- vehicle_registration_no -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-vehicleregno-create">Αρ.Κυκλοφορίας Μεταφορικού Μέσου</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-vehicleregno-create" class="form-control" id="modal-input-vehicleregno-create"
                                                value="" />
                                        </div>
									</div>
									<!-- /vehicle_registration_no -->

									<!-- shipping_company -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-shipco-create">Μεταφορική Εταιρεία</label>
                                        <div class="col-lg-9">
                                        <!--
                                            <input type="text" name="modal-input-shipco-create" class="form-control" id="modal-input-shipco-create"
                                                value="" />
                                        -->
                                        <select name="modal-input-shipco-create" id="modal-input-shipco-create" class="form-control">
                                        @foreach($transport_companies as $transcomp)
                                            <option value="{{ $transcomp->id }}">{{ $transcomp->name }}</option>
                                        @endforeach
                                        </select>
                                        </div>
									</div>
									<!-- /shipping_company -->

							<!-- sendingplace -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-sendplace-create">Τόπος Αποστολής</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-sendplace-create" class="form-control" id="modal-input-sendplace-create"
                                                value="" />
                                        </div>
									</div>
									<!-- /sendingplace -->

									<!-- destination -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-destin-create">Τόπος Προορισμού</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-destin-create" class="form-control" id="modal-input-destin-create"
                                                value="" />
                                        </div>
									</div>
									<!-- /destination -->

									<!-- chargeable_work_hours -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-chargehrs-create">Χρεώσιμες Ώρες Εργασίας</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-chargehrs-create" class="form-control" id="modal-input-chargehrs-create"
                                                value="" />
                                        </div>
									</div>
									<!-- /chargeable_work_hours -->

									<!-- work_hours -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-hours-create">Ώρες Εργασίας</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-hours-create" class="form-control" id="modal-input-hours-create"
                                                value="" />
                                        </div>
									</div>
									<!-- /work_hours -->

									<!-- shipping_bulletin -->
									<!-- <div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-bulletin-create">Δελτίο Αποστολής [αρχείο PDF]
                                        <i class="fas fa-paperclip text-danger" aria-hidden="true"></i></label>

                                        <div class="col-lg-9">
                                            <input type="file" name="modal-input-bulletin-create" class="form-control" id="modal-input-bulletin-create"
                                                value="" />
                                        </div>
									</div> -->
									<!-- /shipping_bulletin -->

									<!-- delivery_description -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtitle-create">Διακριτός Τίτλος Παραλαβής</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-dtitle-create" class="form-control" id="modal-input-dtitle-create"
                                                value="" />
                                        </div>
									</div>
									<!-- /delivery_description -->

                                    <!-- products -->
									<!-- <div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-products-create">Προϊόντα</label>
                                        <div class="col-lg-9">
                                            <select name="modal-input-products-create[]" id="modal-input-products-create" class="form-control" multiple="multiple">

                                            </select>
                                        </div>
									</div> -->
									<!-- /products -->

                                    <!-- product in Import Assignment -->
                                    <div class="form-group row">

                                        <div class="col">

                                            <div class="wrapper-newprodqty">

                                                <div class="form-group row">
                                                    <label class="col-form-label col-lg-3 text-right" for="modal-input-prod-create">Προϊόν & Ποσότητα</label>

                                                    <div class="col-lg-7">
                                                        <select name="modal-input-prod-create[]" id="modal-input-prod-create" class="form-control">

                                                        </select>
                                                    </div>

                                                    <div class="col-lg-2">
                                                        <input type="text" name="modal-input-prodqty-create[]" class="form-control" id="modal-input-prodqty-create"
                                                            value="" placeholder="ποσότητα" />
                                                    </div>

                                                </div>

                                                <div class="form-group row justify-content-center">
                                                    <button type="button" class="btn btn-info col-lg-9" id="add-prodqty-button-create">
                                                        <strong>[+]</strong>&nbsp;Πρόσθεσε Προϊόν</button>
                                                </div>

                                                <!-- append new prodqty div exactly here -->

                                            </div>

                                        </div>

									</div>
                                    <!-- /product in Import Assignment -->




                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-export-button"
                                data-target="#add-modal">Πρόσθεσε Στοιχεία Ανάθεσης Εξαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>



            <!-- the Edit/Update existing ExportAssignment, Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Στοιχείων Ανάθεσης Εξαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="edit-form" class="form-horizontal" method="POST" enctype="multipart/form-data" novalidate>
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
                                    <h2 class="m-0">Μεταβολή Ανάθεσης Εξαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-eid-edit" name="modal-input-eid-edit" value="">

                                    <!-- added hidden input for warehouse (via export_assignment) -->
                                    <input type="hidden" id="modal-input-warehouse-edit" name="modal-input-warehouse-edit" value="">

                                    <!-- added hidden input for exportassignment_id -->
                                    <input type="hidden" id="modal-input-exportassignmentid-edit" name="modal-input-exportassignmentid-edit">


                                     <!-- export_assignment -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-exportassignment-edit">Ανάθεση Εξαγωγής</label>
                                        <div class="col-lg-9">
                                        <!--
                                            <input type="text" name="modal-input-exportassignment-edit" class="form-control-plaintext"
                                                id="modal-input-exportassignment-edit" value="" readonly />
                                        -->
                                            <select name="modal-input-exportassignment-edit" id="modal-input-exportassignment-edit" class="form-control">
                                            @foreach($exportassignments as $expassgnm)
                                                <option value="{{ $expassgnm->id }}">[{{ $expassgnm->export_assignment_code }}]&nbsp;/&nbsp;[{{ $expassgnm->warehouse->name }}],&nbsp;[{{ $expassgnm->export_deadline->isoFormat('llll') }}]</option>
                                            @endforeach
                                            </select>

                                        </div>
									</div>
									<!-- /export_assignment -->

									 <!-- recipient name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-recipient-edit">Υπεύθυνος Παράδοσης</label>
                                        <div class="col-lg-9">
                                        <!--
                                            <input type="text" name="modal-input-recipient-edit" class="form-control" id="modal-input-recipient-edit"
                                                value="" />
                                        -->
                                            <select name="modal-input-recipient-edit" id="modal-input-recipient-edit" class="form-control">

                                            </select>
                                        </div>
									</div>
									<!-- /recipient name -->

									<!-- import company name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-expco-edit">Eταιρεία Παράδοσης</label>
                                        <div class="col-lg-9">
                                        <!--
                                            <input type="text" name="modal-input-expco-edit" class="form-control" id="modal-input-expco-edit"
                                                value="" />
                                        -->
                                            <select name="modal-input-expco-edit" id="modal-input-expco-edit" class="form-control">
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div>
									<!-- /import company name -->

									<!-- date_time_delivered_on -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtdeliv-edit">Ημ/νία &amp; Ώρα Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-dtdeliv-edit" class="form-control" id="modal-input-dtdeliv-edit"
                                                value="" autocomplete="off" />
                                        </div>
									</div>
									<!-- /date_time_delivered_on -->

									<!-- vehicle_registration_no -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-vehicleregno-edit">Αρ.Κυκλοφορίας Μεταφορικού Μέσου</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-vehicleregno-edit" class="form-control" id="modal-input-vehicleregno-edit"
                                                value="" />
                                        </div>
									</div>
									<!-- /vehicle_registration_no -->

									<!-- shipping_company -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-shipco-edit">Μεταφορική Εταιρεία</label>
                                        <div class="col-lg-9">
                                        <!--
                                            <input type="text" name="modal-input-shipco-edit" class="form-control" id="modal-input-shipco-edit"
                                                value="" />
                                        -->
                                            <select name="modal-input-shipco-edit" id="modal-input-shipco-edit" class="form-control">
                                            @foreach($transport_companies as $transcomp)
                                                <option value="{{ $transcomp->id }}">{{ $transcomp->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div>
									<!-- /shipping_company -->

							<!-- sendingplace -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-sendplace-edit">Τόπος Αποστολής</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-sendplace-edit" class="form-control" id="modal-input-sendplace-edit"
                                                value="" />
                                        </div>
									</div>
									<!-- /sendingplace -->

									<!-- destination -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-destin-edit">Τόπος Προορισμού</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-destin-edit" class="form-control" id="modal-input-destin-edit"
                                                value="" />
                                        </div>
									</div>
									<!-- /destination -->

									<!-- chargeable_work_hours -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-chargehrs-edit">Χρεώσιμες Ώρες Εργασίας</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-chargehrs-edit" class="form-control" id="modal-input-chargehrs-edit"
                                                value="" />
                                        </div>
									</div>
									<!-- /chargeable_work_hours -->

									<!-- work_hours -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-hours-edit">Ώρες Εργασίας</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-hours-edit" class="form-control" id="modal-input-hours-edit"
                                                value="" />
                                        </div>
									</div>
									<!-- /work_hours -->

									<!-- shipping_bulletin -->
									<!-- <div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-bulletin-edit">Δελτίο Αποστολής [αρχείο PDF]
                                        <i class="fas fa-paperclip text-danger" aria-hidden="true"></i></label>

                                        <div class="col-lg-9">
                                            <span id="arxeio-DA"></span>
                                            <input type="file" name="modal-input-bulletin-edit" class="form-control" id="modal-input-bulletin-edit"
                                                value="" />
                                        </div>
									</div> -->
									<!-- /shipping_bulletin -->

									<!-- delivery_description -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtitle-edit">Διακριτός Τίτλος Παραλαβής</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-dtitle-edit" class="form-control" id="modal-input-dtitle-edit"
                                                value="" />
                                        </div>
									</div>
									<!-- /delivery_description -->


                                    <!-- products -->
									<!-- <div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-products-edit">Προϊόντα</label>
                                        <div class="col-lg-9">
                                            <select name="modal-input-products-edit[]" id="modal-input-products-edit" class="form-control" multiple="multiple">
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div> -->
									<!-- /products -->



                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-export-button"
                                data-target="#edit-modal" data-eid="">Διόρθωσε Ανάθεση Εξαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update/edit form -->

                    </div>
                </div>
            </div>




            <!-- the Delete existing Export Assignment, Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Στοιχείων Ανάθεσης Εξαγωγής</h4>
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
                                    <h2 class="m-0">Διαγραφή Ανάθεσης Εξαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε τα παρακάτω Στοιχεία Ανάθεσης Εξαγωγής;</p>


                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-eid-del" name="modal-input-eid-del" value="" />
                                    </div>

                                    <div class="form-group">
										<label class="col-form-label" for="modal-input-code-del">Κωδικός Ανάθεσης</label>
										<input type="text" name="modal-input-code-del" class="form-control-plaintext" id="modal-input-code-del"
											value="" />
									</div>

                                    <div class="form-group">
										<label class="col-form-label" for="modal-input-warehouse-del">Αποθήκη</label>
										<input type="text" name="modal-input-warehouse-del" class="form-control-plaintext" id="modal-input-warehouse-del"
											value="" />
									</div>

                                    <!-- date_time_delivered_on -->
                                    <!--
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtdeliv-del">Ημ/νία &amp; Ώρα Παράδοσης</label>
                                        <div class="col-lg-9">
                                            <input type="text" name="modal-input-dtdeliv-del" class="form-control-plaintext" id="modal-input-dtdeliv-del"
                                                value="" autocomplete="off" />
                                        </div>
									</div>
                                    -->
                                    <div class="form-group">
										<label class="col-form-label" for="modal-input-dtdeliv-del">Ημ/νία &amp; Ώρα Παράδοσης</label>
										<input type="text" name="modal-input-dtdeliv-del" class="form-control-plaintext" id="modal-input-dtdeliv-del"
											value="" />
									</div>
									<!-- /date_time_delivered_on -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-export-button"
                                data-target="#delete-modal" data-toggle="modal" data-eid="">Διέγραψε Στοιχεία Ανάθεσης Εξαγωγής</button>
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

        //initialise the two select2 components.
        //I disabled them both because now I need the quantities as well by <them class="">
        //so it's going to be a separate div with a single-select and a text input
        // $('#modal-input-products-create').select2();
        // $('#modal-input-products-edit').select2();


         //configure & initialise the (Export Assignments) DataTable
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
                            "text"   : "Αντιγραφή",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7,8,9,10,11]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Στοιχεία Αναθέσεων Εξαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7,8,9,10,11]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Στοιχεία Αναθέσεων Εξαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7,8,9,10,11]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Στοιχεία Αναθέσεων Εξαγωγής",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7,8,9,10,11]
                            },
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7,8,9,10,11]
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


    var html1 = '<div class="form-group row newprodqty">'+
                '<label class="col-form-label col-lg-3 text-right" for="modal-input-prod-create">Προϊόν & Ποσότητα</label>'+
                '<div class="col-lg-7"><select name="modal-input-prod-create[]" id="modal-input-prod-create" class="form-control createnewdiv">'+
                '</select>'+
                '</div><div class="col-lg-2">'+
                '<input type="text" name="modal-input-prodqty-create[]" class="form-control" id="modal-input-prodqty-create" value="" placeholder="ποσότητα" />'+
                '</div></div><div class="form-group row justify-content-center">'+
                '<button type="button" class="btn btn-danger col-lg-9 minus-btn" id="minus-prodqty-button-create"><strong>[&ndash;]</strong>&nbsp;Αφαίρεσε Προϊόν</button></div>';



        $('#modal-input-dtdeliv-create').datetimepicker({
            format:'d-m-Y H:i',
            timepicker: true,
            datepicker: true,
            minDate: new Date()
            //lang: 'el',
        });

        $('#modal-input-dtdeliv-edit').datetimepicker({
            format:'d-m-Y H:i',
            timepicker: true,
            datepicker: true,
            minDate: new Date()
            //lang: 'el',
        });

        jQuery.datetimepicker.setLocale('el');

        //helper function
        function base_name(path) {
            return path.split('/').reverse()[0].substr(15);
        }



    //the 3 modals follow::
        $('#edit-modal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget); // Button that triggered the modal

            var eid = button.data('eid'); // Extract info from data-* attributes
            //var warehouse = button.data('warehouse');
            var employeeid = button.data('employeeid');
            var employeesperwarehouse = button.data('employeesperwarehouse');
            var warehouseid = button.data('warehouseid');
            //var warehouses = button.data('warehouses');

            var companyid = button.data('companyid');
            var transportid = button.data('transportid');

            var exportassignmentid = button.data('exportassignmentid'); //hidden input
            var export_assignment = button.data('exportassignment');

            var vehicleregno = button.data('vehicleregno');
            var deliveredon = button.data('deliveredon');
            var shipmentaddress = button.data('shipmentaddress');
            var destinationaddress = button.data('destinationaddress');
            var chargeablehours = button.data('chargeablehours');
            var hours = button.data('hours');
            var shipmentbulletin = button.data('shipmentbulletin');
            var itemdescription = button.data('itemdescription');
            //var  = button.data('');
            var products = button.data('products');
            var allproducts = button.data('allproducts');
            var products_in_warehouse = button.data('productsinwarehouse');
            //var allwarehouseproducts = button.data('warehouseproductsall');
            //var prodwh = button.data('prodwh');
            var eacode = button.data('eacode');

            //console.log('deltio apostolis: ', shipmentbulletin);

            // console.log('products: ', products);
            // console.log('allproducts: ', allproducts);
            // console.log('products_in_warehouse (pivot table):', products_in_warehouse);

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-eid-edit').val(eid);
            //modal.find('.modal-body #modal-input-warehouse-edit').val(warehouse);
            modal.find('.modal-body #modal-input-recipient-edit').val(employeeid);
            modal.find('.modal-body #modal-input-expco-edit').val(companyid);
            modal.find('.modal-body #modal-input-shipco-edit').val(transportid);
            modal.find('.modal-body #modal-input-exportassignmentid-edit').val(exportassignmentid); //hidden input
            modal.find('.modal-body #modal-input-vehicleregno-edit').val(vehicleregno);
            modal.find('.modal-body #modal-input-dtdeliv-edit').val(deliveredon);
            modal.find('.modal-body #modal-input-sendplace-edit').val(shipmentaddress);
            modal.find('.modal-body #modal-input-destin-edit').val(destinationaddress);
            modal.find('.modal-body #modal-input-chargehrs-edit').val(chargeablehours);
            modal.find('.modal-body #modal-input-hours-edit').val(hours);
            //modal.find('.modal-body #modal-input-bulletin-edit').val(shipmentbulletin);
            modal.find('.modal-body #modal-input-dtitle-edit').val(itemdescription);

            modal.find('.modal-body #arxeio-DA').empty();
            modal.find('.modal-body #arxeio-DA').append('<li>' + base_name(shipmentbulletin) + '</li>');

            modal.find('.modal-body #modal-input-warehouse-edit').val(warehouseid);



            //Export assignment name!
            // modal.find('.modal-body #modal-input-exportassignment-edit').empty();
            //console.log(export_assignment);
            // modal.find('.modal-body #modal-input-exportassignment-edit').val('['+export_assignment.export_assignment_code+']/['+export_assignment.warehouse.name +'],[' + export_assignment.export_deadline+']');
            modal.find('.modal-body #modal-input-exportassignment-edit').val(exportassignmentid);




            //Employees in Warehouse

            //EMPTY the recipients for now. they will be populated with ajax
            modal.find('.modal-body #modal-input-recipient-edit').empty();
            //console.log('emps_per_wh: ', employeesperwarehouse);

            $.each(employeesperwarehouse, function(key, val){
                //console.log('eval: ', val);
                //console.log('employeeid', employeeid);
                //console.log('warehouseid', warehouseid);
                if((warehouseid == val.warehouse_id) && (employeeid == val.id)){
                    //console.log('emp => val_id: '+ val.id +'val_warehouseid'+ val.warehouse_id);
                    modal.find('.modal-body #modal-input-recipient-edit').append('<option value="'+ val.id +'">' + val.name + '</option>');
                } else if (warehouseid == val.warehouse_id){
                    //console.log('(else) emp => val_id: '+ val.id +'val_warehouse_id'+ val.warehouse_id);
                    modal.find('.modal-body #modal-input-recipient-edit').append('<option value="'+ val.id +'">' + val.name + '</option>');
                }
            });


            //Products in Warehouse

            //console.log('products_in_warehouse:', products_in_warehouse); //all the products, irrespectable of warehouse!
            modal.find('.modal-body #modal-input-products-edit').empty();

            var prods_in_wh = [];
            prods_in_wh = products_in_warehouse.filter(a => a.warehouse_id === warehouseid);
            // console.log('prods_in_wh: ', prods_in_wh);

            //the Selected ones
            var selected_prds = [];
            //selected_prds = products_in_warehouse.filter(a => products.some(b => a.product_id === b.id));
            selected_prds = products_in_warehouse.filter(a => products.some(b => a.product_id === b.id));
            // console.log('selected_prds: ', selected_prds);

            $.each(products, function(k,v){
                modal.find('.modal-body #modal-input-products-edit').append('<option selected value="'+ v.id +'">' + v.name + '</option>');
            });

            ///difference := the NON-Selected ones
            // ----> (prods_in_wh - selected_prds) == difference
            var difference = []; //the rest of the products, in the same warehouse, (but non-selected).
            difference = prods_in_wh.filter(a => !selected_prds.some(b => a.product_id === b.product_id));
            // console.log('difference: ', difference);

            $.each(difference, function(k,v){
                modal.find('.modal-body #modal-input-products-edit').append('<option value="'+ v.id +'">' + v.name + '</option>');
            });




            modal.find('.modal-footer #edit-button').attr("data-eid", eid);  //SET Export assignment id value in data-eid attribute


            //AJAX Update/Edit User
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(eid);
                // console.log(formData);

                //reset the error field(s).
                $('.alert-danger').hide();
                $('.alert-danger').html('');

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/update/" + eid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Στοιχείων Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/view/";
                            }
                        });
                    },
                    error: function(xhr){
                        console.log('Error:', xhr);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(xhr.status == 500){
                            msg = 'Τα Στοιχεία Ανάθεσης Εξαγωγής υπάρχουν ήδη!';
                        } else if (xhr.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης Στοιχείων Ανάθεσης Εξαγωγής!';
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
            //var export_text = button.data('text1');
            var warehouse = button.data('warehouse');
            var datetime1 = button.data('deliveredon');
            var eacode = button.data('eacode');


            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.modal-body .card .card-body #modal-input-eid-del').val(eid); //change the value to...
            //modal.find('.modal-body .card .card-body #modal-input-text-del').val(export_text);
            modal.find('.modal-body .card .card-body #modal-input-code-del').val(eacode);
            modal.find('.modal-body .card .card-body #modal-input-warehouse-del').val(warehouse);
            modal.find('.modal-body .card .card-body #modal-input-dtdeliv-del').val(datetime1);

            modal.find('.modal-footer #delete-button').attr("data-eid", eid); //SET user id value in data-eid attribute


            //AJAX Delete existing Product
            //event delegation here..
            $(document).on("submit", "#delete-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(eid);
                // console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/delete/" + eid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Στοιχείων Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/view/";
                            }
                        });
                    },
                    error: function(response){
                        console.log('Error:', response);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(response.status == 500){
                            msg = 'Τα Στοιχεία Ανάθεσης Εξαγωγής υπάρχουν ήδη!';
                        } else if (response.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής Στοιχεώιν Ανάθεσης Εξαγωγής!';
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

            //reset the error field(s).
            $('.alert-danger').hide();
            $('.alert-danger').html('');

            $.ajax({
                method: "POST",
                data: formData,
                cache: false,
                contentType: false, //do not set any content type header
                processData: false, //send non-processed data
                dataType: "json",
                url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Στοιχείων Ανάθεσης Εξαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/exports/view/";
                            }
                        });
                },
                error: function(xhr){
                    console.log('Error:', xhr);

                    var msg = 'Συνέβη κάποιο λάθος!';

                    if(xhr.status == 500){
                        msg = 'Τα Στοιχεία Ανάθεσης Εξαγωγής υπάρχει ήδη!';
                    } else if (xhr.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας Στοιχείων Ανάθεσης Εξαγωγής!';
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


        //ajax for dropdown lists in add and edit modals
        var z = 1;

        //ajax add modal
        $(document).on('change', '#modal-input-exportassignment-create', function(evt){
            console.log(evt.target.value);
            console.log('Event: ',evt);

            var wh_id = evt.target.value;

            //when i re-change, I want the NEW divs removed!
            $('.modal-body .newprodqty').each(function(){
                $(this).remove();
                $('.minus-btn').remove();
                // $(this).parent().remove();

                // z--;
            });

            $('.modal-body select.createnewdiv').val('');
            $('.modal-body .createnewdiv').empty();




            if(wh_id){
                $.ajax({
                    method: "GET",
                    dataType: "json",
                    url: '{{ url(request()->route()->getPrefix()) }}' + '/assignments/exports/warehouse/' + wh_id,

                    success: function(data){
                        //variable "data" is received from the appropriate controller, as defined in web.php and it returns:= return json_encode($.....);
                        // console.log('Data: ', data);

                        $('.modal-body #modal-input-recipient-create').empty();

                        $('.modal-body #modal-input-prod-create').empty();

                        $.each(data, function(key, value){

                            // console.log('data_again', data);
                            // console.log('key=', key);
                            // console.log('value= ', value);
                            // console.log('value_length', value.length);

                            //value.forEach((item, index) =>  $('#modal-input-recipient-create').append('<option value="'+ item.id +'">'+ item.name +'</option>'));

                            if(key == 0){
                                $.each(value, function(k,v){
                                    $('.modal-body #modal-input-recipient-create').append('<option value="'+ v.id +'">'+ v.name +'</option>');
                                });
                            }

                            if(key == 1){
                                $.each(value, function(k,v){
                                    $('.modal-body #modal-input-prod-create').append('<option value="'+ v.product_id +'">' + v.name+ '</option>');
                                });
                            }

                        });

                        // <here go> 3 nested functions
                        var product_count = data[1].length;
                        console.log('product_count=', product_count);
                        var len = $('.modal-body .newprodqty').length;
                        // var len1 = $('.wrapper-newprodqty').length;
                        var diff = product_count - len;
                        // var diff1 = product_count - len1;
                        // var z = 1;

                        // console.log('z = '+z+', diff ='+diff);
                        // console.log('difference = ', diff);


                        //REMOVE the new div  //NESTED function too
                        $(document).on('click', '.minus-btn', function(evt1){
                            // console.log(evt1);
                            // evt1.preventDefault();
                            // console.log('z = '+z+', diff ='+diff);
                            // $(this).closest('.wrapper-newprodqty').find('.newprodqty').remove(); //removes ALL  .newprodqty subDIVs!

                            // if(z >= diff){
                                //removes the immediately previous sibling which is the div .newprodqty, test it extensively after you will have added products
                                $(this).parent().prev().remove(); //ok, this works but adds empty divs (div class="form-group row justify-content-center"), as i remove them
                                $(this).parent().remove(); //ok. this line removes the empty div as well:)
                                $(this).remove(); //removes the button itself!

                                z--;
                            // }

                        });


                        // var product_count = data[1].length;
                        // console.log('product_count=', product_count);
                        // var len = $('.modal-body .newprodqty').length;
                        // var diff = product_count - len;
                        // z = 1; //my counter for new products

                        //this NESTED function did the trick  //ADD new divs WITH the products.
                        //gets its 'data' parameter from AJAX success function
                        $(document).on('click', '#add-prodqty-button-create', function(evt){
                            // console.log('EVENT= ', evt.target.value);
                            // console.log('EVENT= ', $(evt.target).text());
                            // evt.preventDefault();

                            if(z < diff){

                                z++;
                                $('.wrapper-newprodqty').append(html1); //ADD the new div
                                // $('#add-form').find('select.createnewdiv').val('');

                                $('.modal-body .createnewdiv').empty();
                                // console.log("Data-Create = ", data);

                                $.each(data, function(key, value){
                                    // console.log('keyc=', key);
                                    // console.log('valuec= ', value);
                                    $.each(value, function(k,v){
                                        if(key == 1){
                                            // console.log('kc=', k);
                                            // console.log('vc= ', v);
                                            $('.modal-body .createnewdiv').append('<option value="'+ v.product_id +'">' + v.name+ '</option>');
                                            $('#add-form').find('select.createnewdiv').val('');
                                        }
                                    });
                                });


                            } else {
                                Swal.fire({
                                    icon: "error",
                                    type: "error",
                                    title: 'Προσοχή!',
                                    text: 'Δεν μπορείτε να προσθέσετε περισσότερα Προϊόντα!',
                                });

                                // return false;
                            }

                        });


                        //check for duplicate products! important!
                        //works perfectly!! do NOT modify it!!
                        $(document).on('change', 'select.createnewdiv', function(){

                            var valueOfChangedInput = $(this).val();
                            var timeRepeated = 0;
                            var tR = 0;

                            //"old" select VS. "new" select
                            $("select#modal-input-prod-create").each(function(){
                                //Inside each() check the 'valueOfChangedInput' with all other existing input
                                if ($(this).val() == valueOfChangedInput) {
                                    timeRepeated++; //this will be executed at least 1 time because of the input, which is changed just now
                                }
                            });

                            //"new" select VS. "new" select
                            $('select.createnewdiv').each(function(){ //this == select.selc1
                                //Inside each() check the 'valueOfChangedInput' with all other existing input
                                if ($(this).val() == valueOfChangedInput) {
                                    tR++; //this will be executed at least 1 time because of the input, which is changed just now
                                }
                            });

                            if((timeRepeated > 1) || (tR > 1)){ //changed from timeRepeated > 1 TO timeRepeated >= 1, and it worked!

                                $('#add-form').find('select.createnewdiv').val('');

                                Swal.fire({
                                    icon: "error",
                                    type: "error",
                                    title: 'Προσοχή!',
                                    text: 'Τα προϊόντα πρέπει να είναι διαφορετικά μεταξύ τους! Παρακαλώ επανεπιλέξτε Προϊόντα!',
                                });
                            }

                        });

                        //the inverse of the above!
                        $(document).on('change', 'select#modal-input-prod-create', function(){

                            var valueOfChangedInput = $(this).val();
                            var timeRepeated = 0;
                            var tR = 0;

                            //"old" select VS. "new" select
                            $("select#modal-input-prod-create").each(function(){
                                //Inside each() check the 'valueOfChangedInput' with all other existing input
                                if ($(this).val() == valueOfChangedInput) {
                                    timeRepeated++; //this will be executed at least 1 time because of the input, which is changed just now
                                }
                            });

                            //"new" select VS. "new" select
                            $('select.createnewdiv').each(function(){ //this == select.selc1
                                //Inside each() check the 'valueOfChangedInput' with all other existing input
                                if ($(this).val() == valueOfChangedInput) {
                                    tR++; //this will be executed at least 1 time because of the input, which is changed just now
                                }
                            });

                            if((timeRepeated > 1) || (tR > 1)){ //changed from timeRepeated > 1 TO timeRepeated >= 1, and it worked!

                                $('#add-form').find('select.createnewdiv').val('');

                                Swal.fire({
                                    icon: "error",
                                    type: "error",
                                    title: 'Προσοχή!',
                                    text: 'Τα προϊόντα πρέπει να είναι διαφορετικά μεταξύ τους! Παρακαλώ επανεπιλέξτε Προϊόντα!',
                                });
                            }

                        });


                    },

                });
            } else {
                $('#modal-input-recipient-create').empty();
                $('#modal-input-prod-create').empty();
            }

        });






        //ajax edit modal

        $(document).on('change', '#modal-input-exportassignment-edit', function(evt){
            console.log('Event', evt);
            var wh_id = evt.target.value;
            //var data = evt.params.data;

            if(wh_id){
                $.ajax({
                    method: "GET",
                    dataType: "json",
                    url: '{{ url(request()->route()->getPrefix()) }}' + '/assignments/exports/warehouse/' + wh_id,

                    success: function(data){
                        // console.log('Data : ', data);

                        $('.modal-body #modal-input-recipient-edit').empty();
                        $('.modal-body #modal-input-products-edit').empty();

                        $.each(data, function(key, value){
                            // $('#modal-input-recipient-edit').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                            //console.log('key='+key+ ', value='+value);
                            // console.log('Data(again) : ', data);

                            if(key == 0){
                                $.each(value, function(k,v){
                                    $('.modal-body #modal-input-recipient-edit').append('<option value="'+ v.id +'">'+ v.name +'</option>');
                                });
                            }

                            if(key == 1){
                                $.each(value, function(k,v){
                                    $('.modal-body #modal-input-products-edit').append('<option value="'+ v.product_id +'">' + v.name+ '</option>');
                                });
                            }

                            //$('.modal-body #modal-input-products-edit').append('<option value="'+ val['id'] +'">' + val['name'] + '</option>');
                        });
                    },

                });
            } else {
                $('#modal-input-recipient-edit').empty();
            }
        });



        $('#add-modal').on('show.bs.modal', function(e){
            //when i open the add modal i want no values in there, it will be populated by ajax instead!
            $('.modal-body #modal-input-prod-create').empty();
            // $('.modal .wrapper-newprodqty').empty();

            $('.modal-body .newprodqty').each(function(){
                $(this).remove();
                $('.minus-btn').remove();
                // $(this).parent().remove();

                z--; //added the counter here as well
            });
        });


        $('#add-modal').on('hidden.bs.modal', function(e){
            //so as not to add anymore product/qty divs AFTER the modal is re-opened
            $(document).off('click', '#add-prodqty-button-create');
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
            $('#add-form').find("select#modal-input-recipient-create").empty(); //additionally for select to empty it, or else it retains old values!

            //reset the error field(s).
            $('.alert-danger').hide();
            $('.alert-danger').html('');
        });


        $('#add-export-btn').on('click', function(evt){
            $('#add-form').find('select').val('');
        });


    </script>
@stop
