@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Dashboard</div>

                <div class="card-body">
                    @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                    @endif

                    You are logged in successfully!
                    <br/>

                    @can('isSuperAdmin')
                        <a href="/admin/dashboard">Go to Admin Dashboard</a>
                    @endcan

                    @can('isCompanyCEO')
                        <a href="/manager/dashboard">Go to Manager Dashboard</a>
                    @endcan

                    @can('isAccountant')
                        <a href="/accountant/dashboard">Go to Accountant Dashboard</a>
                    @endcan

                    @can('isWarehouseForeman')
                        <a href="/foreman/dashboard">Go to Warehouse Foreman Dashboard</a>
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
