{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Dashboard')

@section('content_header')
    <h1>Warehouse / Ανάθεση Εισαγωγής</h1>
@stop


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}"> <!-- CSRF token, necessary addition for $.ajax() in jQuery -->

    <div class="row">
        <div class="col-lg-4 col-xs-6">

            <p>Φόρμα Ανάθεσης Εισαγωγής (Import Assignment)</p>


            <form id="create-import-form" class="form-horizontal" method="POST">
            @csrf
            @method('POST')

                    <!-- recipient name -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-recipient-create">Υπεύθυνος Παραλαβής</label>
                        <input type="text" name="modal-input-recipient-create" class="form-control" id="modal-input-recipient-create"
                            value="" required />
                    </div>
                    <!-- /recipient name -->

                    <!-- import company name -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-impco-create">Eταιρεία Εισαγωγής</label>
                        <input type="text" name="modal-input-impco-create" class="form-control" id="modal-input-impco-create"
                            value="" required />
                    </div>
                    <!-- /import company name -->

                    <!-- date_time_delivered_on -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-dtdeliv-create">Ημ/νία &amp; Ώρα Παραλαβής</label>
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

                    <!-- destination -->
                    <div class="form-group">
                        <label class="col-form-label" for="modal-input-destin-create">Τόπος Αποστολής</label>
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

                    <button type="submit" class="btn btn-primary"   id="create-imp-button" name="create-import-button">Καταχώρηση Ανάθεσης Εισαγωγής</button>
                    <button type="reset"  class="btn btn-secondary" id="reset-imp-button" name="reset-import-button">Reset Φόρμας</button>



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
