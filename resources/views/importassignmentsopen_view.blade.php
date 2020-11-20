{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη | Ανοιχτές Αναθέσεις Εισαγωγής')

@section('content_header')
    <h1>Αποθήκη/Warehouse | Ανοιχτές Αναθέσεις Εισαγωγής</h1>
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

            <p>Ανοιχτές Αναθέσεις Εισαγωγής</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])

			<!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Κωδ.Ανάθεσης</th>
                        <th class="text-left">Αποθήκη</th>
                        <th class="text-left">Κείμενο Ανάθεσης Εισαγωγής</th>
                        <th class="text-left">Deadline</th>
                        <th class="text-left">Επισυναπτόμενα Αρχεία</th>
                        <th class="text-left">Σχόλια</th>
						<th class="text-left">Ανοιχτή?</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($importassignments as $importassignment)
                    <tr class="user-row" data-eid="{{ $importassignment->id }}">  <!-- necessary additions -->
                        <td>{{ $importassignment->import_assignment_code }}</td>
                        <td>{{ $importassignment->warehouse->name }}</td>
                        <td>{{ $importassignment->import_assignment_text }}</td>
                        <td>{{ $importassignment->import_deadline->format('l d/m/Y @ H:i') }}</td>

                        @php
                            $attached_files = json_decode($importassignment->uploaded_files, true);
                        @endphp

                        <td>
                            @if($attached_files == null)
                                <i class="fas fa-file fa-lg" aria-hidden="true"></i>&nbsp;Χωρίς αρχείο
                            @else
                                @foreach($attached_files as $att_file)


                                    @if(\Auth::user()->user_type == 'super_admin')


                                        @if(substr($att_file, -3) == 'pdf')
                                        <a href="{{ route('admin.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('admin.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('admin.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('admin.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>
                                        @endif
                                    @endif


                                    @if(\Auth::user()->user_type == 'company_ceo')
                                        @if(substr($att_file, -3) == 'pdf')
                                        <a href="{{ route('manager.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('manager.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('manager.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('manager.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>
                                        @endif
                                    @endif



                                    <!-- @if(\Auth::user()->user_type == 'warehouse_foreman')
                                        @if(substr($att_file, -3) == 'pdf')
                                        <a href="{{ route('foreman.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('foreman.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('foreman.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('foreman.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>
                                        @endif
                                    @endif -->



                                    @if(\Auth::user()->user_type == 'accountant')
                                        @if(substr($att_file, -3) == 'pdf')
                                        <a href="{{ route('accountant.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('accountant.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('accountant.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('accountant.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>
                                        @endif
                                    @endif



                                    <!-- @if(\Auth::user()->user_type == 'warehouse_worker')
                                        @if(substr($att_file, -3) == 'pdf')
                                        <a href="{{ route('worker.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('worker.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('worker.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('worker.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>
                                        @endif
                                    @endif -->


                                @endforeach
                            @endif
                        </td>

                        <td>{{ $importassignment->comments }}</td>
						<td>
                            @if($importassignment->is_open == 1)
                                <i class="fas fa-lock-open" aria-hidden="true"></i>&nbsp;Ανοιχτή
                            @elseif($importassignment->is_open == 0)
                                <i class="fas fa-lock-open" aria-hidden="true"></i>&nbsp;Κλειστή
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>


            <br/><br/>
            @endcanany <!-- isSuperAdmin, isCompanyCEO, -->



            @canany(['isWarehouseForeman', 'isWarehouseWorker'])

            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Κωδ.Ανάθεσης</th>
                        <th class="text-left">Αποθήκη</th>
                        <th class="text-left">Κείμενο Ανάθεσης Εισαγωγής</th>
                        <th class="text-left">Deadline</th>
                        <th class="text-left">Επισυναπτόμενα Αρχεία</th>
                        <th class="text-left">Σχόλια</th>
						<th class="text-left">Ανοιχτή?</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($importassignments as $importassignment)
                @foreach($warehouses as $warehouse)
                @foreach($warehouse->employees as $employee)

                    @if(($importassignment->warehouse_id == $warehouse->id)
                     && (\Auth::user()->id == $employee->user_id)
                     && (($employee->user->user_type == 'warehouse_foreman') || ($employee->user->user_type == 'warehouse_worker')))

                    <tr class="user-row" data-eid="{{ $importassignment->id }}">  <!-- necessary additions -->
                        <td>{{ $importassignment->import_assignment_code }}</td>
                        <td>{{ $importassignment->warehouse->name }}</td>
                        <td>{{ $importassignment->import_assignment_text }}</td>
                        <td>{{ $importassignment->import_deadline->format('l d/m/Y @ H:i') }}</td>

                        @php
                            $attached_files = json_decode($importassignment->uploaded_files, true);
                        @endphp

                        <td>
                            @if($attached_files == null)
                                <i class="fas fa-file fa-lg" aria-hidden="true"></i>&nbsp;Χωρίς αρχείο
                            @else
                                @foreach($attached_files as $att_file)

                                    @if(\Auth::user()->user_type == 'warehouse_foreman')
                                        @if(substr($att_file, -3) == 'pdf')
                                        <a href="{{ route('foreman.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('foreman.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('foreman.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('foreman.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>
                                        @endif
                                    @endif


                                    @if(\Auth::user()->user_type == 'warehouse_worker')
                                        @if(substr($att_file, -3) == 'pdf')
                                        <a href="{{ route('worker.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('worker.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('worker.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('worker.assignments.import.open.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>
                                        @endif
                                    @endif

                                @endforeach
                            @endif
                        </td>

                        <td>{{ $importassignment->comments }}</td>
						<td>
                            @if($importassignment->is_open == 1)
                                <i class="fas fa-lock-open" aria-hidden="true"></i>&nbsp;Ανοιχτή
                            @elseif($importassignment->is_open == 0)
                                <i class="fas fa-lock-open" aria-hidden="true"></i>&nbsp;Κλειστή
                            @endif
                        </td>

                    </tr>
                    @endif

                @endforeach
                @endforeach
                @endforeach
                </tbody>

            </table>


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
                                columns: [ 0, 1, 2, 3, 4, 5,6]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Ανοιχτές Αναθέσεις Εισαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Ανοιχτές Αναθέσεις Εισαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Ανοιχτές Αναθέσεις Εισαγωγής",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6]
                            }
                        },
                    ],
        });

    });

    </script>

@stop
