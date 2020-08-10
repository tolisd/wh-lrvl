<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Product;
use App\Category;

class CategoryController extends Controller
{
    //
    //==================================================================================================
    //[PRODUCT] CATEGORIES::


    //View all product categories
    public function view_categories(Request $request){

        //4 user types -> Admin, CEO, Foreman, Worker
        //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $categories = DB::table('category')->get();

            return view('category_view', ['categories' => $categories]);

        } else {
             return abort(403, 'Sorry you cannot view this page');
        }
    }


    //Create a new product category
    public function create_category(Request $request){

         //4 user types -> Admin, CEO, Foreman, Worker
         //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $category = new Category();

            $category->name            = $request->input('modal-input-name-create');
            $category->description     = $request->input('modal-input-description-create');

            $category->save();

            if ($request->ajax()){
                return \Response::json();
            }

             return back();

         } else {
              return abort(403, 'Sorry you cannot view this page');
         }

    }


    //Update an existing product category
    public function update_category(Request $request, $id){

         //4 user types -> Admin, CEO, Foreman, Worker
         //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $category = Category::findOrFail($id); //was findOrFail($u_id);

            $category->name            = $request->input('modal-input-name-edit');
            $category->description     = $request->input('modal-input-description-edit');

            $category->update($request->all());


            if ($request->ajax()){
                return \Response::json();
            }

             return back();

         } else {
              return abort(403, 'Sorry you cannot view this page');
         }

    }


    //Delete an existing product category
    public function delete_category(Request $request, $id){

         //4 user types -> Admin, CEO, Foreman, Worker

         //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $category = Category::findOrFail($id);
            $category->delete();

            if ($request->ajax()){
                return \Response::json();
            }

             return back();

         } else {
              return abort(403, 'Sorry you cannot view this page');
         }

    }


}
