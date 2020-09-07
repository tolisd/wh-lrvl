{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Dashboard')

@section('content_header')
    <h1>Warehouse / Ανάθεση Εξαγωγής</h1>
@stop


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token, necessary addition for $.ajax() in jQuery -->
    <div class="row">
        <div class="col-lg-6 col-xs-6">

            <p>Ανάθεση Εξαγωγής</p>



            <form id="create-export-form" class="form-horizontal" method="POST" enctype="multipart/form-data">
            @csrf
            @method('POST')

                    <!-- recipient name -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-recipient-create">Υπεύθυνος Παράδοσης</label>
                        <input type="text" name="modal-input-recipient-create" class="form-control" id="modal-input-recipient-create"
                            value="" required />
                    </div>
                    <!-- /recipient name -->

                    <!-- import company name -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-expco-create">Eταιρεία Παράδοσης</label>
                        <input type="text" name="modal-input-expco-create" class="form-control" id="modal-input-expco-create"
                            value="" required />
                    </div>
                    <!-- /import company name -->

                    <!-- date_time_delivered_on -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-dtdeliv-create">Ημ/νία &amp; Ώρα Παράδοσης</label>
                        <input type="text" name="modal-input-dtdeliv-create" class="form-control" id="modal-input-dtdeliv-create"
                            value="" required />
                    </div>
                    <!-- /date_time_delivered_on -->

                    <!-- vehicle_registration_no -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-vehicleregno-create">Αρ.Κυκλοφορίας Μεταφορικού Μέσου</label>
                        <input type="text" name="modal-input-vehicleregno-create" class="form-control" id="modal-input-vehicleregno-create"
                            value="" required />
                    </div>
                    <!-- /vehicle_registration_no -->

                    <!-- shipping_company -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-shipco-create">Μεταφορική Εταιρεία</label>
                        <input type="text" name="modal-input-shipco-create" class="form-control" id="modal-input-shipco-create"
                            value="" required />
                    </div>
                    <!-- /shipping_company -->

		    <!-- sendingplace -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-sendplace-create">Τόπος Αποστολής</label>
                        <input type="text" name="modal-input-sendplace-create" class="form-control" id="modal-input-sendplace-create"
                            value="" required />
                    </div>
                    <!-- /sendingplace -->

                    <!-- destination -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-destin-create">Τόπος Προορισμού</label>
                        <input type="text" name="modal-input-destin-create" class="form-control" id="modal-input-destin-create"
                            value="" required />
                    </div>
                    <!-- /destination -->

                    <!-- chargeable_work_hours -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-chargehrs-create">Χρεώσιμες Ώρες Εργασίας</label>
                        <input type="text" name="modal-input-chargehrs-create" class="form-control" id="modal-input-chargehrs-create"
                            value="" required />
                    </div>
                    <!-- /chargeable_work_hours -->

                    <!-- work_hours -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-hours-create">Ώρες Εργασίας</label>
                        <input type="text" name="modal-input-hours-create" class="form-control" id="modal-input-hours-create"
                            value="" required />
                    </div>
                    <!-- /work_hours -->

                    <!-- shipping_bulletin -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-bulletin-create">Δελτίο Αποστολής [αρχείο PDF]</label>
                        <input type="text" name="modal-input-bulletin-create" class="form-control" id="modal-input-bulletin-create"
                            value="" required />
                    </div>
                    <!-- /shipping_bulletin -->

                    <!-- delivery_description -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-dtitle-create">Διακριτός Τίτλος Παραλαβής</label>
                        <input type="text" name="modal-input-dtitle-create" class="form-control" id="modal-input-dtitle-create"
                            value="" required />
                    </div>
                    <!-- /delivery_description -->

                    <button type="submit" class="btn btn-primary"   id="create-exp-button" name="create-export-button">Καταχώρηση Ανάθεσης Εισαγωγής</button>
                    <button type="reset"  class="btn btn-secondary" id="reset-exp-button" name="reset-export-button">Reset Φόρμας</button>



            </form>


            @can('isSuperAdmin')
                <a href="{{ route('admin.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isCompanyCEO')
                <a href="{{ route('manager.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isWarehouseForeman')
                <a href="{{ route('foreman.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isAccountant')
                <a href="{{ route('accountant.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

        </div>
      </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script type="text/javascript">

        //console.log('Hi!');

        $(document).ready(function(){
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
                }
            });
        });

    </script>
@stop
