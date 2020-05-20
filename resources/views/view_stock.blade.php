{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη - Dashboard')

@section('content_header')
    <h1>Warehouse / Stock Availability</h1>
@stop


@section('content')    
    <div class="row">
        <div class="col-lg-3 col-xs-6">

            <p>Δες Διαθεσιμότητα του Στοκ</p>  
            <p>Τρέχων # προϊόντων σε Στοκ</p> 

            @can('isSuperAdmin')
                <a href="admin/dashboard">Πίσω στην κυρίως οθόνη</a> 
            @endcan

            @can('isCompanyCEO')
                <a href="manager/dashboard">Πίσω στην κυρίως οθόνη</a> 
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