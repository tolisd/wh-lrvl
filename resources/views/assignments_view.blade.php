{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Dashboard')

@section('content_header')
    <h1>Warehouse / Δες τις Ανοιχτές Αναθέσεις</h1>
@stop


@section('content')
    <div class="row">
        <div class="col-lg-3 col-xs-6">

            <p>Ανοιχτές Αναθέσεις</p>


            @can('isSuperAdmin')
                <ul>
                    <li><h4><a href="#">Ανοιχτές Αναθέσεις Εισαγωγής ({{ $imp_assignments_count }})</a></h4></li>
                    <li><h4><a href="#">Ανοιχτές Αναθέσεις Εξαγωγής ({{ $exp_assignments_count }})</a></h4></li>
                </ul>
                <br/><br/>
                <a href="{{ route('admin.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isCompanyCEO')
                <ul>
                    <li><h4><a href="#">Ανοιχτές Αναθέσεις Εισαγωγής ({{ $imp_assignments_count }})</a></h4></li>
                    <li><h4><a href="#">Ανοιχτές Αναθέσεις Εξαγωγής ({{ $exp_assignments_count }})</a></h4></li>
                </ul>
                <br/><br/>
                <a href="{{ route('manager.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isAccountant')
                <ul>
                    <li><h4><a href="#">Ανοιχτές Αναθέσεις Εισαγωγής ({{ $imp_assignments_count }})</a></h4></li>
                    <li><h4><a href="#">Ανοιχτές Αναθέσεις Εξαγωγής ({{ $exp_assignments_count }})</a></h4></li>
                </ul>
                <br/><br/>
                <a href="{{ route('accountant.dashboard') }}">Πίσω στην κυρίως οθόνη</a>
            @endcan

            @can('isWarehouseForeman')
                <ul>
                    <li><h4><a href="#">Ανοιχτές Αναθέσεις Εισαγωγής ({{ $imp_assignments_count }})</a></h4></li>
                    <li><h4><a href="#">Ανοιχτές Αναθέσεις Εξαγωγής ({{ $exp_assignments_count }})</a></h4></li>
                </ul>
                <br/><br/>
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
