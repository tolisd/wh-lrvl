{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη | Στοιχεία Αναθέσεων Εισαγωγής')

@section('content_header')
    <h1>Αποθήκη/Warehouse | Στοιχεία Αναθέσεων Εισαγωγής</h1>
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

            <p>Στοιχεία Αναθέσεων Εισαγωγής</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])

			<!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Ανάθεση Εισαγωγής</th>
                        <th class="text-left">Υπεύθυνος Παραλαβής</th>
                        <th class="text-left">Εταιρεία Εισαγωγής</th>
                        <th class="text-left">Ημ/νία &amp; Ώρα Παραλαβής</th>
                        <th class="text-left">Αρ.Κυκλ. Μεταφ.Μέσου</th>
                        <th class="text-left">Μεταφορική Εταιρεία</th>
						<th class="text-left">Τόπος Αποστολής</th>
						<th class="text-left">ΩΕ/ ΧρΩΕ</th>
						<th class="text-left">Δελτίο Αποστολής</th>
						<th class="text-left">Διακριτός Τίτλος Παραλαβής</th>
                        <th class="text-left">Εισαγόμενα Προϊόντα</th>
                        <!-- <th class="text-left">Προϊόντα</th> -->

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($imports as $import)
                    <tr class="user-row" data-iid="{{ $import->id }}">  <!-- necessary additions -->
                        <td>[{{ $import->import_assignment->import_assignment_code }}] <br/>
                            [{{ $import->import_assignment->warehouse->name }}] <br/>
                            [{{ $import->import_assignment->import_deadline->isoFormat('llll') }}]</td>
						<td>{{ $import->employee->user->name }}</td>
                        <td>{{ $import->company->name }}</td>
						<td>{{ $import->delivered_on->format('l d/m/Y @ H:i') }}</td>
						<td>{{ $import->vehicle_reg_no }}</td>
						<td>{{ $import->transport->name }}</td>
						<td>{{ $import->delivery_address }}</td>
						<td>{{ $import->hours_worked }}/{{ $import->chargeable_hours_worked }}</td>

						<!-- <td>{{ substr(basename($import->shipment_bulletin), 15) }}</td> -->
                        <td>
                            @if(\Auth::user()->user_type == 'super_admin')
                                @if(substr($import->shipment_bulletin, -3) == 'pdf')
                                <a href="{{ route('admin.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @elseif((substr($import->shipment_bulletin, -3) == 'doc') or (substr($import->shipment_bulletin, -4) == 'docx'))
                                <a href="{{ route('admin.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @elseif(substr($import->shipment_bulletin, -3) == 'txt')
                                <a href="{{ route('admin.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @else
                                <a href="{{ route('admin.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>
                                @endif
                            @endif


                            @if(\Auth::user()->user_type == 'company_ceo')
                                @if(substr($import->shipment_bulletin, -3) == 'pdf')
                                <a href="{{ route('manager.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @elseif((substr($import->shipment_bulletin, -3) == 'doc') or (substr($import->shipment_bulletin, -4) == 'docx'))
                                <a href="{{ route('manager.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @elseif(substr($import->shipment_bulletin, -3) == 'txt')
                                <a href="{{ route('manager.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @else
                                <a href="{{ route('manager.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>
                                @endif
                            @endif




                            @if(\Auth::user()->user_type == 'accountant')
                                @if(substr($import->shipment_bulletin, -3) == 'pdf')
                                <a href="{{ route('accountant.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @elseif((substr($import->shipment_bulletin, -3) == 'doc') or (substr($import->shipment_bulletin, -4) == 'docx'))
                                <a href="{{ route('accountant.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @elseif(substr($import->shipment_bulletin, -3) == 'txt')
                                <a href="{{ route('accountant.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @else
                                <a href="{{ route('accountant.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>
                                @endif
                            @endif

                        </td>


						<td>{{ $import->discrete_description }}</td>
                        <!-- <td>{{ $import->product_id }}</td> -->
                        <!-- <td>{{ $import->import_assignment->import_assignment_text }}</td> -->

                        <td>
                        <ul>
                            @foreach($import->products as $product)
                                <li>{{ $product->name }}:&nbsp;{{ $product->pivot->quantity }}&nbsp;{{ $product->measureunit->name }}</li>
                            @endforeach
                        </ul>
                        </td>

                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-iid="{{ $import->id }}"
									data-employeeid="{{ $import->employee_id }}"
                                    data-employeesall="{{ $employees }}"
                                    data-usersall="{{ $users }}"
                                    data-warehousesall="{{ $warehouses }}"
                                    data-warehouseid="{{ $import->import_assignment->warehouse_id }}"
                                    data-employeesperwarehouse="{{ $employees_per_warehouse }}"
									data-companyid="{{ $import->company_id }}"
									data-deliveredon="{{ $import->delivered_on->format('d-m-Y H:i') }}"
									data-vehicleregno="{{ $import->vehicle_reg_no }}"
									data-transportid="{{ $import->transport_id }}"
									data-deliveryaddress="{{ $import->delivery_address }}"
									data-chargeablehours="{{ $import->chargeable_hours_worked }}"
									data-hours="{{ $import->hours_worked }}"
									data-bulletin="{{ basename($import->shipment_bulletin) }}"
									data-description="{{ $import->discrete_description }}"
                                    data-importassignmentid="{{ $import->importassignment_id }}"
                                    data-importassignment="{{ $import->import_assignment }}"
                                    data-iacode="{{ $import->import_assignment->import_assignment_code }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-iid="{{ $import->id }}"
                                    data-iacode="{{ $import->import_assignment->import_assignment_code }}"
                                    data-warehouse="{{ $import->import_assignment->warehouse->name }}"
                                    data-deliveredon="{{ $import->delivered_on->format('l, d-m-Y H:i') }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New User button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal" id="add-import-btn">Προσθήκη Στοιχείων Ανάθεσης Εισαγωγής</button>

            <br/><br/>
            @endcanany <!-- isSuperAdmin, isCompanyCEO, isAccountant -->





            @canany(['isWarehouseForeman', 'isWarehouseWorker'])
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Ανάθεση Εισαγωγής</th>
                        <th class="text-left">Υπεύθυνος Παραλαβής</th>
                        <th class="text-left">Εταιρεία Εισαγωγής</th>
                        <th class="text-left">Ημ/νία &amp; Ώρα Παραλαβής</th>
                        <th class="text-left">Αρ.Κυκλ. Μεταφ.Μέσου</th>
                        <th class="text-left">Μεταφορική Εταιρεία</th>
						<th class="text-left">Τόπος Αποστολής</th>
						<th class="text-left">ΩΕ/ ΧρΩΕ</th>
						<th class="text-left">Δελτίο Αποστολής</th>
						<th class="text-left">Διακριτός Τίτλος Παραλαβής</th>
                        <th class="text-left">Εισαγόμενα Προϊόντα</th>
                        <!-- <th class="text-left">Προϊόντα</th> -->

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($imports as $import)
                @foreach($importassignments as $importassignment)
                @foreach($warehouses as $warehouse)
                @foreach($warehouse->employees as $employee)

                    @if(($import->importassignment_id == $importassignment->id)
                     && ($importassignment->warehouse_id == $warehouse->id)
                     && (\Auth::user()->id == $employee->user_id)
                     && (($employee->user->user_type == 'warehouse_foreman') || ($employee->user->user_type == 'warehouse_worker')))


                    <tr class="user-row" data-iid="{{ $import->id }}">  <!-- necessary additions -->
                        <td>[{{ $import->import_assignment->import_assignment_code }}] <br/>
                            [{{ $import->import_assignment->warehouse->name }}] <br/>
                            [{{ $import->import_assignment->import_deadline->isoFormat('llll') }}]</td>
						<td>{{ $import->employee->user->name }}</td>
                        <td>{{ $import->company->name }}</td>
						<td>{{ $import->delivered_on->format('l d/m/Y @ H:i') }}</td>
						<td>{{ $import->vehicle_reg_no }}</td>
						<td>{{ $import->transport->name }}</td>
						<td>{{ $import->delivery_address }}</td>
						<td>{{ $import->hours_worked }}/{{ $import->chargeable_hours_worked }}</td>

						<!-- <td>{{ substr(basename($import->shipment_bulletin), 15) }}</td> -->
                        <td>

                            @if(\Auth::user()->user_type == 'warehouse_foreman')
                                @if(substr($import->shipment_bulletin, -3) == 'pdf')
                                <a href="{{ route('foreman.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @elseif((substr($import->shipment_bulletin, -3) == 'doc') or (substr($import->shipment_bulletin, -4) == 'docx'))
                                <a href="{{ route('foreman.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @elseif(substr($import->shipment_bulletin, -3) == 'txt')
                                <a href="{{ route('foreman.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @else
                                <a href="{{ route('foreman.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>
                                @endif
                            @endif


                            @if(\Auth::user()->user_type == 'warehouse_worker')
                                @if(substr($import->shipment_bulletin, -3) == 'pdf')
                                <a href="{{ route('worker.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @elseif((substr($import->shipment_bulletin, -3) == 'doc') or (substr($import->shipment_bulletin, -4) == 'docx'))
                                <a href="{{ route('worker.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @elseif(substr($import->shipment_bulletin, -3) == 'txt')
                                <a href="{{ route('worker.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>

                                @else
                                <a href="{{ route('worker.imports.deltio.download', ['filename' => basename($import->shipment_bulletin)]) }}" download>
                                    <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                </a>&nbsp;{{ substr(basename($import->shipment_bulletin), 15) }}<br/>
                                @endif
                            @endif
                        </td>


						<td>{{ $import->discrete_description }}</td>
                        <!-- <td>{{ $import->product_id }}</td> -->
                        <!-- <td>{{ $import->import_assignment->import_assignment_text }}</td> -->

                        <td>
                        <ul>
                            @foreach($import->products as $product)
                                <li>{{ $product->name }}:&nbsp;{{ $product->pivot->quantity }}&nbsp;{{ $product->measureunit->name }}</li>
                            @endforeach
                        </ul>
                        </td>

                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-iid="{{ $import->id }}"
									data-employeeid="{{ $import->employee_id }}"
                                    data-employeesall="{{ $employees }}"
                                    data-usersall="{{ $users }}"
                                    data-warehousesall="{{ $warehouses }}"
                                    data-warehouseid="{{ $import->import_assignment->warehouse_id }}"
                                    data-employeesperwarehouse="{{ $employees_per_warehouse }}"
									data-companyid="{{ $import->company_id }}"
									data-deliveredon="{{ $import->delivered_on->format('d-m-Y H:i') }}"
									data-vehicleregno="{{ $import->vehicle_reg_no }}"
									data-transportid="{{ $import->transport_id }}"
									data-deliveryaddress="{{ $import->delivery_address }}"
									data-chargeablehours="{{ $import->chargeable_hours_worked }}"
									data-hours="{{ $import->hours_worked }}"
									data-bulletin="{{ basename($import->shipment_bulletin) }}"
									data-description="{{ $import->discrete_description }}"
                                    data-importassignmentid="{{ $import->importassignment_id }}"
                                    data-importassignment="{{ $import->import_assignment }}"
                                    data-iacode="{{ $import->import_assignment->import_assignment_code }}">
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-iid="{{ $import->id }}"
                                    data-iacode="{{ $import->import_assignment->import_assignment_code }}"
                                    data-warehouse="{{ $import->import_assignment->warehouse->name }}"
                                    data-deliveredon="{{ $import->delivered_on->format('l, d-m-Y H:i') }}">
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
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal" id="add-import-btn">Προσθήκη Στοιχείων Ανάθεσης Εισαγωγής</button>

            <br/><br/>

            @endcanany
            <!-- 'isWarehouseForeman', 'isWarehouseWorker' -->





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

            <!-- the Add/Create new Import Assignment, Modal popup window -->
            <div class="modal fade" id="add-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Προσθήκη Στοιχείων Ανάθεσης Εισαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="add-form" class="form-horizontal" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('POST')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <!-- this where the errors will be produced and shown -->
                            <div class="alert alert-danger" style="display:none" role="alert">
                            </div>


                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Προσθήκη Προϊόντος</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-iid-create" name="modal-input-iid-create" value="">

                                    <!-- also another id, for the warehouse this time -->
                                    <!-- <input type="hidden" id="modal-input-whid-create" name="modal-input-whid-create" value=""> -->


                                    <!-- import_assignment -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-importassignment-create">Ανάθεση Εισαγωγής</label>
                                        <div class="col-lg-9">
                                            <!--
                                            <input type="text" name="modal-input--create" class="form-control" id="modal-input--create"
                                                value="" />
                                            -->
                                            <select name="modal-input-importassignment-create" id="modal-input-importassignment-create" class="form-control">
                                            @foreach($importassignments as $impassgnm)
                                                <option value="{{ $impassgnm->id }}">[{{ $impassgnm->import_assignment_code }}]&nbsp;/&nbsp;[{{ $impassgnm->warehouse->name }}], [{{ $impassgnm->import_deadline->isoFormat('llll') }}]</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div>
									<!-- /import_assignment -->

									<!-- recipient name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-recipient-create">Υπεύθυνος Παραλαβής</label>
                                        <div class="col-lg-9">
                                        <!-- <input type="text" name="modal-input-recipient-create" class="form-control" id="modal-input-recipient-create"
                                                value="" />  -->
                                        <select name="modal-input-recipient-create" id="modal-input-recipient-create" class="form-control">

                                        </select>
                                        </div>
									</div>
									<!-- /recipient name -->

									<!-- import company name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-impco-create">Eταιρεία Εισαγωγής</label>
                                        <div class="col-lg-9">
                                        <!--
                                            <input type="text" name="modal-input-impco-create" class="form-control" id="modal-input-impco-create"
                                                value="" />
                                        -->
                                            <select name="modal-input-impco-create" id="modal-input-impco-create" class="form-control">
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div>
									<!-- /import company name -->

									<!-- date_time_delivered_on -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtdeliv-create">Ημ/νία &amp; Ώρα Παραλαβής</label>
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

									<!-- destination -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-destin-create">Τόπος Αποστολής</label>
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
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-bulletin-create">Δελτίο Αποστολής [αρχείο PDF]
                                        <i class="fas fa-paperclip text-danger" aria-hidden="true"></i></label>
                                        <div class="col-lg-9">
                                            <input type="file" name="modal-input-bulletin-create" class="form-control" id="modal-input-bulletin-create"
                                                value="" />
                                        </div>
									</div>
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
                                    <!--
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-products-create">Προϊόντα</label>
                                        <div class="col-lg-9">
                                    -->
                                            <!--
                                            <input type="text" name="modal-input--create" class="form-control" id="modal-input--create"
                                                value="" />
                                            -->
                                    <!--
                                            <select name="modal-input-products-create[]" id="modal-input-products-create" class="form-control" multiple="multiple">
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div>
                                    -->
									<!-- /products -->

                                    <hr/>


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
                            <button type="submit" class="btn btn-primary" id="add-button" name="add-import-button"
                                data-target="#add-modal">Πρόσθεσε Στοιχεία Ανάθεσης Εισαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end add/create form -->

                    </div>
                </div>
            </div>



            <!-- the Edit/Update existing ImportAssignment, Modal popup window -->
            <div class="modal fade" id="edit-modal">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Μεταβολή Στοιχείων Ανάθεσης Εισαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>

                        <form id="edit-form" class="form-horizontal" method="POST" enctype="multipart/form-data" novalidate>
                        @csrf <!-- necessary fields for CSRF & Method type-->
                        @method('PUT')

                        <!-- Modal body -->
                        <div class="modal-body">

                            <!-- this where the errors will be produced and shown -->
                            <div class="alert alert-danger" style="display:none" role="alert">
                            </div>


                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Μεταβολή Ανάθεσης Εισαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">

                                    <!-- added hidden input for ID -->
                                    <input type="hidden" id="modal-input-iid-edit" name="modal-input-iid-edit" value="">

                                    <!-- added hidden input for warehouse, I need it for the AJAX to get employees in edit modal -->
                                    <input type="hidden" id="modal-input-warehouse-edit" name="modal-input-warehouse-edit" value="">

                                    <!-- added hidden input -->
                                    <input type="hidden" id="modal-input-importassignmentid-edit" name="modal-input-importassignmentid-edit" value="">


                                    <!-- import_assignment -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-importassignment-edit">Ανάθεση Εισαγωγής</label>
                                        <div class="col-lg-9">
                                        <!--
                                            <input type="text" name="modal-input-exportassignment-edit" class="form-control-plaintext"
                                                id="modal-input-importassignment-edit" value="" readonly />
                                        -->
                                            <select name="modal-input-importassignment-edit" id="modal-input-importassignment-edit" class="form-control mia-edt">
                                            @foreach($importassignments as $impassgnm)
                                                <option value="{{ $impassgnm->id }}">[{{ $impassgnm->import_assignment_code }}]&nbsp;/&nbsp;[{{ $impassgnm->warehouse->name }}], [{{ $impassgnm->import_deadline->isoFormat('llll') }}]</option>
                                            @endforeach
                                            </select>

                                        </div>
									</div>
									<!-- /import_assignment -->

									<!-- recipient name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-recipient-edit">Υπεύθυνος Παραλαβής</label>
                                        <div class="col-lg-9">
                                            <!--
                                            <input type="text" name="modal-input-recipient-edit" class="form-control" id="modal-input-recipient-edit"
                                                value="" />
                                            -->
                                            <select name="modal-input-recipient-edit" id="modal-input-recipient-edit" class="form-control">
                                            @foreach($employees as $emp)
                                                <option value="{{ $emp->id }}">{{ $emp->user->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div>
									<!-- /recipient name -->

									<!-- import company name -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-impco-edit">Eταιρεία Εισαγωγής</label>
                                        <div class="col-lg-9">
                                        <!--
                                            <input type="text" name="modal-input-impco-edit" class="form-control" id="modal-input-impco-edit"
                                                value="" />
                                        -->
                                            <select name="modal-input-impco-edit" id="modal-input-impco-edit" class="form-control">
                                            @foreach($companies as $company)
                                                <option value="{{ $company->id }}">{{ $company->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div>
									<!-- /import company name -->

									<!-- date_time_delivered_on -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-dtdeliv-edit">Ημ/νία &amp; Ώρα Παραλαβής</label>
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

									<!-- destination -->
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-destin-edit">Τόπος Αποστολής</label>
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
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-bulletin-edit">Δελτίο Αποστολής [αρχείο PDF]
                                        <i class="fas fa-paperclip text-danger" aria-hidden="true"></i></label>
                                        <div class="col-lg-9">
                                            <span id="arxeio-DA"></span>
                                            <input type="file" name="modal-input-bulletin-edit" class="form-control" id="modal-input-bulletin-edit"
                                                value="" />
                                        </div>
									</div>
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
                                    <!--
									<div class="form-group row">
										<label class="col-form-label col-lg-3 text-right" for="modal-input-products-edit">Προϊόντα</label>
                                        <div class="col-lg-9">
                                            <select name="modal-input-products-edit[]" id="modal-input-products-edit" class="form-control" multiple="multiple">
                                            @foreach($products as $product)
                                                <option value="{{ $product->id }}">{{ $product->name }}</option>
                                            @endforeach
                                            </select>
                                        </div>
									</div>
                                    -->
									<!-- /products -->


                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="edit-button" name="edit-import-button"
                                data-target="#edit-modal" data-iid="">Διόρθωσε Ανάθεση Εισαγωγής</button>
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Ακύρωση</button>
                        </div>

                        </form> <!-- end update/edit form -->

                    </div>
                </div>
            </div>




            <!-- the Delete existing Import Assignment, Modal popup window -->
            <div class="modal modal-danger fade" id="delete-modal">
                <div class="modal-dialog modal-xl">
                    <div class="modal-content">

                        <!-- Modal Header -->
                        <div class="modal-header">
                            <h4 class="modal-title">Διαγραφή Ανάθεσης Εισαγωγής</h4>
                            <button type="button" class="close" data-dismiss="modal">&times;</button>
                        </div>


                        <form id="delete-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
                        @csrf
                        @method('DELETE')  <!-- necessary fields for CSRF & Method type-->

                        <!-- Modal body -->
                        <div class="modal-body">

                            <div class="card text-white bg-white mb-0">
                                <!--
                                <div class="card-header">
                                    <h2 class="m-0">Διαγραφή Ανάθεσης Εισαγωγής</h2>
                                </div>
                                -->
                                <div class="card-body">
                                    <p class="text-center">Είστε σίγουρος ότι θέλετε να διαγράψετε τα παρακάτω Στοιχεία Ανάθεσης Εισαγωγής;</p>

                                    <!-- added hidden input for ID -->
                                    <div class="form-group">
                                        <input type="hidden" id="modal-input-iid-del" name="modal-input-iid-del" value="" />
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
									<div class="form-group">
										<label class="col-form-label" for="modal-input-dtdeliv-del">Ημ/νία &amp; Ώρα Παραλαβής</label>
										<input type="text" name="modal-input-dtdeliv-del" class="form-control-plaintext" id="modal-input-dtdeliv-del"
											value="" />
									</div>
									<!-- /date_time_delivered_on -->

                                </div>
                            </div>

                        </div>

                        <!-- Modal footer -->
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-danger" id="delete-button" name="delete-import-button"
                                data-target="#delete-modal" data-toggle="modal" data-iid="">Διέγραψε Στοιχεία Ανάθεσης Εισαγωγής</button>
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
        //NB: I removed both of the following 2 (-products-) from the modals!

        //$('#modal-input-products-create').select2();
        //$('#modal-input-products-edit').select2();


         //configure & initialise the (Import Assignments) DataTable
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
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Στοιχεία Αναθέσεων Εισαγωγής",
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Στοιχεία Αναθέσεων Εισαγωγής",
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Στοιχεία Αναθέσεων Εισαγωγής",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [0, 1, 2, 3, 4, 5, 6, 7, 8, 9]
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


        // $(document).on('click', '#add-prodqty-button-create', function(evt){

        //     evt.preventDefault();
        //     $('.wrapper-newprodqty').append(html1); //add the new div

        //     console.log('evt:: ', evt);


        // });



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

            var iid = button.data('iid'); // Extract info from data-* attributes

            var employeeid = button.data('employeeid');
            //var employeesall = button.data('employeesall');
            //var usersall = button.data('usersall');
            var employeesperwarehouse = button.data('employeesperwarehouse');
            var warehousesall = button.data('warehousesall');
            var employeenames = button.data('employeenames');
            var warehouseid = button.data('warehouseid');

            var companyid = button.data('companyid');
            var deliveredon = button.data('deliveredon');
            var vehicleregno = button.data('vehicleregno');
            var transportid = button.data('transportid');
            var deliveryaddress = button.data('deliveryaddress');
            var chargeablehours = button.data('chargeablehours');
            var hours = button.data('hours');
            var bulletin = button.data('bulletin');
            var description = button.data('description');
            var importassignmentid = button.data('importassignmentid');
            var import_assignment = button.data('importassignment');
            var iacode = button.data('iacode');
            //var products = button.data('productid');

            // If necessary, you could initiate an AJAX request here (and then do the updating in a callback).
            // Update the modal's content. We'll use jQuery here, but you could use a data binding library or other methods instead.

            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.card-body #modal-input-iid-edit').val(iid);
            modal.find('.modal-body #modal-input-warehouse-edit').val(warehouseid); //hidden input in edit modal.

            modal.find('.modal-body #modal-input-recipient-edit').val(employeeid);
            modal.find('.modal-body #modal-input-importassignmentid-edit').val(importassignmentid); //hidden input

            modal.find('.modal-body #modal-input-impco-edit').val(companyid);
            modal.find('.modal-body #modal-input-dtdeliv-edit').val(deliveredon);
            modal.find('.modal-body #modal-input-vehicleregno-edit').val(vehicleregno);
            modal.find('.modal-body #modal-input-shipco-edit').val(transportid);
            modal.find('.modal-body #modal-input-destin-edit').val(deliveryaddress);
            modal.find('.modal-body #modal-input-chargehrs-edit').val(chargeablehours);
            modal.find('.modal-body #modal-input-hours-edit').val(hours);


            // modal.find('.modal-body #modal-input-bulletin-edit').val(bulletin); //this very line caused the ERROR of
                                                                                   // bootstrap update modal NOT showing up!!
                                                                                   //for security reasons!! input type=file!!!!
                                                                                   //hence cannot do this!


            modal.find('.modal-body #modal-input-dtitle-edit').val(description);
            // modal.find('.modal-body #modal-input-importassignment-edit').val(importassignmentid);
            //modal.find('.modal-body #modal-input-products-edit').val(products);

            modal.find('.modal-body #arxeio-DA').empty();
            modal.find('.modal-body #arxeio-DA').append('<li>' + base_name(bulletin) + '</li>');

            modal.find('.modal-footer #edit-button').attr("data-iid", iid);  //SET import assignment id value in data-iid attribute


            //EMPTY the recipients for now. they will be populated with ajax
            modal.find('.modal-body #modal-input-recipient-edit').empty();

            //Export assignment name!
            console.log('im_as: ' ,import_assignment); //array of i_a?
            // modal.find('.modal-body #modal-input-importassignment-edit').empty();
            //console.log(export_assignment);
            // modal.find('.modal-body #modal-input-importassignment-edit').val('['+import_assignment.import_assignment_code+']/['+import_assignment.warehouse.name +'],[' + import_assignment.import_deadline+']');
            modal.find('.modal-body #modal-input-importassignment-edit').val(importassignmentid);


            // $.each(import_assignment, function(key,val){
            //     // console.log('key: ', key);
            //     // console.log('value: ', val);
            //     if()

            // });

            // $('.mia-edt').each(function(k,v){
            //     console.log('key: ', k);
            //     console.log('value: ', v);
            //     console.log('$thisVAL: ', $(this).val());
            //     //modal.find($(this)).append();
            // });


            //console.log('emps_per_wh: ', employeesperwarehouse);
            //console.log('emp_names: ', employeenames);
            //console.log('employee_id: ', employeeid);
            //console.log('warehouse_id: ', warehouseid);

            //console.log('Event: ', event);
            //var whid = event.target.value;
            //console.log('warehouseid', whid);
            //console.log(employeesall);
            //console.log(usersall);

            $.each(employeesperwarehouse, function(key, val){
                //console.log('eval: ', val);

                if((warehouseid == val.warehouse_id) && (employeeid == val.id)){
                    //console.log('emp => val_id: '+ val.id +'val_warehouseid'+ val.warehouse_id);
                    modal.find('.modal-body #modal-input-recipient-edit').append('<option selected value="'+ val.id +'">' + val.name + '</option>');
                } else if (warehouseid == val.warehouse_id){
                    //console.log('(else) emp => val_id: '+ val.id +'val_warehouse_id'+ val.warehouse_id);
                    modal.find('.modal-body #modal-input-recipient-edit').append('<option value="'+ val.id +'">' + val.name + '</option>');
                }
            });





            //AJAX Update/Edit User
            //event delegation here...
            $(document).on("submit", "#edit-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(iid);
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
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/imports/update/" + iid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διόρθωση Στοιχείων Ανάθεσης Εισαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent PUT Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/imports/view/";
                            }
                        });
                    },
                    error: function(xhr){
                        console.log('Error:', xhr);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(xhr.status == 500){
                            msg = 'Τα Στοιχεία Ανάθεσης Εισαγωγής υπάρχουν ήδη!';
                        } else if (xhr.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διόρθωσης Στοιχείων Ανάθεσης Εισαγωγής!';
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

            var iid = button.data('iid'); // Extract info from data-* attributes
            var dtdeliv = button.data('deliveredon');
            var warehouse = button.data('warehouse');
            var iacode = button.data('iacode');


            var modal = $(this);
            //modal.find('.modal-title').text('New message to ' + recipient);
            //modal.find('.modal-body .card .card-body #modal-input-iid-del').val(iid); //change the value to...
            modal.find('.modal-body .card .card-body #modal-input-dtdeliv-del').val(dtdeliv);
            modal.find('.modal-body .card .card-body #modal-input-warehouse-del').val(warehouse);
            modal.find('.modal-body .card .card-body #modal-input-code-del').val(iacode);

            modal.find('.modal-footer #delete-button').attr("data-iid", iid); //SET user id value in data-iid attribute


            //AJAX Delete existing Product
            //event delegation here..
            $(document).on("submit", "#delete-form", function(evt){
                evt.preventDefault();
                var formData = new FormData(this);

                console.log(iid);
                console.log(formData);

                $.ajax({
                    method: "POST",
                    data: formData,
                    cache: false,
                    contentType: false, //do not set any content type header
                    processData: false, //send non-processed data
                    dataType: "json",
                    url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/imports/delete/" + iid, //where to send the ajax request
                    success: function(){
                        Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Διαγραφή Στοιχείων Ανάθεσης Εισαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent DELETE Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/imports/view/";
                            }
                        });
                    },
                    error: function(xhr){
                        console.log('Error:', xhr);

                        var msg = 'Συνέβη κάποιο λάθος!';

                        if(xhr.status == 500){
                            msg = 'Τα Στοιχεία Ανάθεσης Εισαγωγής υπάρχουν ήδη!';
                        } else if (xhr.status == 403){
                            msg = 'Δεν έχετε to δικαίωμα διαγραφής Στοιχείων Ανάθεσης Εισαγωγής!';
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

            //reset the error field(s).
            $('.alert-danger').hide();
            $('.alert-danger').html('');




            //Need to do some Checks here for the Products? No, ONLY in the exports info.

            $.ajax({
                method: "POST",
                data: formData,
                cache: false,
                contentType: false, //do not set any content type header
                processData: false, //send non-processed data
                dataType: "json",
                url: "{{ url(request()->route()->getPrefix()) }}" + "/assignments/imports/create/", //where to send the ajax request
                success: function(){
                    Swal.fire({
                            icon: "success",
                            type: "success",
                            text: "Επιτυχής Δημιουργία Στοιχείων Ανάθεσης Εισαγωγής!",
                            buttons: [false, "OK"],
                            closeOnClickOutside: false, //Decide whether the user should be able to dismiss the modal by clicking outside of it, or not. Default=true.
                        }).then(function(isConfirm){
                            if (isConfirm){
                                console.log("Sent POST Request ..");
                                window.location.href = "{{ url(request()->route()->getPrefix()) }}" + "/assignments/imports/view/";
                            }
                        });
                },
                error: function(xhr){
                    console.log('Error:', xhr);

                    var msg = 'Συνέβη κάποιο λάθος!';

                    if(xhr.status == 500){
                        msg = 'Τα Στοιχεία Ανάθεσης Εισαγωγής υπάρχουν ήδη!';
                    } else if (xhr.status == 403){
                        msg = 'Δεν έχετε to δικαίωμα δημιουργίας Στοιχείων Ανάθεσης Εισαγωγής!';
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
        $(document).on('change', '#modal-input-importassignment-create', function(evt){
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
                    url: '{{ url(request()->route()->getPrefix()) }}' + '/assignments/imports/warehouse/' + wh_id,

                    success: function(data){

                        console.log('Data: ',data);

                        // $('#modal-input-recipient-create').empty();
                        // $.each(data, function(key, value){

                        //     $('#modal-input-recipient-create').append('<option value="'+ value.id +'">'+ value.name +'</option>');
                        //     //console.log('key='+key+ ', value='+value);
                        // });
                        $('.modal-body #modal-input-recipient-create').empty();
                        $('.modal-body #modal-input-prod-create').empty();


                        $.each(data, function(key, value){

                            console.log('data_again', data);
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

                        // <<here>> to add create & delete divs as NESTED functions,
                        // so that they receive the DATA parameter from success function!

                        var product_count = data[1].length; //{{ count($products_in_wh) }}; //
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
        $(document).on('change', '#modal-input-importassignment-edit', function(evt){
            var wh_id = evt.target.value;
            //var data = evt.params.data;

            if(wh_id){
                $.ajax({
                    method: "GET",
                    dataType: "json",
                    url: '{{ url(request()->route()->getPrefix()) }}' + '/assignments/imports/warehouse/' + wh_id,

                    success: function(data){

                        console.log('Data : ', data);

                        $('#modal-input-recipient-edit').empty();

                        $.each(data, function(key, value){

                            $('#modal-input-recipient-edit').append('<option value="'+ value.id +'">'+ value.name +'</option>');

                            //console.log('key='+key+ ', value='+value);
                        });
                    },

                });
            } else {
                $('#modal-input-recipient-edit').empty();
            }
        });



        //necessary additions for when the modals get hidden

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




        $('#edit-modal').on('hidden.bs.modal', function(e){
            $(document).off('submit', '#edit-form');
        });

        $('#delete-modal').on('hidden.bs.modal', function(e){
            $(document).off('submit', '#delete-form');
        });

        //resets the create/add form. Re-use this code snippet in other blade views!
        $(document).on('click', '[data-dismiss="modal"]', function(e){
            $('#add-form').find("input,textarea,select").val('');

            //reset the error field(s) also
            $('.alert-danger').hide();
            $('.alert-danger').html('');
        });


        $('#add-import-btn').on('click', function(evt){
            $('#add-form').find('select').val('');
        });


    </script>
@stop
