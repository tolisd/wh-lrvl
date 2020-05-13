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


Route::middleware('can:isSuperAdmin')->group(function(){
    //mention all the administrator routes in here
    Route::get('/admin/home', 'AdministratorController@home');
    Route::get('/admin/dashboard', 'AdministratorController@dashboard');
    Route::get('/admin/private', 'AdministratorController@private');
    Route::get('/admin/about', 'AdministratorController@about');
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


Route::middleware('can:isCompanyCEO')->group(function(){
    //all the Company CEO routes in here
   Route::get('/manager/home', 'CompanyCEOController@home');
   Route::get('/manager/dashboard', 'CompanyCEOController@index');     
   Route::get('/manager/private', 'CompanyCEOController@private');
   Route::get('/manager/about', 'CompanyCEOController@about');
});



Route::middleware('can:isAccountant')->group(function(){
    Route::get('/accountant/home', 'AccountantController@home');
    Route::get('/accountant/dashboard', 'AccountantController@dasboard');
});


Route::middleware('can:isWarehouseForeman')->group(function(){
    Route::get('/whforeman/home', 'WarehouseForemanController@home');

});


Route::middleware('can:isWarehouseWorker')->group(function(){
    Route::get('/whworker/home', 'WarehouseWorkerController@home');

});


Route::middleware('can:isNormalUser')->group(function(){
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
