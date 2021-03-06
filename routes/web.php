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

// Auth::routes();
Auth::routes(['register' => false]);

//I added this following logout line, it works, and it needs to be a GET request..
Route::get('/logout', 'Auth\LoginController@logout');

//I added the following line so that it goes straight to dashboard after login!
//I also changed $redirectTo var in RouteServiceProvider!!
//update: I just changed home_url in adminlte.php to redirect(go) to this route.
Route::get('/home', 'DashboardController@index')->name('dashboard');


//this was bringing up the intermediate home screen (welcome and proceed to dashboard...)
//I fixed it by changing the index method to bring up the dashboard..
//Route::get('/home', 'HomeController@index')->name('home');


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



Route::middleware(['auth', 'administrator'])->prefix('admin')->group(function(){
    //mention all the administrator routes in here

    Route::get('/home', 'AdministratorController@home');
    //Route::get('/dashboard', 'AdministratorController@dashboard');
    Route::get('/private', 'AdministratorController@private');
    Route::get('/about', 'AdministratorController@about');
    Route::get('/dashboard', 'DashboardController@index')->name('admin.dashboard');

    Route::get('/stock/view', 'DashboardController@view_stock')->name('admin.stock.view'); //view stock availability


    Route::get('/tools/view', 'ToolController@view_tools')->name('admin.tools.view');
    Route::get('/tools/charged/view', 'ToolController@view_charged_tools')->name('admin.tools.charged.view');
    Route::get('/tools/non-charged/view', 'ToolController@view_non_charged_tools')->name('admin.tools.noncharged.view');
    //Route::get('/tools/my-charged/view', 'ToolController@view_my_charged_tools')->name('admin.tools.mycharged.view');
    Route::put('/tools/charge-tool/{id}', 'ToolController@charge_tool')->name('admin.tools.charge');
    Route::put('/tools/uncharge-tool/{id}', 'ToolController@uncharge_tool')->name('admin.tools.uncharge');
    Route::post('/tools/create', 'ToolController@create_tool')->name('admin.tools.create');
    Route::put('/tools/update/{id}', 'ToolController@update_tool')->name('admin.tools.update');
    Route::delete('/tools/delete/{id}', 'ToolController@delete_tool')->name('admin.tools.delete');
    Route::get('/tools/download/{filename?}', 'ToolController@get_file')->where('filename', '(.*)')->name('admin.tools.download.file'); //download xrewstiko arxeio/file
    //Route::get('/charge-toolkit', 'DashboardController@charge_toolkit')->name('admin.chargetoolkit'); //charge toolkit
    Route::get('tools/history/view', 'ToolController@view_history')->name('admin.tools.history.view');

    Route::post('/invoice/create', 'DashboardController@create_invoice')->name('admin.invoicecreate');  //create invoice (should be ::post NOT ::get)




    Route::get('/assignments/view', 'AssignmentController@view_all_assignments')->name('admin.assignments.view'); //view assignments
    //Route::get('/assignments/import/view', 'AssignmentController@view_import_assignments')->name('admin.assignments.import.view');
    //Route::get('/assignments/export/view', 'AssignmentController@view_export_assignments')->name('admin.assignments.export.view');
    //Route::get('/assignments/import/view/{id}', 'AssignmentController@view_import_assignment_byId')->name('admin.assignment.import.view');
    Route::get('/assignments/export/view/{id}', 'AssignmentController@view_export_assignment_byId')->name('admin.assignment.export.view');
    //----I changed the next line from post to get, for viewing form purposes!
    //Route::post('/assignments/import/create', 'AssignmentController@create_import_assignment')->name('admin.assignment.import.create'); //create import assignment
    //Route::get('/assignments/import/create', 'AssignmentController@import_index')->name('admin.assignment.import.create');
    //Route::post('/assignments/export/create', 'AssignmentController@create_export_assignment')->name('admin.assignment.export.create'); //create export assignment
    //Route::get('/assignments/export/create', 'AssignmentController@export_index')->name('admin.assignment.export.create');
    //Route::put('/assignments/import/update/{id}', 'AssignmentController@update_import_assignment')->name('admin.assignment.import.update'); //update assignment details
    //Route::put('/assignments/export/update/{id}', 'AssignmentController@update_export_assignment')->name('admin.assignment.export.update');
    //Route::delete('/assignments/import/delete/{id}', 'AssignmentController@delete_import_assignment')->name('admin.assignment.import.delete'); //delete assignment
    //Route::delete('/assignments/export/delete/{id}', 'AssignmentController@delete_export_assignment')->name('admin.assignment.export.delete');


    //Open Import/Export Assignments Views
    Route::get('assignments/import-assignments/open/view', 'ImportAssignmentController@view_open_import_assignments')->name('admin.assignments.import.open.view');
    Route::get('assignments/export-assignments/open/view', 'ExportAssignmentController@view_open_export_assignments')->name('admin.assignments.export.open.view');
    Route::get('assignments/import/close/view', 'ImportAssignmentController@view_closed_import_assignments')->name('admin.assignments.import.close.view');
    Route::get('assignments/export/close/view', 'ExportAssignmentController@view_closed_export_assignments')->name('admin.assignments.export.close.view');

    Route::get('assignments/export/close/download/{filenames?}', 'ExportAssignmentController@get_files_closed_exp')->where('filenames', '(.*)')->name('admin.assignments.export.close.getfiles');
    Route::get('assignments/export/open/download/{filenames?}', 'ExportAssignmentController@get_files_open_exp')->where('filenames', '(.*)')->name('admin.assignments.export.open.getfiles');
    Route::get('assignments/import/close/download/{filenames?}', 'ImportAssignmentController@get_files_closed_imp')->where('filenames', '(.*)')->name('admin.assignments.import.close.getfiles');
    Route::get('assignments/import/open/download/{filenames?}', 'ImportAssignmentController@get_files_open_imp')->where('filenames', '(.*)')->name('admin.assignments.import.open.getfiles');


    //import assignments
    Route::get('/assignments/import/view', 'ImportAssignmentController@view_import_assignments')->name('admin.assignments.import.view');
    Route::post('assignments/import/create', 'ImportAssignmentController@create_import_assignment')->name('admin.assignment.import.create');
    Route::put('assignments/import/update/{id}', 'ImportAssignmentController@update_import_assignment')->name('admin.assignment.import.update');
    Route::delete('assignments/import/delete/{id}', 'ImportAssignmentController@delete_import_assignment')->name('admin.assignment.import.delete');
    Route::put('assignments/import/open/{id}', 'ImportAssignmentController@open_import_assignment')->name('admin.assignment.import.open');
    Route::put('assignments/import/close/{id}', 'ImportAssignmentController@close_import_assignment')->name('admin.assignment.import.close');
    Route::get('assignments/import/download/{filenames?}', 'ImportAssignmentController@get_files')->where('filenames','(.*)')->name('admin.assignment.import.files');

    //export assignments
    Route::get('/assignments/export/view', 'ExportAssignmentController@view_export_assignments')->name('admin.assignments.export.view');
    Route::post('assignments/export/create', 'ExportAssignmentController@create_export_assignment')->name('admin.assignment.export.create');
    Route::put('assignments/export/update/{id}', 'ExportAssignmentController@update_export_assignment')->name('admin.assignment.export.update');
    Route::delete('assignments/export/delete/{id}', 'ExportAssignmentController@delete_export_assignment')->name('admin.assignment.export.delete');
    Route::put('assignments/export/open/{id}', 'ExportAssignmentController@open_export_assignment')->name('admin.assignment.export.open');
    Route::put('assignments/export/close/{id}', 'ExportAssignmentController@close_export_assignment')->name('admin.assignment.export.close');
    Route::get('assignments/export/download/{filenames?}', 'ExportAssignmentController@get_files')->where('filenames','(.*)')->name('admin.assignment.export.files');

    //imports
    Route::get('/assignments/imports/view', 'ImportController@view_imports')->name('admin.imports.view');
    Route::post('/assignments/imports/create', 'ImportController@create_import')->name('admin.imports.create');
    Route::put('/assignments/imports/update/{id}', 'ImportController@update_import')->name('admin.imports.update');
    Route::delete('/assignments/imports/delete/{id}', 'ImportController@delete_import')->name('admin.imports.delete');
    Route::get('assignments/imports/warehouse/{id}', 'WarehouseController@get_employees_imp'); //ajax route
    Route::get('assignments/imports/download/{filename?}', 'ImportController@get_deltio_imp')->where('filename', '(.*)')->name('admin.imports.deltio.download');

    //exports
    Route::get('/assignments/exports/view', 'ExportController@view_exports')->name('admin.exports.view');
    Route::post('/assignments/exports/create', 'ExportController@create_export')->name('admin.exports.create');
    Route::put('/assignments/exports/update/{id}', 'ExportController@update_export')->name('admin.exports.update');
    Route::delete('/assignments/exports/delete/{id}', 'ExportController@delete_export')->name('admin.exports.delete');
    Route::get('assignments/exports/warehouse/{id}', 'WarehouseController@get_employees_exp'); //ajax route
    // Route::get('assignments/exports/download/{filename?}', 'ExportController@get_deltio_exp')->where('filename', '(.*)')->name('admin.exports.deltio.download');


    Route::get('/products/view', 'ProductController@view_products')->name('admin.products.view'); //view products
    Route::get('/product/view/{id}', 'DashboardController@view_product')->name('admin.product.view'); //view single product
    Route::post('/products/create', 'ProductController@create_product')->name('admin.product.create'); //create new product
    Route::put('/products/update/{id}', 'ProductController@update_product')->name('admin.product.update'); //update a product
    Route::delete('/products/delete/{id}', 'ProductController@delete_product')->name('admin.product.delete'); //delete a product
    Route::get('/products/type/{id}', 'CategoryController@get_types')->name('admin.categorytypes'); //ajax route


    Route::post('/users/create', 'UserController@create_user')->name('admin.user.create'); //create a new user
    Route::put('/users/update/{id}', 'UserController@update_user')->name('admin.user.update'); //update a user
    Route::delete('/users/delete/{id}', 'UserController@delete_user')->name('admin.user.delete'); //delete an existing user
    Route::get('/user/view/{id}', 'DashboardController@view_user')->name('admin.user.view'); //view a single user
    Route::get('/users/view', 'DashboardController@view_users')->name('admin.users.view'); //view all users
    // Route::put('/users/change-password', 'DashboardController@change_user_password')->name('admin.user.change-password'); //change user password
    Route::get('/users/show/{photo?}', 'UserController@show_photo')->where('photo', '(.*)')->name('admin.user.show.photo');
    Route::get('/users/show/pic/{photo?}', 'UserController@show_userpic')->where('photo', '(.*)')->name('admin.user.show.userpic');


    Route::get('/product_category/view', 'CategoryController@view_categories')->name('admin.category.view');
    Route::post('/product_category/create', 'CategoryController@create_category')->name('admin.category.create');
    Route::put('/product_category/update/{id}','CategoryController@update_category')->name('admin.category.update');
    Route::delete('/product_category/delete/{id}', 'CategoryController@delete_category')->name('admin.category.delete');

    Route::get('/product_type/view', 'TypeController@view_types')->name('admin.type.view');
    Route::post('/product_type/create', 'TypeController@create_type')->name('admin.type.create');
    Route::put('/product_type/update/{id}','TypeController@update_type')->name('admin.type.update');
    Route::delete('/product_type/delete/{id}', 'TypeController@delete_type')->name('admin.type.delete');

    Route::get('/meas-units/view', 'MeasureController@view_measunits')->name('admin.measunit.view');
    Route::post('/meas-units/create', 'MeasureController@create_measunit')->name('admin.measunit.create');
    Route::put('/meas-units/update/{id}', 'MeasureController@update_measunit')->name('admin.measunit.update');
    Route::delete('/meas-units/delete/{id}', 'MeasureController@delete_measunit')->name('admin.measunit.delete');

    Route::get('/employees/view', 'EmployeeController@view_employees')->name('admin.employees.view');
    Route::post('/employees/create', 'EmployeeController@create_employee')->name('admin.employees.create');
    Route::put('/employees/update/{id}', 'EmployeeController@update_employee')->name('admin.employees.update');
    Route::delete('/employees/delete/{id}', 'EmployeeController@delete_employee')->name('admin.employees.delete');
    Route::get('/employees/company/{id}', 'CompanyController@get_warehouses')->name('admin.companywarehouses'); //ajax route

    Route::get('/companies/view', 'CompanyController@view_companies')->name('admin.companies.view');
    Route::post('/companies/create', 'CompanyController@create_company')->name('admin.companies.create');
    Route::put('/companies/update/{id}', 'CompanyController@update_company')->name('admin.companies.update');
    Route::delete('/companies/delete/{id}', 'CompanyController@delete_company')->name('admin.companies.delete');

    Route::get('/shipping-companies/view', 'TransportController@view_transport_companies')->name('admin.shipping.view');
    Route::post('/shipping-companies/create', 'TransportController@create_transport_company')->name('admin.shipping.create');
    Route::put('/shipping-companies/update/{id}', 'TransportController@update_transport_company')->name('admin.shipping.update');
    Route::delete('/shipping-companies/delete/{id}', 'TransportController@delete_transport_company')->name('admin.shipping.delete');

    Route::get('/warehouses/view', 'WarehouseController@view_warehouses')->name('admin.warehouses.view');
    Route::post('/warehouses/create', 'WarehouseController@create_warehouse')->name('admin.warehouses.create');
    Route::put('/warehouses/update/{id}', 'WarehouseController@update_warehouse')->name('admin.warehouses.update');
    Route::delete('/warehouses/delete/{id}', 'WarehouseController@delete_warehouse')->name('admin.warehouses.delete');

    Route::any('/warehouse/show/{id}', 'WarehouseController@show_warehouse')->name('admin.warehouse.show');

    //uncomment the following routes, after implementing them in the AdministratorController!
    /*
    Route::get('/view/assignment/{id}', 'AdministratorController@view_assignment_by_id'); //view assignment

    Route::put('/user/{id}/password','AdministratorController@update_password');  //change user password
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


Route::middleware(['auth', 'companymanager'])->prefix('manager')->group(function(){  //was middleware('can:isCompanyCEO')
    //all the Company CEO routes in here

   Route::get('/home', 'CompanyCEOController@home');
   Route::get('/dashboard', 'DashboardController@index')->name('manager.dashboard');    //<-- it worked. It's a common route with Super-Administrator.
   Route::get('/private', 'CompanyCEOController@private');
   Route::get('/about', 'CompanyCEOController@about');

   Route::get('/stock/view', 'DashboardController@view_stock')->name('manager.stock.view'); //view stock availability

   Route::get('/tools/view', 'ToolController@view_tools')->name('manager.tools.view');
   Route::get('/tools/charged/view', 'ToolController@view_charged_tools')->name('manager.tools.charged.view');
   Route::get('/tools/non-charged/view', 'ToolController@view_non_charged_tools')->name('manager.tools.noncharged.view');
   //Route::get('/tools/my-charged/view', 'ToolController@view_my_charged_tools')->name('manager.tools.mycharged.view');
   Route::put('/tools/charge-tool/{id}', 'ToolController@charge_tool')->name('manager.tools.charge');
   Route::put('/tools/uncharge-tool/{id}', 'ToolController@uncharge_tool')->name('manager.tools.uncharge');
   Route::post('/tools/create', 'ToolController@create_tool')->name('manager.tools.create');
   Route::put('/tools/update/{id}', 'ToolController@update_tool')->name('manager.tools.update');
   Route::delete('/tools/delete/{id}', 'ToolController@delete_tool')->name('manager.tools.delete');
   Route::get('/tools/download/{filename?}', 'ToolController@get_file')->where('filename', '(.*)')->name('manager.tools.download.file'); //download xrewstiko arxeio/file
   //Route::get('/charge-toolkit', 'DashboardController@charge_toolkit')->name('manager.chargetoolkit'); //charge toolkit
   Route::get('tools/history/view', 'ToolController@view_history')->name('manager.tools.history.view');

   Route::post('/invoice/create', 'DashboardController@create_invoice')->name('manager.invoicecreate');  //create invoice

   Route::get('/assignments/view', 'AssignmentController@view_all_assignments')->name('manager.assignments.view'); //view assignments
   //Route::get('/assignments/import/view', 'AssignmentController@view_import_assignments')->name('manager.assignments.import.view');
   //Route::get('/assignments/export/view', 'AssignmentController@view_export_assignments')->name('manager.assignments.export.view');
   //Route::get('/assignments/import/view/{id}', 'AssignmentController@view_import_assignment_byId')->name('manager.assignment.import.view');
   //Route::get('/assignments/export/view/{id}', 'AssignmentController@view_export_assignment_byId')->name('manager.assignment.export.view');
   //Route::post('/assignments/import/create', 'AssignmentController@create_import_assignment')->name('manager.assignment.import.create'); //create import assignment
   //Route::post('/assignments/export/create', 'AssignmentController@create_export_assignment')->name('manager.assignment.export.create'); //create export assignment
   //Route::put('/assignments/import/update/{id}', 'AssignmentController@update_import_assignment')->name('manager.assignment.import.update'); //update assignment details
   //Route::put('/assignments/export/update/{id}', 'AssignmentController@update_export_assignment')->name('manager.assignment.export.update');
   //Route::delete('/assignments/import/delete/{id}', 'AssignmentController@delete_import_assignment')->name('manager.assignment.import.delete'); //delete assignment
   //Route::delete('/assignments/export/delete/{id}', 'AssignmentController@delete_export_assignment')->name('manager.assignment.export.delete');


    //Open Import/Export Assignments Views
    Route::get('assignments/import-assignments/open/view', 'ImportAssignmentController@view_open_import_assignments')->name('manager.assignments.import.open.view');
    Route::get('assignments/export-assignments/open/view', 'ExportAssignmentController@view_open_export_assignments')->name('manager.assignments.export.open.view');
    Route::get('assignments/import/close/view', 'ImportAssignmentController@view_closed_import_assignments')->name('manager.assignments.import.close.view');
    Route::get('assignments/export/close/view', 'ExportAssignmentController@view_closed_export_assignments')->name('manager.assignments.export.close.view');

    Route::get('assignments/export/close/download/{filenames?}', 'ExportAssignmentController@get_files_closed_exp')->where('filenames', '(.*)')->name('manager.assignments.export.close.getfiles');
    Route::get('assignments/export/open/download/{filenames?}', 'ExportAssignmentController@get_files_open_exp')->where('filenames', '(.*)')->name('manager.assignments.export.open.getfiles');
    Route::get('assignments/import/close/download/{filenames?}', 'ImportAssignmentController@get_files_closed_imp')->where('filenames', '(.*)')->name('manager.assignments.import.close.getfiles');
    Route::get('assignments/import/open/download/{filenames?}', 'ImportAssignmentController@get_files_open_imp')->where('filenames', '(.*)')->name('manager.assignments.import.open.getfiles');


    //import assignments
    Route::get('/assignments/import/view', 'ImportAssignmentController@view_import_assignments')->name('manager.assignments.import.view');
    Route::post('assignments/import/create', 'ImportAssignmentController@create_import_assignment')->name('manager.assignment.import.create');
    Route::put('assignments/import/update/{id}', 'ImportAssignmentController@update_import_assignment')->name('manager.assignment.import.update');
    Route::delete('assignments/import/delete/{id}', 'ImportAssignmentController@delete_import_assignment')->name('manager.assignment.import.delete');
    Route::put('assignments/import/open/{id}', 'ImportAssignmentController@open_import_assignment')->name('manager.assignment.import.open');
    Route::put('assignments/import/close/{id}', 'ImportAssignmentController@close_import_assignment')->name('manager.assignment.import.close');
    Route::get('assignments/import/{filenames?}', 'ImportAssignmentController@get_files')->where('filenames','(.*)')->name('manager.assignment.import.files');

    //export assignments
    Route::get('/assignments/export/view', 'ExportAssignmentController@view_export_assignments')->name('manager.assignments.export.view');
    Route::post('assignments/export/create', 'ExportAssignmentController@create_export_assignment')->name('manager.assignment.export.create');
    Route::put('assignments/export/update/{id}', 'ExportAssignmentController@update_export_assignment')->name('manager.assignment.export.update');
    Route::delete('assignments/export/delete/{id}', 'ExportAssignmentController@delete_export_assignment')->name('manager.assignment.export.delete');
    Route::put('assignments/export/open/{id}', 'ExportAssignmentController@open_export_assignment')->name('manager.assignment.export.open');
    Route::put('assignments/export/close/{id}', 'ExportAssignmentController@close_export_assignment')->name('manager.assignment.export.close');
    // Route::get('assignments/export/download/{filenames?}', 'ExportAssignmentController@get_files')->where('filenames','(.*)')->name('manager.assignment.export.files');


    //imports
    Route::get('/assignments/imports/view', 'ImportController@view_imports')->name('manager.imports.view');
    Route::post('/assignments/imports/create', 'ImportController@create_import')->name('manager.imports.create');
    Route::put('/assignments/imports/update/{id}', 'ImportController@update_import')->name('manager.imports.update');
    Route::delete('/assignments/imports/delete/{id}', 'ImportController@delete_import')->name('manager.imports.delete');
    Route::get('assignments/imports/warehouse/{id}', 'WarehouseController@get_employees_imp'); //ajax route
    Route::get('assignments/imports/download/{filename?}', 'ImportController@get_deltio_imp')->where('filename', '(.*)')->name('manager.imports.deltio.download');

    //exports
    Route::get('/assignments/exports/view', 'ExportController@view_exports')->name('manager.exports.view');
    Route::post('/assignments/exports/create', 'ExportController@create_export')->name('manager.exports.create');
    Route::put('/assignments/exports/update/{id}', 'ExportController@update_export')->name('manager.exports.update');
    Route::delete('/assignments/exports/delete/{id}', 'ExportController@delete_export')->name('manager.exports.delete');
    Route::get('assignments/exports/warehouse/{id}', 'WarehouseController@get_employees_exp'); //ajax route
    Route::get('assignments/exports/download/{filename?}', 'ExportController@get_deltio_exp')->where('filename', '(.*)')->name('manager.exports.deltio.download');

   Route::get('/products/view', 'ProductController@view_products')->name('manager.products.view'); //view products
   Route::get('/product/view/{id}', 'DashboardController@view_product')->name('manager.product.view'); //view single product
   Route::post('/products/create', 'ProductController@create_product')->name('manager.product.create'); //create new product
   Route::put('/products/update/{id}', 'ProductController@update_product')->name('manager.product.update'); //update product
   Route::delete('/products/delete/{id}', 'ProductController@delete_product')->name('manager.product.delete'); //delete product
   Route::get('/products/type/{id}', 'CategoryController@get_types')->name('manager.categorytypes'); //ajax route

   Route::post('/users/create', 'UserController@create_user')->name('manager.user.create'); //create a new user
   Route::put('/users/update/{id}', 'UserController@update_user')->name('manager.user.update'); //update a user
   Route::delete('/users/delete/{id}', 'UserController@delete_user')->name('manager.user.delete'); //delete an existing user
   Route::get('/user/view/{id}', 'DashboardController@view_user')->name('manager.user.view'); //view a single user
   Route::get('/users/view', 'DashboardController@view_users')->name('manager.users.view'); //view all users
   Route::put('/user/change-password', 'DashboardController@change_user_password')->name('manager.user.change-password'); //change user password
   Route::get('/users/show/{photo?}', 'UserController@show_photo')->where('photo', '(.*)')->name('manager.user.show.photo');
   Route::get('/users/show/pic/{photo?}', 'UserController@show_userpic')->where('photo', '(.*)')->name('manager.user.show.userpic');


   Route::get('/product_category/view', 'CategoryController@view_categories')->name('manager.category.view');
   Route::post('/product_category/create', 'CategoryController@create_category')->name('manager.category.create');
   Route::put('/product_category/update/{id}','CategoryController@update_category')->name('manager.category.update');
   Route::delete('/product_category/delete/{id}', 'CategoryController@delete_category')->name('manager.category.delete');

   Route::get('/product_type/view', 'TypeController@view_types')->name('manager.type.view');
   Route::post('/product_type/create', 'TypeController@create_type')->name('manager.type.create');
   Route::put('/product_type/update/{id}','TypeController@update_type')->name('manager.type.update');
   Route::delete('/product_type/delete/{id}', 'TypeController@delete_type')->name('manager.type.delete');

   Route::get('/meas-units/view', 'MeasureController@view_measunits')->name('manager.measunit.view');
   Route::post('/meas-units/create', 'MeasureController@create_measunit')->name('manager.measunit.create');
   Route::put('/meas-units/update/{id}', 'MeasureController@update_measunit')->name('manager.measunit.update');
   Route::delete('/meas-units/delete/{id}', 'MeasureController@delete_measunit')->name('manager.measunit.delete');

   Route::get('/employees/view', 'EmployeeController@view_employees')->name('manager.employees.view');
   Route::post('/employees/create', 'EmployeeController@create_employee')->name('manager.employees.create');
   Route::put('/employees/update/{id}', 'EmployeeController@update_employee')->name('manager.employees.update');
   Route::delete('/employees/delete/{id}', 'EmployeeController@delete_employee')->name('manager.employees.delete');
   Route::get('/employees/company/{id}', 'CompanyController@get_warehouses')->name('manager.companywarehouses'); //ajax route

   Route::get('/companies/view', 'CompanyController@view_companies')->name('manager.companies.view');
   Route::post('/companies/create', 'CompanyController@create_company')->name('manager.companies.create');
   Route::put('/companies/update/{id}', 'CompanyController@update_company')->name('manager.companies.update');
   Route::delete('/companies/delete/{id}', 'CompanyController@delete_company')->name('manager.companies.delete');

   Route::get('/shipping-companies/view', 'TransportController@view_transport_companies')->name('manager.shipping.view');
   Route::post('/shipping-companies/create', 'TransportController@create_transport_company')->name('manager.shipping.create');
   Route::put('/shipping-companies/update/{id}', 'TransportController@update_transport_company')->name('manager.shipping.update');
   Route::delete('/shipping-companies/delete/{id}', 'TransportController@delete_transport_company')->name('manager.shipping.delete');

   Route::get('/warehouses/view', 'WarehouseController@view_warehouses')->name('manager.warehouses.view');
   Route::post('/warehouses/create', 'WarehouseController@create_warehouse')->name('manager.warehouses.create');
   Route::put('/warehouses/update/{id}', 'WarehouseController@update_warehouse')->name('manager.warehouses.update');
   Route::delete('/warehouses/delete/{id}', 'WarehouseController@delete_warehouse')->name('manager.warehouses.delete');

   Route::get('/warehouse/show/{id}', 'WarehouseController@show_warehouse')->name('manager.warehouse.show');

   //uncomment the following routes and implement the in the CompanyCEOController!
   /*
    Route::get('/view/assignment/{id}', 'CompanyCEOController@view_assignment_by_id'); //view 1 assignment

    Route::delete('/user/delete/{id}', 'CompanyCEOController@delete_user_by_id'); //delete an existing user
    Route::get('/user/view', 'CompanyCEOController@view_user'); //view a single user
    Route::put('/user/{id}/password','CompanyCEOController@update_password');  //change user password
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


Route::middleware(['auth', 'accountant'])->prefix('accountant')->group(function(){
    Route::get('/home', 'AccountantController@index');
    Route::get('/dashboard', 'DashboardController@index')->name('accountant.dashboard');

    Route::post('/invoice/create', 'DashboardController@create_invoice')->name('accountant.invoicecreate');

    Route::get('/assignments/view', 'AssignmentController@view_all_assignments')->name('accountant.assignments.view'); //view assignments
    //Route::get('/assignments/import/view', 'AssignmentController@view_import_assignments')->name('accountant.assignments.import.view');
    //Route::get('/assignments/export/view', 'AssignmentController@view_export_assignments')->name('accountant.assignments.export.view');
    //Route::get('/assignments/import/view/{id}', 'AssignmentController@view_import_assignment_byId')->name('accountant.assignment.import.view');
    //Route::get('/assignments/export/view/{id}', 'AssignmentController@view_export_assignment_byId')->name('accountant.assignment.export.view');
    //Route::post('/assignments/import/create', 'AssignmentController@create_import_assignment')->name('accountant.assignment.import.create'); //create import assignment
    //Route::post('/assignments/export/create', 'AssignmentController@create_export_assignment')->name('accountant.assignment.export.create'); //create export assignment
    //Route::put('/assignments/import/update/{id}', 'AssignmentController@update_import_assignment')->name('accountant.assignment.import.update'); //update assignment details
    //Route::put('/assignments/export/update/{id}', 'AssignmentController@update_export_assignment')->name('accountant.assignment.export.update');
    //Route::delete('/assignments/import/delete/{id}', 'AssignmentController@delete_import_assignment')->name('accountant.assignment.import.delete'); //delete assignment
    //Route::delete('/assignments/export/delete/{id}', 'AssignmentController@delete_export_assignment')->name('accountant.assignment.export.delete');

    //Open Import/Export Assignments Views
    Route::get('assignments/import-assignments/open/view', 'ImportAssignmentController@view_open_import_assignments')->name('accountant.assignments.import.open.view');
    Route::get('assignments/export-assignments/open/view', 'ExportAssignmentController@view_open_export_assignments')->name('accountant.assignments.export.open.view');
    Route::get('assignments/import/close/view', 'ImportAssignmentController@view_closed_import_assignments')->name('accountant.assignments.import.close.view');
    Route::get('assignments/export/close/view', 'ExportAssignmentController@view_closed_export_assignments')->name('accountant.assignments.export.close.view');

    Route::get('assignments/export/close/download/{filenames?}', 'ExportAssignmentController@get_files_closed_exp')->where('filenames', '(.*)')->name('accountant.assignments.export.close.getfiles');
    Route::get('assignments/export/open/download/{filenames?}', 'ExportAssignmentController@get_files_open_exp')->where('filenames', '(.*)')->name('accountant.assignments.export.open.getfiles');
    Route::get('assignments/import/close/download/{filenames?}', 'ImportAssignmentController@get_files_closed_imp')->where('filenames', '(.*)')->name('accountant.assignments.import.close.getfiles');
    Route::get('assignments/import/open/download/{filenames?}', 'ImportAssignmentController@get_files_open_imp')->where('filenames', '(.*)')->name('accountant.assignments.import.open.getfiles');


    //import assignments
    Route::get('/assignments/import/view', 'ImportAssignmentController@view_import_assignments')->name('accountant.assignments.import.view');
    Route::post('assignments/import/create', 'ImportAssignmentController@create_import_assignment')->name('accountant.assignment.import.create');
    Route::put('assignments/import/update/{id}', 'ImportAssignmentController@update_import_assignment')->name('accountant.assignment.import.update');
    Route::delete('assignments/import/delete/{id}', 'ImportAssignmentController@delete_import_assignment')->name('accountant.assignment.import.delete');
    Route::put('assignments/import/open/{id}', 'ImportAssignmentController@open_import_assignment')->name('accountant.assignment.import.open');
    Route::put('assignments/import/close/{id}', 'ImportAssignmentController@close_import_assignment')->name('accountant.assignment.import.close');
    // Route::get('assignments/import/{filenames?}', 'ImportAssignmentController@get_files')->where('filenames','(.*)')->name('accountant.assignment.files.get');
    Route::get('assignments/import/download/{filenames?}', 'ImportAssignmentController@get_files')->where('filenames','(.*)')->name('accountant.assignment.import.files');


    //export assignments
    Route::get('/assignments/export/view', 'ExportAssignmentController@view_export_assignments')->name('accountant.assignments.export.view');
    Route::post('assignments/export/create', 'ExportAssignmentController@create_export_assignment')->name('accountant.assignment.export.create');
    Route::put('assignments/export/update/{id}', 'ExportAssignmentController@update_export_assignment')->name('accountant.assignment.export.update');
    Route::delete('assignments/export/delete/{id}', 'ExportAssignmentController@delete_export_assignment')->name('accountant.assignment.export.delete');
    Route::put('assignments/export/open/{id}', 'ExportAssignmentController@open_export_assignment')->name('accountant.assignment.export.open');
    Route::put('assignments/export/close/{id}', 'ExportAssignmentController@close_export_assignment')->name('accountant.assignment.export.close');
    Route::get('assignments/export/download/{filenames?}', 'ExportAssignmentController@get_files')->where('filenames','(.*)')->name('accountant.assignment.export.files');


    //imports
    Route::get('/assignments/imports/view', 'ImportController@view_imports')->name('accountant.imports.view');
    Route::post('/assignments/imports/create', 'ImportController@create_import')->name('accountant.imports.create');
    Route::put('/assignments/imports/update/{id}', 'ImportController@update_import')->name('accountant.imports.update');
    Route::delete('/assignments/imports/delete/{id}', 'ImportController@delete_import')->name('accountant.imports.delete');
    Route::get('assignments/imports/warehouse/{id}', 'WarehouseController@get_employees_imp'); //ajax route
    Route::get('assignments/imports/download/{filename?}', 'ImportController@get_deltio_imp')->where('filename', '(.*)')->name('accountant.imports.deltio.download');


    //exports
    Route::get('/assignments/exports/view', 'ExportController@view_exports')->name('accountant.exports.view');
    Route::post('/assignments/exports/create', 'ExportController@create_export')->name('accountant.exports.create');
    Route::put('/assignments/exports/update/{id}', 'ExportController@update_export')->name('accountant.exports.update');
    Route::delete('/assignments/exports/delete/{id}', 'ExportController@delete_export')->name('accountant.exports.delete');
    Route::get('assignments/exports/warehouse/{id}', 'WarehouseController@get_employees_exp'); //ajax route


    Route::get('/users/show/pic/{photo?}', 'UserController@show_userpic')->where('photo', '(.*)')->name('accountant.user.show.userpic');

    Route::get('/employees/view', 'EmployeeController@view_employees')->name('accountant.employees.view');
    Route::post('/employees/create', 'EmployeeController@create_employee')->name('accountant.employees.create');
    Route::put('/employees/update/{id}', 'EmployeeController@update_employee')->name('accountant.employees.update');
    Route::delete('/employees/delete/{id}', 'EmployeeController@delete_employee')->name('accountant.employees.delete');
    Route::get('/employees/company/{id}', 'CompanyController@get_warehouses')->name('accountant.companywarehouses'); //ajax route

    Route::get('/companies/view', 'CompanyController@view_companies')->name('accountant.companies.view');
    Route::post('/companies/create', 'CompanyController@create_company')->name('accountant.companies.create');
    Route::put('/companies/update/{id}', 'CompanyController@update_company')->name('accountant.companies.update');
    Route::delete('/companies/delete/{id}', 'CompanyController@delete_company')->name('accountant.companies.delete');

    Route::get('/shipping-companies/view', 'TransportController@view_transport_companies')->name('accountant.shipping.view');
    Route::post('/shipping-companies/create', 'TransportController@create_transport_company')->name('accountant.shipping.create');
    Route::put('/shipping-companies/update/{id}', 'TransportController@update_transport_company')->name('accountant.shipping.update');
    Route::delete('/shipping-companies/delete/{id}', 'TransportController@delete_transport_company')->name('accountant.shipping.delete');

    Route::get('/warehouses/view', 'WarehouseController@view_warehouses')->name('accountant.warehouses.view');
    Route::post('/warehouses/create', 'WarehouseController@create_warehouse')->name('accountant.warehouses.create');
    Route::put('/warehouses/update/{id}', 'WarehouseController@update_warehouse')->name('accountant.warehouses.update');
    Route::delete('/warehouses/delete/{id}', 'WarehouseController@delete_warehouse')->name('accountant.warehouses.delete');

    Route::get('/warehouse/show/{id}', 'WarehouseController@show_warehouse')->name('accountant.warehouse.show');
});


Route::middleware(['auth', 'foreman'])->prefix('foreman')->group(function(){
    Route::get('/home', 'WarehouseForemanController@home');
    Route::get('/dashboard', 'DashboardController@index')->name('foreman.dashboard');

    Route::get('/tools/view', 'ToolController@view_tools')->name('foreman.tools.view');
    Route::get('/tools/charged/view', 'ToolController@view_charged_tools')->name('foreman.tools.charged.view');
    Route::get('/tools/non-charged/view', 'ToolController@view_non_charged_tools')->name('foreman.tools.noncharged.view');
    Route::get('/tools/my-charged/view', 'ToolController@view_my_charged_tools')->name('foreman.tools.mycharged.view');
    Route::put('/tools/charge-tool/{id}', 'ToolController@charge_tool')->name('foreman.tools.charge');
    Route::put('/tools/uncharge-tool/{id}', 'ToolController@uncharge_tool')->name('foreman.tools.uncharge');
    Route::post('/tools/create', 'ToolController@create_tool')->name('foreman.tools.create');
    Route::put('/tools/update/{id}', 'ToolController@update_tool')->name('foreman.tools.update');
    Route::delete('/tools/delete/{id}', 'ToolController@delete_tool')->name('foreman.tools.delete');
    Route::get('/tools/download/{filename?}', 'ToolController@get_file')->where('filename', '(.*)')->name('foreman.tools.download.file'); //download xrewstiko arxeio/file
    //Route::get('/charge-toolkit', 'DashboardController@charge_toolkit')->name('foreman.chargetoolkit');
    Route::get('tools/history/view', 'ToolController@view_history')->name('foreman.tools.history.view');

    Route::get('/assignments/view', 'AssignmentController@view_all_assignments')->name('foreman.assignments.view'); //view assignments
    //Route::get('/assignments/import/view', 'AssignmentController@view_import_assignments')->name('foreman.assignments.import.view');
    //Route::get('/assignments/export/view', 'AssignmentController@view_export_assignments')->name('foreman.assignments.export.view');
    //Route::get('/assignments/import/view/{id}', 'AssignmentController@view_import_assignment_byId')->name('foreman.assignment.import.view');
    //Route::get('/assignments/export/view/{id}', 'AssignmentController@view_export_assignment_byId')->name('foreman.assignment.export.view');
    //Route::post('/assignments/import/create', 'AssignmentController@create_import_assignment')->name('foreman.assignment.import.create'); //create import assignment
    //Route::post('/assignments/export/create', 'AssignmentController@create_export_assignment')->name('foreman.assignment.export.create'); //create export assignment
    //Route::put('/assignments/import/update/{id}', 'AssignmentController@update_import_assignment')->name('foreman.assignment.import.update'); //update assignment details
    //Route::put('/assignments/export/update/{id}', 'AssignmentController@update_export_assignment')->name('foreman.assignment.export.update');
    //Route::delete('/assignments/import/delete/{id}', 'AssignmentController@delete_import_assignment')->name('foreman.assignment.import.delete'); //delete assignment
    //Route::delete('/assignments/export/delete/{id}', 'AssignmentController@delete_export_assignment')->name('foreman.assignment.export.delete');

    //Open Import/Export Assignments Views
    Route::get('assignments/import-assignments/open/view', 'ImportAssignmentController@view_open_import_assignments')->name('foreman.assignments.import.open.view');
    Route::get('assignments/export-assignments/open/view', 'ExportAssignmentController@view_open_export_assignments')->name('foreman.assignments.export.open.view');

    Route::get('assignments/export/open/download/{filenames?}', 'ExportAssignmentController@get_files_open_exp')->where('filenames', '(.*)')->name('foreman.assignments.export.open.getfiles');
    Route::get('assignments/import/open/download/{filenames?}', 'ImportAssignmentController@get_files_open_imp')->where('filenames', '(.*)')->name('foreman.assignments.import.open.getfiles');



    //import assignments
    Route::get('/assignments/import/view', 'ImportAssignmentController@view_import_assignments')->name('foreman.assignments.import.view');
    Route::post('assignments/import/create', 'ImportAssignmentController@create_import_assignment')->name('foreman.assignment.import.create');
    Route::put('assignments/import/update/{id}', 'ImportAssignmentController@update_import_assignment')->name('foreman.assignment.import.update');
    Route::delete('assignments/import/delete/{id}', 'ImportAssignmentController@delete_import_assignment')->name('foreman.assignment.import.delete');
    Route::put('assignments/import/open/{id}', 'ImportAssignmentController@open_import_assignment')->name('foreman.assignment.import.open');
    Route::put('assignments/import/close/{id}', 'ImportAssignmentController@close_import_assignment')->name('foreman.assignment.import.close');
    Route::get('assignments/import/{filenames?}', 'ImportAssignmentController@get_files')->where('filenames','(.*)')->name('foreman.assignment.import.files');


    //export assignments
    Route::get('/assignments/export/view', 'ExportAssignmentController@view_export_assignments')->name('foreman.assignments.export.view');
    Route::post('assignments/export/create', 'ExportAssignmentController@create_export_assignment')->name('foreman.assignment.export.create');
    Route::put('assignments/export/update/{id}', 'ExportAssignmentController@update_export_assignment')->name('foreman.assignment.export.update');
    Route::delete('assignments/export/delete/{id}', 'ExportAssignmentController@delete_export_assignment')->name('foreman.assignment.export.delete');
    Route::put('assignments/export/open/{id}', 'ExportAssignmentController@open_export_assignment')->name('foreman.assignment.export.open');
    Route::put('assignments/export/close/{id}', 'ExportAssignmentController@close_export_assignment')->name('foreman.assignment.export.close');
    Route::get('assignments/export/download/{filenames?}', 'ExportAssignmentController@get_files')->where('filenames','(.*)')->name('foreman.assignment.export.files');


    //imports
    Route::get('/assignments/imports/view', 'ImportController@view_imports')->name('foreman.imports.view');
    Route::post('/assignments/imports/create', 'ImportController@create_import')->name('foreman.imports.create');
    Route::put('/assignments/imports/update/{id}', 'ImportController@update_import')->name('foreman.imports.update');
    Route::delete('/assignments/imports/delete/{id}', 'ImportController@delete_import')->name('foreman.imports.delete');
    Route::get('assignments/imports/warehouse/{id}', 'WarehouseController@get_employees_imp'); //ajax route
    Route::get('assignments/imports/download/{filename?}', 'ImportController@get_deltio_imp')->where('filename', '(.*)')->name('foreman.imports.deltio.download');


    //exports
    Route::get('/assignments/exports/view', 'ExportController@view_exports')->name('foreman.exports.view');
    Route::post('/assignments/exports/create', 'ExportController@create_export')->name('foreman.exports.create');
    Route::put('/assignments/exports/update/{id}', 'ExportController@update_export')->name('foreman.exports.update');
    Route::delete('/assignments/exports/delete/{id}', 'ExportController@delete_export')->name('foreman.exports.delete');
    Route::get('assignments/exports/warehouse/{id}', 'WarehouseController@get_employees_exp'); //ajax route

    Route::get('/products/view', 'ProductController@view_products')->name('foreman.products.view'); //view products
    Route::get('/product/view/{id}', 'DashboardController@view_product')->name('foreman.product.view'); //view a single product
    Route::post('/products/create', 'ProductController@create_product')->name('foreman.product.create'); //create new product
    Route::put('/products/update/{id}', 'ProductController@update_product')->name('foreman.product.update'); //update product
    Route::delete('/products/delete/{id}', 'ProductController@delete_product')->name('foreman.product.delete'); //delete product
    Route::get('/products/type/{id}', 'CategoryController@get_types')->name('foreman.categorytypes'); //ajax route

    Route::get('/product_category/view', 'CategoryController@view_categories')->name('foreman.category.view');
    Route::post('/product_category/create', 'CategoryController@create_category')->name('foreman.category.create');
    Route::put('/product_category/update/{id}', 'CategoryController@update_category')->name('foreman.category.update');
    Route::delete('/product_category/delete/{id}', 'CategoryController@delete_category')->name('foreman.category.delete');

    Route::get('/product_type/view', 'TypeController@view_types')->name('foreman.type.view');
    Route::post('/product_type/create', 'TypeController@create_type')->name('foreman.type.create');
    Route::put('/product_type/update/{id}', 'TypeController@update_type')->name('foreman.type.update');
    Route::delete('/product_type/delete/{id}', 'TypeController@delete_type')->name('foreman.type.delete');

    Route::get('/meas-units/view', 'MeasureController@view_measunits')->name('foreman.measunit.view');
    Route::post('/meas-units/create', 'MeasureController@create_measunit')->name('foreman.measunit.create');
    Route::put('/meas-units/update/{id}', 'MeasureController@update_measunit')->name('foreman.measunit.update');
    Route::delete('/meas-units/delete/{id}', 'MeasureController@delete_measunit')->name('foreman.measunit.delete');

    Route::get('/warehouses/view', 'WarehouseController@view_warehouses')->name('foreman.warehouses.view');
    Route::post('/warehouses/create', 'WarehouseController@create_warehouse')->name('foreman.warehouses.create');
    Route::put('/warehouses/update/{id}', 'WarehouseController@update_warehouse')->name('foreman.warehouses.update');
    Route::delete('/warehouses/delete/{id}', 'WarehouseController@delete_warehouse')->name('foreman.warehouses.delete');

    Route::get('/warehouse/show/{id}', 'WarehouseController@show_warehouse')->name('foreman.warehouse.show');

    Route::get('/users/show/pic/{photo?}', 'UserController@show_userpic')->where('photo', '(.*)')->name('foreman.user.show.userpic');

});


Route::middleware(['auth', 'worker'])->prefix('worker')->group(function(){
    Route::get('/home', 'WarehouseWorkerController@home');
    Route::get('/dashboard', 'DashboardController@index')->name('worker.dashboard');

    //Route::get('/tools/view', 'ToolController@view_tools')->name('worker.tools.view'); //BUT, he CANNOT see/access Charge/Uncharge Buttons!
    Route::get('/tools/my-charged/view', 'ToolController@view_my_charged_tools')->name('worker.tools.mycharged.view');

    //Open Import/Export Assignments Views
    Route::get('assignments/import-assignments/open/view', 'ImportAssignmentController@view_open_import_assignments')->name('worker.assignments.import.open.view');
    Route::get('assignments/export-assignments/open/view', 'ExportAssignmentController@view_open_export_assignments')->name('worker.assignments.export.open.view');

    Route::get('assignments/export/open/download/{filenames?}', 'ExportAssignmentController@get_files_open_exp')->where('filenames', '(.*)')->name('worker.assignments.export.open.getfiles');
    Route::get('assignments/import/open/download/{filenames?}', 'ImportAssignmentController@get_files_open_imp')->where('filenames', '(.*)')->name('worker.assignments.import.open.getfiles');

    //imports
    Route::get('/assignments/imports/view', 'ImportController@view_imports')->name('worker.imports.view');
    Route::post('/assignments/imports/create', 'ImportController@create_import')->name('worker.imports.create');
    Route::put('/assignments/imports/update/{id}', 'ImportController@update_import')->name('worker.imports.update');
    Route::delete('/assignments/imports/delete/{id}', 'ImportController@delete_import')->name('worker.imports.delete');
    Route::get('assignments/imports/warehouse/{id}', 'WarehouseController@get_employees_imp'); //ajax route
    Route::get('assignments/imports/download/{filename?}', 'ImportController@get_deltio_imp')->where('filename', '(.*)')->name('worker.imports.deltio.download');

    //exports
    Route::get('/assignments/exports/view', 'ExportController@view_exports')->name('worker.exports.view');
    Route::post('/assignments/exports/create', 'ExportController@create_export')->name('worker.exports.create');
    Route::put('/assignments/exports/update/{id}', 'ExportController@update_export')->name('worker.exports.update');
    Route::delete('/assignments/exports/delete/{id}', 'ExportController@delete_export')->name('worker.exports.delete');
    Route::get('assignments/exports/warehouse/{id}', 'WarehouseController@get_employees_exp'); //ajax route


    Route::get('/products/view', 'ProductController@view_products')->name('worker.products.view'); //view products
    Route::get('/product/view/{id}', 'DashboardController@view_product')->name('worker.product.view'); //view a single product
    Route::post('/products/create', 'ProductController@create_product')->name('worker.product.create'); //create new product
    Route::put('/products/update/{id}', 'ProductController@update_product')->name('worker.product.update'); //update product
    Route::delete('/products/delete/{id}', 'ProductController@delete_product')->name('worker.product.delete'); //delete product
    Route::get('/products/type/{id}', 'CategoryController@get_types')->name('worker.categorytypes'); //ajax route

    Route::get('/product_category/view', 'CategoryController@view_categories')->name('worker.category.view');
    Route::post('/product_category/create', 'CategoryController@create_category')->name('worker.category.create');
    Route::put('/product_category/update/{id}','CategoryController@update_category')->name('worker.category.update');
    Route::delete('/product_category/delete/{id}', 'CategoryController@delete_category')->name('worker.category.delete');

    Route::get('/product_type/view', 'TypeController@view_types')->name('worker.type.view');
    Route::post('/product_type/create', 'TypeController@create_type')->name('worker.type.create');
    Route::put('/product_type/update/{id}', 'TypeController@update_type')->name('worker.type.update');
    Route::delete('/product_type/delete/{id}', 'TypeController@delete_type')->name('worker.type.delete');

    Route::get('/meas-units/view', 'MeasureController@view_measunits')->name('worker.measunit.view');
    Route::post('/meas-units/create', 'MeasureController@create_measunit')->name('worker.measunit.create');
    Route::put('/meas-units/update/{id}', 'MeasureController@update_measunit')->name('worker.measunit.update');
    Route::delete('/meas-units/delete/{id}', 'MeasureController@delete_measunit')->name('worker.measunit.delete');

    Route::get('/users/show/pic/{photo?}', 'UserController@show_userpic')->where('photo', '(.*)')->name('worker.user.show.userpic');

});


Route::middleware(['auth', 'technician'])->prefix('technician')->group(function(){

    Route::get('/dashboard', 'DashboardController@index')->name('technician.dashboard');

    Route::get('/tools/my-charged/view', 'ToolController@view_my_charged_tools')->name('technician.tools.mycharged.view');

    Route::get('/users/show/pic/{photo?}', 'UserController@show_userpic')->where('photo', '(.*)')->name('technician.user.show.userpic');


});




Route::middleware(['auth', 'normaluser'])->prefix('user')->group(function(){
    Route::get('/home', 'UserController@home');
    Route::get('/dashboard', 'DashboardController@index')->name('normaluser.dashboard');

    Route::get('/users/show/pic/{photo?}', 'UserController@show_userpic')->where('photo', '(.*)')->name('user.user.show.userpic');


    // Route::get('assignments/import/close/view', 'ImportAssignmentController@view_closed_import_assignments')->name('user.assignments.import.close.view');
    // Route::get('assignments/export/close/view', 'ExportAssignmentController@view_closed_export_assignments')->name('user.assignments.export.close.view');
    // Route::get('assignments/export/close/download/{filenames?}', 'ExportAssignmentController@get_files_closed_exp')->where('filenames', '(.*)')->name('user.assignments.export.close.getfiles');
    // Route::get('assignments/import/close/download/{filenames?}', 'ImportAssignmentController@get_files_closed_imp')->where('filenames', '(.*)')->name('user.assignments.import.close.getfiles');

    Route::get('assignments/import/my/view', 'ImportAssignmentController@view_my_import_assignments')->name('user.assignments.import.my.view');
    Route::get('assignments/export/my/view', 'ExportAssignmentController@view_my_export_assignments')->name('user.assignments.export.my.view');
    Route::get('assignments/import/my/download/{filenames?}', 'ImportAssignmentController@get_files')->where('filenames', '(.*)')->name('user.assignments.import.getfiles');
    Route::get('assignments/export/my/download/{filenames?}', 'ExportAssignmentController@get_files')->where('filenames', '(.*)')->name('user.assignments.export.getfiles');



    Route::get('assignments/import/my/closed/view', 'ImportAssignmentController@view_my_closed_import_assignments')->name('user.assignments.import.my.closed.view');
    Route::get('assignments/export/my/closed/view', 'ExportAssignmentController@view_my_closed_export_assignments')->name('user.assignments.export.my.closed.view');
    Route::get('assignments/import/my/closed/download/{filenames?}', 'ImportAssignmentController@get_files_closed_imp')->where('filenames', '(.*)')->name('user.assignments.import.close.getfiles');
    Route::get('assignments/export/my/closed/download/{filenames?}', 'ExportAssignmentController@get_files_closed_exp')->where('filenames', '(.*)')->name('user.assignments.export.close.getfiles');



    //import assignments
    Route::get('assignments/import/view', 'ImportAssignmentController@view_import_assignments')->name('user.assignments.import.view');
    Route::post('assignments/import/create', 'ImportAssignmentController@create_import_assignment')->name('user.assignment.import.create');
    Route::put('assignments/import/update/{id}', 'ImportAssignmentController@update_import_assignment')->name('user.assignment.import.update');
    Route::delete('assignments/import/delete/{id}', 'ImportAssignmentController@delete_import_assignment')->name('user.assignment.import.delete');
    Route::put('assignments/import/open/{id}', 'ImportAssignmentController@open_import_assignment')->name('user.assignment.import.open');
    Route::put('assignments/import/close/{id}', 'ImportAssignmentController@close_import_assignment')->name('user.assignment.import.close');
    Route::get('assignments/import/{filenames?}', 'ImportAssignmentController@get_files')->where('filenames','(.*)')->name('user.assignment.import.files');

    //export assignments
    Route::get('assignments/export/view', 'ExportAssignmentController@view_export_assignments')->name('user.assignments.export.view');
    Route::post('assignments/export/create', 'ExportAssignmentController@create_export_assignment')->name('user.assignment.export.create');
    Route::put('assignments/export/update/{id}', 'ExportAssignmentController@update_export_assignment')->name('user.assignment.export.update');
    Route::delete('assignments/export/delete/{id}', 'ExportAssignmentController@delete_export_assignment')->name('user.assignment.export.delete');
    Route::put('assignments/export/open/{id}', 'ExportAssignmentController@open_export_assignment')->name('user.assignment.export.open');
    Route::put('assignments/export/close/{id}', 'ExportAssignmentController@close_export_assignment')->name('user.assignment.export.close');
    Route::get('assignments/export/download/{filenames?}', 'ExportAssignmentController@get_files')->where('filenames','(.*)')->name('user.assignment.export.files');




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
