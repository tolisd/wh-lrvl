{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Πίνακας Ελέγχου')

@section('content_header')
    <h1>Warehouse/Αποθήκη | Προϊόντα Αποθήκης</h1>
@stop


@section('content')
<style>
    .dt-buttons{
        margin-bottom: 10px;
        padding-bottom: 5px;
    }
</style>

    <div class="row">
        <div class="col-lg-12 col-xs-6">

        <p>Προϊόντα Αποθήκης (@foreach($warehouse_data as $wh) <strong>{{ $wh->name }}</strong> @endforeach)</p>

        <p><strong>Προϊστάμενος:</strong> &nbsp;
        @foreach($employees_in_warehouse as $employee)
            @if($employee->user->user_type == 'warehouse_foreman')
                {{ $employee->user->name }}
            @endif
        @endforeach
        </p>

        <strong>Αποθηκάριοι:</strong>
        <ul>
        @foreach($employees_in_warehouse as $employee)
            @if($employee->user->user_type == 'warehouse_worker')
                <li>{{ $employee->user->name }}</li>
            @endif
        @endforeach
        </ul>


            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant'])
            <!-- insert here the main my charged tools table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Κωδικός</th>
                        <th class="text-left">Όνομα Προϊόντος</th>
                        <th class="text-left">Περιγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($warehouse_data as $wh)
                    @foreach($wh->products as $product)
                    <tr>
                        <td>{{ $product->code }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ $product->description }}</td>
                    </tr>
                    @endforeach
                @endforeach
                </tbody>
            </table>
            @endcanany <!-- isWarehouseForeman, isWarehouseWorker -->

            <br/><br/>




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

            //configure & initialise the (My Charged Tools) DataTable
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
                                columns: [0,1,2]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Προϊόντα Αποθήκης",
                            exportOptions: {
                                columns: [0,1,2]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Προϊόντα Αποθήκης",
                            exportOptions: {
                                columns: [0,1,2]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Προϊόντα Αποθήκης",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [0,1,2]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [0,1,2]
                            }
                        },
                    ],
            });
        });


    </script>
@stop
