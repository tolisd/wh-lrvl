<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

// Route::get('/home', 'PagesController@home'); //is overridden by the other route

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');

/*
Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
*/



/*
Route::middleware(['can:isSuperAdmin', 'can:isCompanyCEO'])->group(function(){
    Route::get('/dashboard', 'PagesController@dashboard');
});
*/

/*
Route::group(['middleware' => 'can:isSuperAdmin'], function(){
        //mention all the administrator routes in here
        Route::get('/home', 'AdministratorController@home');
        Route::get('/dashboard', 'AdministratorController@dashboard');
        Route::get('/private', 'AdministratorController@private');
        Route::get('/about', 'AdministratorController@about');
});
*/





Route::middleware('administrator')->group(function(){
    //mention all the administrator routes in here
    
    Route::get('/admin/home', 'AdministratorController@home');
    //Route::get('/admin/dashboard', 'AdministratorController@dashboard');
    Route::get('/admin/private', 'AdministratorController@private');
    Route::get('/admin/about', 'AdministratorController@about');

    Route::get('/admin/dashboard', 'DashboardController@index')->name('dashboard');
    
    //uncomment the following routes, after implementing them in the AdministratorController!
    
    Route::get('admin/stock/view', 'DashboardController@view_stock')->name('view_stock'); //view stock availability
    /*
    Route::get('/admin/charge', 'AdministratorController@charge_toolkit'); //charge toolkit
    Route::post('/admin/invoice', 'AdministratorController@create_invoice');  //create invoice

    Route::get('/admin/view/assignments', 'AdministratorController@view_assignments'); //view assignments
    Route::get('/admin/view/assignment/{id}', 'AdministratorController@view_assignment_by_id'); //view assignment
    Route::post('/admin/create/assignment/import', 'AdministratorController@create_import_assignment'); //create import assignment
    Route::post('/admin/create/assignment/export', 'AdministratorController@create_export_assignment'); //create export assignment
    Route::put('/admin/update/assignment/{id}', 'AdministratorController@update_assignment_by_id'); //update assignment details
    Route::delete('/admin/delete/assignment/{id}', 'AdministratorController@delete_assignment_by_id'); //delete assignment

    Route::get('/admin/view/products', 'AdministratorController@view_products'); //view products
    Route::get('/admin/view/product/{id}', 'AdministratorController@view_product_by_id'); //view single product
    Route::post('/admin/create/product', 'AdministratorController@create_product'); //create new product
    Route::put('admin/update/product/{id}', 'AdministratorController@update_product_by_id'); //update a product
    Route::delete('/admin/delete/product/{id}', 'AdministratorController@delete_product_by_id'); //delete a product

    Route::post('/admin/profile/create', 'AdministratorController@create_new_user'); //create new user
    Route::put('/admin/profile/update/{id}', 'AdministratorController@update_user_by_id'); //update a user
    Route::delete('/admin/profile/delete/{id}', 'AdministratorController@delete_user_by_id'); //delete an existing user
    Route::get('/admin/profile/view', 'AdministratorController@view_user'); //view a single user    
    Route::put('/admin/profile/{id}/password','AdministratorController@update_password');  //change user password
    */
});


//Route::get('/home', 'CompanyCEOController@home')->middleware('can:isCompanyCEO');


/*
Route::group(['middleware' => 'can:isCompanyCEO'], function(){
   //all the Company CEO routes in here
   Route::get('/home', 'CompanyCEOController@home');
   Route::get('/dashboard', 'CompanyCEOController@index');     
   Route::get('/private', 'CompanyCEOController@private');
});
*/


Route::middleware('companymanager')->group(function(){  //was middleware('can:isCompanyCEO')
    //all the Company CEO routes in here
    
   Route::get('/manager/home', 'CompanyCEOController@home');
   Route::get('/manager/dashboard', 'DashboardController@index');    //<-- it worked. It's a common route with Super-Administrator.
   Route::get('/manager/private', 'CompanyCEOController@private');
   Route::get('/manager/about', 'CompanyCEOController@about');
   
   //uncomment the following routes and implement the in the CompanyCEOController!
   
   Route::get('/manager/stock/view', 'DashboardController@view_stock')->name('view_stock'); //view stock availability
   /*
   Route::get('/manager/charge', 'CompanyCEOController@charge_toolkit'); //charge toolkit
   Route::post('/manager/invoice', 'CompanyCEOController@create_invoice');  //create invoice

   Route::get('/manager/view/assignments', 'CompanyCEOController@view_assignments'); //view assignments
   Route::get('/manager/view/assignment/{id}', 'CompanyCEOController@view_assignment_by_id'); //view assignment
   Route::post('/manager/create/assignment/import', 'CompanyCEOController@create_import_assignment'); //create import assignment
   Route::post('/manager/create/assignment/export', 'CompanyCEOController@create_export_assignment'); //create export assignment
   Route::put('/manager/update/assignment/{id}', 'CompanyCEOController@update_assignment_by_id'); //update assignment details
   Route::delete('/manager/delete/assignment/{id}', 'CompanyCEOController@delete_assignment_by_id'); //delete assignment

   Route::get('/manager/view/products', 'CompanyCEOController@view_products'); //view products
   Route::get('/manager/view/product/{id}', 'CompanyCEOController@view_product_by_id'); //view single product
   Route::post('/manager/create/product', 'CompanyCEOController@create_product'); //create new product
   Route::put('/manager/update/product/{id}', 'CompanyCEOController@update_product_by_id'); //update a product
   Route::delete('/manager/delete/product/{id}', 'CompanyCEOController@delete_product_by_id'); //delete a product

   Route::post('/manager/profile/create', 'CompanyCEOController@create_new_user'); //create new user
    Route::put('/manager/profile/update/{id}', 'CompanyCEOController@update_user_by_id'); //update a user
    Route::delete('/manager/profile/delete/{id}', 'CompanyCEOController@delete_user_by_id'); //delete an existing user
    Route::get('/manager/profile/view', 'CompanyCEOController@view_user'); //view a single user    
    Route::put('/manager/profile/{id}/password','CompanyCEOController@update_password');  //change user password
    */
});



/*
//Web routes group logic, for both super-roles
Route::group(['middleware' => 'can:isSuperAdmin,isCompanyCEO'], function(){
    Route::get('/admin/dashboard', 'AdministratorController@dashboard');
    Route::get('/manager/dashboard', 'CompanyCEOController@index');      
    //Route::get('/dashboard', 'PagesController@dashboard');
});
*/



/*
Route::group(['middleware' => 'can:isCompanyCEO'], function(){
    Route::get('/dashboard', 'PagesController@dashboard');
    Route::get('/manager/home', 'CompanyCEOController@home');
    Route::get('/manager/dashboard', 'CompanyCEOController@index');     
    Route::get('/manager/private', 'CompanyCEOController@private');
    Route::get('/manager/about', 'CompanyCEOController@about');

    Route::group(['middleware' => 'can:isSuperAdmin'], function(){
        Route::get('/admin/home', 'AdministratorController@home');
        Route::get('/admin/dashboard', 'AdministratorController@dashboard');
        Route::get('/admin/private', 'AdministratorController@private');
        Route::get('/admin/about', 'AdministratorController@about');
    });
});
*/



Route::middleware('accountant')->group(function(){
    Route::get('/accountant/home', 'AccountantController@home');
    Route::get('/accountant/dashboard', 'AccountantController@dasboard');
});


Route::middleware('foreman')->group(function(){
    Route::get('/whforeman/home', 'WarehouseForemanController@home');

});


Route::middleware('worker')->group(function(){
    Route::get('/whworker/home', 'WarehouseWorkerController@home');

});


Route::middleware('normaluser')->group(function(){
    Route::get('/user/home', 'UserController@home');

});



/*
Route::get('/dashboard', function(){
    return view('dashboard');
});
*/





//default /ui view
Route::get('/bootstrap4', function(){
    return view('layouts.app');
});

/*
Route::group(['middleware' => 'can:isSuperAdmin,isCompanyCEO'], function(){
    Route::get('/dashboard', 'PagesController@dashboard');
    //Route::get('/admin/dashboard', 'AdministratorController@dashboard');
    Route::get('/private', 'PagesController@private');
    Route::get('/about', 'PagesController@about');
        
    Route::get('/stock/view', 'PagesController@view_stock'); //view stock availability
    Route::get('/charge-toolkit', 'PagesController@charge_toolkit'); //charge toolkit
    Route::post('/invoice/create', 'PagesController@create_invoice');  //create invoice

    Route::get('/assignments/view', 'PagesController@view_assignments'); //view assignments
    Route::get('/assignment/{id}/view', 'PagesController@view_assignment_by_id'); //view assignment
    Route::post('/assignment/import/create', 'PagesController@create_import_assignment'); //create import assignment
    Route::post('/assignment/export/create', 'PagesController@create_export_assignment'); //create export assignment
    Route::put('/assignment/{id}/update', 'PagesController@update_assignment_by_id'); //update assignment details
    Route::delete('/assignment/{id}/delete', 'PagesController@delete_assignment_by_id'); //delete assignment

    Route::get('/products/view', 'PagesController@view_products'); //view products
    Route::get('/product/{id}/view', 'PagesController@view_product_by_id'); //view single product
    Route::post('/product/create', 'PagesController@create_product'); //create new product
    Route::put('/product/{id}/update', 'PagesController@update_product_by_id'); //update a product
    Route::delete('/product/{id}/delete', 'PagesController@delete_product_by_id'); //delete a product

    Route::post('/profile/create', 'PagesController@create_new_user'); //create new user
    Route::put('/profile/{id}/update', 'PagesController@update_user_by_id'); //update a user
    Route::delete('/profile/{id}/delete', 'PagesController@delete_user_by_id'); //delete an existing user
    Route::get('/profile/view', 'PagesController@view_user'); //view a single user    
    Route::put('/profile/{id}/change-password','PagesController@update_password');  //change user password
});
*/