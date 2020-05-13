@extends('master')


@section('head')

@endsection

@section('title') 
    This is the about page..
@endsection

@section('nav')
@endsection

@section('header')  
    <h3>This is the header of the page</h3>
@endsection

@section('footer')  
    <h3>Ypostirixis</h3>  
@endsection

@section('container')    
    <h2>This is the main content for the ABOUT page</h2>
    <p>Lorem ipsum, dolor sit amet consectetur adipisicing elit. Omnis, pariatur quae ducimus laborum libero minus possimus ea ex inventore repudiandae sint,
       hic quas eius magni repellendus sequi, voluptatum reiciendis accusantium totam quasi? Praesentium perspiciatis perferendis illum deleniti doloremque,
       voluptatum eaque in earum totam ullam minus cumque aspernatur laborum! Aperiam, possimus?
    </p>
@endsection


@can('isSuperAdmin')
	<h4>Administrator access!</h4>
	<a href="/admin/dashboard">Go to dashboard</a>
@endcan

@can('isWarehouseForeman')
	<h4>Warehouse-Foreman access!</h4>
@endcan

@can('isWarehouseWorker')
	<h4>Warehouse-Worker access!</h4>
@endcan



 



