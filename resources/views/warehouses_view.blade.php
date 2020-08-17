{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Πίνακας Ελέγχου')

@section('content_header')
    <h1>Warehouse / Όλες οι Αποθήκες</h1>
@stop


@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">

            <p>Όλες οι Αποθήκες</p>

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])
            <table class="table data-table display table-striped table-bordered"
                     data-order='[[ 0, "asc" ]]' data-page-length="5">
                <thead>
                    <tr>
                        <th class="text-left">Όνομα</th>
                        <th class="text-left">Διεύθυνση</th>
                        <th class="text-left">Πόλη</th>
                        <th class="text-left">Τηλέφωνο</th>
                        <th class="text-left">E-mail</th>
                        <th class="text-left">Όνομα Εταιρείας</th>

                        <th class="text-left">Μεταβολή</th>
                        <th class="text-left">Διαγραφή</th>
                    </tr>
                </thead>

                <tbody>
                @foreach($warehouses as $warehouse)
                    <tr class="user-row" data-tid="{{ $type->id }}">  <!-- necessary additions -->
                        <td>{{ $warehouse->name }}</td>
                        <td>{{ $warehouse->address }}</td>
                        <td>{{ $warehouse->city }}</td>
                        <td>{{ $warehouse->phone_number }}</td>
                        <td>{{ $warehouse->email }}</td>
                        <td>{{ $warehouse->company->name }}</td>
                        <td>
                            <button class="edit-modal btn btn-info"
                                    data-toggle="modal" data-target="#edit-modal"
                                    data-wid="{{ $warehouse->id }}"
                                    data-name="{{ $warehouse->name }}"
                                    data-address="{{ $warehouse->address }}"
                                    data-city="{{ $warehouse->city }}"
                                    data-telno="{{ $warehouse->phone_number }}"
                                    data-email="{{ $warehouse->email }}"
                                    data-company="{{ $warehouse->company->id }}">  <!-- id instead of name, OR just name? -->
                                <i class="fas fa-edit" aria-hidden="true"></i>&nbsp;Διόρθωση
                            </button>
                        </td>
                        <td>
                            <button class="delete-modal btn btn-danger"
                                    data-toggle="modal" data-target="#delete-modal"
                                    data-tid="{{ $warehouse->id }}"
                                    data-name="{{ $warehouse->name }}">
                                <i class="fas fa-times" aria-hidden="true"></i>&nbsp;Διαγραφή
                            </button>
                        </td>
                    </tr>
                @endforeach
                </tbody>

            </table>

            <br/><br/>

            <!--Create New Products Type button -->
            <button class="btn btn-primary" data-toggle="modal" data-target="#add-modal">Προσθήκη Είδους Προϊόντος</button>

            <br/><br/>
            @endcanany  <!-- ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'] -->






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

        </div>
      </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
