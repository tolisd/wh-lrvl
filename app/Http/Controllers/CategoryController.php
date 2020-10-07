<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use Validator;
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

            $validation_rules = [
                'modal-input-name-create' => 'required',
                'modal-input-description-create' => 'required'
            ];

            $custom_messages = [
                'modal-input-name-create.required' => 'Το όνομα απαιτείται',
                'modal-input-description-create.required' => 'Η περιγραφή απαιτείται'
            ];

            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);


            if($request->ajax()){
                if($validator->fails()){
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){

                    $category = new Category();

                    $category->name            = $request->input('modal-input-name-create');
                    $category->description     = $request->input('modal-input-description-create');

                    $category->save();

                    return \Response::json([
                        'success' => false,
                        //'errors' => $validator->getMessageBag()->toArray(),
                    ], 200);

                }
            }

            /*
            if ($request->ajax()){
                return \Response::json();
            }

             return back();
             */

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


            $validation_rules = [
                'modal-input-name-edit' => 'required',
                'modal-input-description-edit' => 'required'
            ];

            $custom_messages = [
                'modal-input-name-edit.required' => 'Το όνομα απαιτείται',
                'modal-input-description-edit.required' => 'Η περιγραφή απαιτείται'
            ];


            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);


            if($request->ajax()){
                if($validator->fails()){
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){

                    $category = Category::findOrFail($id); //was findOrFail($u_id);

                    $category->name            = $request->input('modal-input-name-edit');
                    $category->description     = $request->input('modal-input-description-edit');

                    $category->update($request->all());

                    return \Response::json([
                        'success' => false,
                        //'errors' => $validator->getMessageBag()->toArray(),
                    ], 200);

                }
            }

            /*
            if ($request->ajax()){
                return \Response::json();
            }

             return back();
            */

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
