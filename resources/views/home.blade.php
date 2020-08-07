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

                    Κάνατε επιτυχές Log-In!
                    <br/>

                    @can('isSuperAdmin')
                        <a href="/admin/dashboard">Πήγαινε στον Πίνακα Ελέγχου του Διαχειριστή</a>
                    @endcan

                    @can('isCompanyCEO')
                        <a href="/manager/dashboard">Πήγαινε στον Πίνακα Ελέγχου του Διευθυντή</a>
                    @endcan

                    @can('isAccountant')
                        <a href="/accountant/dashboard">Πήγαινε στον Πίνακα Ελέγχου του Λογιστή</a>
                    @endcan

                    @can('isWarehouseForeman')
                        <a href="/foreman/dashboard">Πήγαινε στον Πίνακα Ελέγχου του Προϊσταμένου Αποθήκης</a>
                    @endcan

                    @can('isWarehouseWorker')
                        <a href="/worker/dashboard">Πήγαινε στον Πίνακα Ελέγχου του Εργαζόμενου Αποθήκης</a>
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
