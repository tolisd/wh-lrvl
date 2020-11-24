{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Ιστορικό Εργαλείων')

@section('content_header')
    <h1>Warehouse / Ιστορικό Εργαλείων</h1>
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

            <p>Ιστορικό (Χρεώσεων/Ξεχρεώσεων) Εργαλείων</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])
            <!-- insert here the main my charged tools table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Κωδικός Εργαλείου</th>
                        <th class="text-left">Όνομα Εργαλείου</th>
                        <th class="text-left">Χρεώθηκε Στις/Από</th>
                        <th class="text-left">Ξεχρεώθηκε Στις</th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($tools_history_data as $th)
                    <tr>
                        <td>{{ $th->tool->code }}</td>
                        <td>{{ $th->tool->name }}</td>

                        @php
                            $charged = json_decode($th->charged_at);
                        @endphp
                        <td>
                            <ul>
                            @foreach($charged as $ch)
                            @if($ch != null)
                                <li>
                                    {{ $ch->date ?? 'Δ/Υ' }},&nbsp;
                                    {{ $ch->whom ?? 'Δ/Υ' }},&nbsp;
                                    {{ $ch->file ?? 'Δ/Υ' }}
                                </li>
                            @endif
                            @endforeach
                            </ul>
                        </td>

                        @php
                            $uncharged = json_decode($th->uncharged_at);
                        @endphp
                        <td>
                            <ul>
                            @foreach($uncharged as $unch)
                            @if($unch != null)
                                <li>
                                    {{ $unch->date ?? 'Δ/Υ' }}
                                </li>
                            @endif
                            @endforeach
                            </ul>
                        </td>

                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endcanany <!-- isSuperAdmin, isCompanyCEO, isWarehouseForeman -->

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
                            "text"   : "Αντιγραφή",
                            exportOptions: {
                                columns: [0,1,2,3]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Χρεωμένα Εργαλεία",
                            exportOptions: {
                                columns: [0,1,2,3]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Χρεωμένα Εργαλεία",
                            exportOptions: {
                                columns: [0,1,2,3]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Χρεωμένα Εργαλεία",
                            "orientation" : "portrait",
                            exportOptions: {
                                columns: [0,1,2,3]
                            }


                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [0,1,2,3]
                            }
                        },
                    ],
            });

        });


    </script>
@stop
