{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Πίνακας Ελέγχου')

@section('content_header')
    <h1>Warehouse / Χρεωμένα Εργαλεία</h1>
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

            <p>Χρεωμένα Εργαλεία</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])
            <!-- insert here the main my charged tools table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Όνομα Εργαλείου</th>
                        <th class="text-left">Περιγραφή Εργαλείου</th>
                        <th class="text-left">Όνομα Χρεωμένου Χρήστη</th>
                        <th class="text-left">Ημ/νία Χρέωσης</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($charged_tools as $tool)
                    <tr>
                        <td>{{ $tool->name }}</td>
                        <td>{{ $tool->description }}</td>
                        <td>{{ $tool->user->name }}</td>
                        <td>{{ $tool->user->created_at }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endcanany <!-- isSuperAdmin, isCompanyCEO, isWarehouseForeman, isWarehouseWorker -->

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

            @can('isWarehouseWorker')
                <a href="{{ route('worker.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
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

            //configure & initialise the (Charged Tools) DataTable
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
                            "title"  : "Χρεωμένα Εργαλεία"
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Χρεωμένα Εργαλεία"
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Χρεωμένα Εργαλεία"
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση"
                        },
                    ],
            });

        });


    </script>
@stop
