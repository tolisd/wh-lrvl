{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Αποθήκη | Κεντρικός Πίνακας Ελέγχου')

@section('content_header')
    <h1><strong>Warehouse/Αποθήκη</strong> | Πίνακας Ελέγχου</h1>
@stop

<!-- Originally, NO sidebar section in this file! I added this section! Beware! -->

@section('content')

    <p>Καλώς ήλθατε, αυτή είναι η εφαρμογή Warehouse/Αποθήκη της <strong>Ypostirixis Group</strong>.</p>
    <div class="row">
        <div class="col-lg-3 col-xs-6">


            @canany(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant'])
              <!-- small box -->
              <div class="small-box bg-yellow">

                <div class="inner">
                      <h3>{{ $import_assignments_count }}</h3>
                      <p>Ανοιχτές Αναθέσεις Εισαγωγής</p>
                </div>

                <div class="icon">
                  <i class="fas fa-arrow-left fa-sm" aria-hidden="true"></i>
                </div>

                @can('isSuperAdmin')
                  <a href="{{ route('admin.assignments.import.open.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isCompanyCEO')
                  <a href="{{ route('manager.assignments.import.open.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isWarehouseForeman')
                  <a href="{{ route('foreman.assignments.import.open.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isAccountant')
                  <a href="{{ route('accountant.assignments.import.open.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

              </div>




              <div class="small-box bg-orange">

                <div class="inner">
                      <h3>{{ $export_assignments_count }}</h3>
                      <p>Ανοιχτές Αναθέσεις Εξαγωγής</p>
                </div>

                <div class="icon">
                  <i class="fas fa-arrow-right fa-sm" aria-hidden="true"></i>
                </div>

                @can('isSuperAdmin')
                  <a href="{{ route('admin.assignments.export.open.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isCompanyCEO')
                  <a href="{{ route('manager.assignments.export.open.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isWarehouseForeman')
                  <a href="{{ route('foreman.assignments.export.open.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isAccountant')
                  <a href="{{ route('accountant.assignments.export.open.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

              </div>


            @endcanany

            @canany(['isSuperAdmin', 'isCompanyCEO'])
              <!-- small box -->
              <div class="small-box bg-lightblue"> <!-- changed from bg-aqua and it works -->
                <div class="inner">
                      <h3>{{ $usersCount }}</h3>
                      <p>Χρήστες Εφαρμογής</p>
                </div>
                <div class="icon">
                  <!--<i class="ion ion-stats-bars"></i> -->
                  <i class="fas fa-user fa-sm" aria-hidden="true"></i>
                  <!--<i class="ion ion-stats-bars">
                    <ion-icon name="stats-chart-outline"></ion-icon>
                  </i>-->
                </div>
                @can('isSuperAdmin')
                  <a href="{{ route('admin.users.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isCompanyCEO')
                  <a href="{{ route('manager.users.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

              </div>
            @endcanany

            @canany(['isSuperAdmin','isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])
            <!-- small box -->
            <div class="small-box bg-green">
                <div class="inner">
                      <h3>{{ $prodCount }}</h3>
                      <p>Προϊόντα στις Αποθήκες</p>
                </div>
                <div class="icon">
                  <i class="fas fa-cubes fa-sm" aria-hidden="true"></i>
                </div>
                @can('isSuperAdmin')
                  <a href="{{ route('admin.products.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isCompanyCEO')
                  <a href="{{ route('manager.products.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isWarehouseForeman')
                  <a href="{{ route('foreman.products.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isWarehouseWorker')
                  <a href="{{ route('worker.products.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan
            </div>
            @endcanany

            @canany(['isSuperAdmin','isCompanyCEO', 'isWarehouseForeman'])
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                      <h3>{{ $tools_count }}</h3>
                      <p>Εργαλεία</p>
                </div>
                <div class="icon">
                  <i class="fas fa-tools fa-sm" aria-hidden="true"></i>
                </div>
                @can('isSuperAdmin')
                  <a href="{{ route('admin.tools.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isCompanyCEO')
                  <a href="{{ route('manager.tools.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

                @can('isWarehouseForeman')
                  <a href="{{ route('foreman.tools.view') }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                @endcan

            </div>
            @endcanany

        </div>

        <div class="col-lg-3 col-xs-6">
             <!--warehouses, dynamic infoboxes & views, twice -->

            @canany(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])
            <!-- small box -->

            <div class="col">

            @foreach($warehouses as $warehouse)

                <div class="small-box" style="background-color: lightblue;">
                    <div class="inner">
                        <h5><strong>{{ $warehouse->name }}</strong></h5>

                            <strong>Προϊστάμενος:</strong>
                            <ul>
                            @foreach($employees as $emp)
                                @php
                                    $proistamenoi = [];
                                    foreach($users as $user){
                                        if(   ($user->user_type   == 'warehouse_foreman')
                                        && ($emp->warehouse_id == $warehouse->id)
                                        && ($emp->user_id      == $user->id)
                                        ){
                                            $user_name = $user->name;
                                            array_push($proistamenoi, $user_name);
                                        }
                                    }
                                @endphp

                                @foreach($proistamenoi as $fname)
                                    <li>{{ $fname }}</li>
                                @endforeach
                            @endforeach
                            </ul>


                            <strong>Αποθηκάριοι:</strong>
                            <ul>
                            @foreach($employees as $emp)
                                @php
                                    $apothikarioi = [];
                                    foreach($users as $user){
                                        if(    ($user->user_type   == 'warehouse_worker')
                                            && ($emp->user_id      == $user->id)
                                            && ($emp->warehouse_id == $warehouse->id)
                                        ){
                                            $apothikarios = $user->name;
                                            array_push($apothikarioi, $apothikarios);
                                        }
                                    }
                                @endphp

                                @foreach($apothikarioi as $wname)
                                    <li>{{ $wname }}</li>
                                @endforeach
                            @endforeach
                            </ul>

                        <!--
                        <p>Αποθηκάριοι:
                            <ul>
                            @foreach($employees->where('warehouse_id', $warehouse->id) as $emp)
                                <li>{{ $emp->user->name }}</li>
                            @endforeach
                            </ul>
                        </p>
                        -->

                        {{-- warehouse product count in here.. --}}
                        <h3>{{ $warehouse->products->count() }}</h3>
                        Προϊόντα


                    </div>

                    <div class="icon">
                        <i class="fas fa-warehouse fa-sm" aria-hidden="true"></i>
                    </div>



                    @can('isSuperAdmin')
                    <a href="{{ route('admin.warehouse.show', ['id' => $warehouse->id]) }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                    @endcan

                    @can('isCompanyCEO')
                    <a href="{{ route('manager.warehouse.show', ['id' => $warehouse->id]) }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                    @endcan

                    @can('isAccountant')
                    <a href="{{ route('accountant.warehouse.show', ['id' => $warehouse->id]) }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                    @endcan

                </div>

            @endforeach
            </div>

            @endcanany



            @canany(['isSuperAdmin','isCompanyCEO', 'isWarehouseForeman', 'isAccountant'])
                <!-- small box -->

                <div class="col">

                @foreach($my_warehouses as $warehouse)


                    <div class="small-box" style="background-color: lightblue;">
                        <div class="inner">
                            <h5><strong>{{ $warehouse->name }}</strong></h5>

                                <strong>Προϊστάμενος:</strong>
                                <ul>
                                @foreach($employees as $emp)
                                    @php
                                        $proistamenoi = [];
                                        foreach($users as $user){
                                            if(   ($user->user_type   == 'warehouse_foreman')
                                               && ($emp->warehouse_id == $warehouse->id)
                                               && ($emp->user_id      == $user->id)
                                               ){
                                                $user_name = $user->name;
                                                array_push($proistamenoi, $user_name);
                                            }
                                        }
                                    @endphp

                                    @foreach($proistamenoi as $fname)
                                        <li>{{ $fname }}</li>
                                    @endforeach
                                @endforeach
                                </ul>


                                <strong>Αποθηκάριοι:</strong>
                                <ul>
                                @foreach($employees as $emp)
                                    @php
                                        $apothikarioi = [];
                                        foreach($users as $user){
                                            if(    ($user->user_type   == 'warehouse_worker')
                                                && ($emp->user_id      == $user->id)
                                                && ($emp->warehouse_id == $warehouse->id)
                                              ){
                                                $apothikarios = $user->name;
                                                array_push($apothikarioi, $apothikarios);
                                            }
                                        }
                                    @endphp

                                    @foreach($apothikarioi as $wname)
                                        <li>{{ $wname }}</li>
                                    @endforeach
                                @endforeach
                                </ul>

                            <!--
                            <p>Αποθηκάριοι:
                                <ul>
                                @foreach($employees->where('warehouse_id', $warehouse->id) as $emp)
                                    <li>{{ $emp->user->name }}</li>
                                @endforeach
                                </ul>
                            </p>
                            -->

                            {{-- warehouse product count in here.. --}}


                        </div>

                        <div class="icon">
                            <i class="fas fa-warehouse fa-sm" aria-hidden="true"></i>
                        </div>


                        @can('isWarehouseForeman')
                        <a href="{{ route('foreman.warehouse.show', ['id' => $warehouse->id]) }}" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
                        @endcan


                    </div>

                @endforeach
                </div>
            @endcanany

        </div>

    </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop
