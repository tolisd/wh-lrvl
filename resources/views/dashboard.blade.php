{{-- resources/views/admin/dashboard.blade.php --}}

@extends('adminlte::page')

@section('title', 'Dashboard')

@section('content_header')
    <h1>Warehouse / Dashboard</h1>
@stop

<!-- Originally, NO sidebar section in this file! I added this section! Beware! -->

@section('content')
    <p>Welcome, this is the Warehouse Application by Ypostirixis Group.</p>
    <div class="row">
        <div class="col-lg-3 col-xs-6">



            <!-- small box -->
            <div class="small-box bg-yellow"> 
              <div class="inner">
                    <h3>6</h3>     
                    <p>Ανοιχτές Αναθέσεις</p>
              </div>
              <div class="icon">                
                <i class="fas fa-briefcase fa-sm" aria-hidden="true"></i>                
              </div>
              <a href="/assignments/view" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
            </div>


        
            <!-- small box -->
            <div class="small-box bg-lightblue"> <!-- changed from bg-aqua and it works -->
              <div class="inner">
                    <h3>{{ $users }}</h3>     
                    <p>Χρήστες</p>
              </div>
              <div class="icon">
                <!--<i class="ion ion-stats-bars"></i> -->
                <i class="fas fa-users fa-sm" aria-hidden="true"></i>
                <!--<i class="ion ion-stats-bars">
                  <ion-icon name="stats-chart-outline"></ion-icon> 
                </i>-->
              </div>
              <a href="/profiles/view" class="small-box-footer">Περισσότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
            </div>


            <!-- small box -->
            <div class="small-box bg-green"> 
              <div class="inner">
                    <h3>1000</h3>     
                    <p>Προϊόντα</p>
              </div>
              <div class="icon">                
                <i class="fas fa-server fa-sm" aria-hidden="true"></i>                
              </div>
              <a href="/products/view" class="small-box-footer">Περισότερες πληροφορίες... <i class="fa fa-arrow-circle-right"></i></a>
            </div>
         
        </div>
      </div>
@stop

@section('css')
    <link rel="stylesheet" href="/css/admin_custom.css">
@stop

@section('js')
    <script> console.log('Hi!'); </script>
@stop