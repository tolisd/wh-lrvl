@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Private Resources</div>

                <div class="panel-body">
                    Confidential Information
                    <br/>                

                    @can('isSuperAdmin')
                        <div class="btn btn-success btn-lg">
                          You have Administrator Access!
                        </div>
                        <br/>
                        <a href="/admin/dashboard">Go to dashboard</a>
                    @endcan

                    @can('isCompanyCEO')
                        <div class="btn btn-primary btn-lg">
                          You have Manager Access!
                        </div>
                        <br/>
                        <a href="/manager/dashboard">Go to dashboard</a>
                    @endcan

                    @can('isNormalUser')
                        <div class="btn btn-info btn-lg">
                          You have Normal User Access
                        </div>
                    @endcan

                </div>
            </div>
        </div>
    </div>
</div>
@endsection