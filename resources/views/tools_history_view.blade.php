{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Ιστορικό Εργαλείων')

@section('content_header')
    <h1>Αποθήκη/Warehouse | Ιστορικό Εργαλείων</h1>
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
                        <th class="text-left">Χρεώθηκε Στις</th>
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
                            <ol>
                            @foreach($charged as $ch)

                                @if((!empty($ch->date))
                                  &&(!empty($ch->whom))
                                  &&(!empty($ch->file)))

                                    @foreach($employees as $emp)
                                        @if($emp->id == $ch->whom)

                                            <li>
                                                 {{ $ch->date ?? 'n/a' }},&nbsp;

                                                 (Από:&nbsp;<strong>{{ $emp->find($ch->from)->user->name ?? 'n/a' }}</strong>,&nbsp;
                                                  Στον:&nbsp;<strong>{{ $emp->find($ch->whom)->user->name ?? 'n/a' }}</strong>),&nbsp;

                                                <!-- xrewstiko arxeio download -->
                                                 @if(\Auth::user()->user_type == 'super_admin')
                                                    @if(substr($ch->file, -3) == 'pdf')
                                                    <a href="{{ route('admin.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                                    </a>&nbsp;{{ $ch->file }}<br/>
                                                    @elseif((substr($ch->file, -3) == 'doc') or (substr($ch->file, -4) == 'docx'))
                                                    <a href="{{ route('admin.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                                    </a>&nbsp;{{ $ch->file }}<br/>
                                                    @elseif(substr($ch->file, -3) == 'txt')
                                                    <a href="{{ route('admin.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                                    </a>&nbsp;{{ $ch->file }}<br/>
                                                    @else
                                                    <a href="{{ route('admin.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file fa-lg" aria-hidden="true"></i>&nbsp;{{ $ch->file }}
                                                    </a>
                                                    @endif
                                                @endif

                                                @if(\Auth::user()->user_type == 'company_ceo')
                                                    @if(substr($ch->file, -3) == 'pdf')
                                                    <a href="{{ route('manager.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                                    </a>&nbsp;{{ $ch->file }}<br/>
                                                    @elseif((substr($ch->file, -3) == 'doc') or (substr($ch->file, -4) == 'docx'))
                                                    <a href="{{ route('manager.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                                    </a>&nbsp;{{ $ch->file }}<br/>
                                                    @elseif(substr($ch->file, -3) == 'txt')
                                                    <a href="{{ route('manager.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                                    </a>&nbsp;{{ $ch->file }}<br/>
                                                    @else
                                                    <a href="{{ route('manager.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file fa-lg" aria-hidden="true"></i>&nbsp;{{ $ch->file }}
                                                    </a>
                                                    @endif
                                                @endif

                                                @if(\Auth::user()->user_type == 'warehouse_foreman')
                                                    @if(substr($ch->file, -3) == 'pdf')
                                                    <a href="{{ route('foreman.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file-pdf fa-lg" aria-hidden="true"></i>
                                                    </a>&nbsp;{{ $ch->file }}<br/>
                                                    @elseif((substr($ch->file, -3) == 'doc') or (substr($ch->file, -4) == 'docx'))
                                                    <a href="{{ route('foreman.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file-word fa-lg" aria-hidden="true"></i>
                                                    </a>&nbsp;{{ $ch->file }}<br/>
                                                    @elseif(substr($ch->file, -3) == 'txt')
                                                    <a href="{{ route('foreman.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file-alt fa-lg" aria-hidden="true"></i>
                                                    </a>&nbsp;{{ $ch->file }}<br/>
                                                    @else
                                                    <a href="{{ route('foreman.tools.download.file', ['filename' => $ch->file]) }}" download>
                                                        <i class="far fa-file fa-lg" aria-hidden="true"></i>&nbsp;{{ $ch->file }}
                                                    </a>
                                                    @endif
                                                @endif


                                            </li>

                                        @endif
                                    @endforeach

                                @endif

                            @endforeach
                            </ol>
                        </td>

                        @php
                            $uncharged = json_decode($th->uncharged_at);
                        @endphp
                        <td>
                            <ol>
                            @foreach($uncharged as $unch)

                            @if(!empty($unch->date))

                                @foreach($employees as $emp)
                                    @if($emp->id == $unch->whom)
                                    <li>
                                        {{ $unch->date ?? 'n/a' }},&nbsp;
                                        Από τον:&nbsp;<strong>{{ $emp->find($unch->whom)->user->name ?? 'n/a'  }}</strong>
                                    </li>
                                    @endif
                                @endforeach

                            @endif

                            @endforeach
                            </ol>
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
                            "title"  : "Ιστορικό Εργαλείων",
                            exportOptions: {
                                columns: [0,1,2,3]
                            }
                        },
                        {
                            "extend" : "excel",
                            "text"   : "Εξαγωγή σε Excel",
                            "title"  : "Ιστορικό Εργαλείων",
                            exportOptions: {
                                columns: [0,1,2,3]
                            }
                        },
                        {
                            "extend" : "pdf",
                            "text"   : "Εξαγωγή σε PDF",
                            "title"  : "Ιστορικό Εργαλείων",
                            "orientation" : "landscape",
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
