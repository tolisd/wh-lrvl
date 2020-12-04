{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη | Κλειστές Αναθέσεις Εισαγωγής')

@section('content_header')
    <h1>Αποθήκη/Warehouse | Κλειστές Αναθέσεις Εισαγωγής</h1>
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

            <p>Κλειστές Αναθέσεις Εισαγωγής</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])

			<!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Όνομα Αναθέτη</th>
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
                        <td>{{ $importassignment->user->name }}</td>
                        <td>{{ $importassignment->import_assignment_code }}</td>
                        <td>{{ $importassignment->warehouse->name }}</td>
                        <td>{{ $importassignment->import_assignment_text }}</td>

                        <!-- <td>{{ $importassignment->import_deadline->format('l d/m/Y @ H:i') }}</td> -->
                        <td>{{ $importassignment->import_deadline->isoFormat('llll') }}</td>

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
                                        <a href="{{ route('admin.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('admin.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('admin.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('admin.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>
                                        @endif
                                    @endif


                                    @if(\Auth::user()->user_type == 'company_ceo')
                                        @if(substr($att_file, -3) == 'pdf')
                                        <a href="{{ route('manager.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('manager.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('manager.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('manager.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>
                                        @endif
                                    @endif


                                    @if(\Auth::user()->user_type == 'accountant')
                                        @if(substr($att_file, -3) == 'pdf')
                                        <a href="{{ route('accountant.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('accountant.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('accountant.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('accountant.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
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
                                <i class="fas fa-lock" aria-hidden="true"></i>&nbsp;Κλειστή
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>


            <br/><br/>
            @endcanany <!-- isSuperAdmin, isCompanyCEO, isAccountant -->





            @canany(['isNormalUser'])

            <!-- insert here the main products table-->
            <table class="table data-table display table-striped table-bordered"
                    data-order='[[ 0, "asc" ]]' data-page-length="10">
                <thead>
                    <tr>
                        <th class="text-left">Όνομα Αναθέτη</th>
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
                        <td>{{ $importassignment->user->name }}</td>
                        <td>{{ $importassignment->import_assignment_code }}</td>
                        <td>{{ $importassignment->warehouse->name }}</td>
                        <td>{{ $importassignment->import_assignment_text }}</td>

                        <!-- <td>{{ $importassignment->import_deadline->format('l d/m/Y @ H:i') }}</td> -->
                        <td>{{ $importassignment->import_deadline->isoFormat('llll') }}</td>

                        @php
                            $attached_files = json_decode($importassignment->uploaded_files, true);
                        @endphp

                        <td>
                            @if($attached_files == null)
                                <i class="fas fa-file fa-lg" aria-hidden="true"></i>&nbsp;Χωρίς αρχείο
                            @else
                                @foreach($attached_files as $att_file)

                                    @if(\Auth::user()->user_type == 'normal_user')

                                        @if(substr($att_file, -3) == 'pdf')
                                        <a href="{{ route('user.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif((substr($att_file, -3) == 'doc') or (substr($att_file, -4) == 'docx'))
                                        <a href="{{ route('user.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @elseif(substr($att_file, -3) == 'txt')
                                        <a href="{{ route('user.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
                                            <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                        </a>&nbsp;{{ substr(basename($att_file), 15) }}<br/>

                                        @else
                                        <a href="{{ route('user.assignments.import.close.getfiles', ['filenames' => basename($att_file)]) }}" download>
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
                                <i class="fas fa-lock" aria-hidden="true"></i>&nbsp;Κλειστή
                            @endif
                        </td>

                    </tr>
                @endforeach
                </tbody>

            </table>




            <br/><br/>
            @endcanany <!-- isNormalUser -->





			@can('isSuperAdmin')
                <a href="{{ route('admin.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isCompanyCEO')
                <a href="{{ route('manager.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isAccountant')
                <a href="{{ route('accountant.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isNormalUser')
                <a href="{{ route('normaluser.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
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
                                columns: [ 0, 1, 2, 3, 4, 5,6,7]
                            }
                        },
                        {
                            "extend" : "csv",
                            "text"   : "Εξαγωγή σε CSV",
                            "title"  : "Κλειστές Αναθέσεις Εισαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Κλειστές Αναθέσεις Εισαγωγής",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Κλειστές Αναθέσεις Εισαγωγής",
                            "orientation" : "landscape",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7]
                            }
                        },
                        {
                            "extend" : "print",
                            "text"   : "Εκτύπωση",
                            exportOptions: {
                                columns: [ 0, 1, 2, 3, 4, 5,6,7]
                            }
                        },
                    ],
        });

    });

    </script>

@stop
