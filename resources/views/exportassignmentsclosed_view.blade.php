{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη | Κλειστές Αναθέσεις Εξαγωγής')

@section('content_header')
    <h1>Αποθήκη/Warehouse | Κλειστές Αναθέσεις Εξαγωγής</h1>
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

            <p>Κλειστές Αναθέσεις Εξαγωγής</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])

			<!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Αποθήκη</th>
                        <th class="text-left">Κείμενο Ανάθεσης Εξαγωγής</th>
                        <th class="text-left">Deadline</th>
                        <th class="text-left">Επισυναπτόμενα Αρχεία</th>
                        <th class="text-left">Σχόλια</th>
						<th class="text-left">Ανοιχτή?</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($exportassignments as $exportassignment)
                    <tr class="user-row" data-eid="{{ $exportassignment->id }}">  <!-- necessary additions -->
                        <td>{{ $exportassignment->warehouse->name }}</td>
                        <td>{{ $exportassignment->export_assignment_text }}</td>
                        <td>{{ $exportassignment->export_deadline->format('l d/m/Y @ H:i') }}</td>

                        @php
                            $attached_files = json_decode($exportassignment->uploaded_files, true);
                        @endphp
                        <td>
                            <ul>
                            @foreach($attached_files as $att_file)
                                <li>{{ basename($att_file) }}</li>
                            @endforeach
                            </ul>
                        </td>
                        <td>{{ $exportassignment->comments }}</td>
						<td>
                            @if($exportassignment->is_open == 1)
                                <i class="fas fa-lock-open" aria-hidden="true"></i>&nbsp;Ανοιχτή
                            @elseif($exportassignment->is_open == 0)
                                <i class="fas fa-lock" aria-hidden="true"></i>&nbsp;Κλειστή
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>



            <br/><br/>
            @endcanany <!-- isSuperAdmin, isCompanyCEO, isAccountant -->


			@can('isSuperAdmin')
                <a href="{{ route('admin.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isCompanyCEO')
                <a href="{{ route('manager.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isAccountant')
                <a href="{{ route('accountant.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan


        </div>
    </div>

@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">

    <!-- <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/limonte-sweetalert2/7.33.1/sweetalert2.min.css" /> -->
@stop


@section('js')

<script type="text/javascript">
    //console.log('Hi!');

    $(document).ready(function(){

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
                                columns: [ 0, 1, 2, 3, 4, 5 ]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Κλειστές Αναθέσεις Εξαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Κλειστές Αναθέσεις Εξαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Κλειστές Αναθέσεις Εξαγωγής",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5]
                            }
                        },
                    ],
        });

    });

    </script>

@stop
