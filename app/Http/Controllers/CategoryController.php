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
                'modal-input-name-create' => 'required|unique:category,name',
                'modal-input-description-create' => 'required'
            ];

            $custom_messages = [
                'modal-input-name-create.required' => 'Το όνομα απαιτείται',
                'modal-input-name-create.unique' => 'Το όνομα υπάρχει ήδη. Επιλέξτε διαφορετικό όνομα.',
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

                    DB::beginTransaction();

                    try{
                        $category = new Category();

                        $category->name            = $request->input('modal-input-name-create');
                        $category->description     = $request->input('modal-input-description-create');

                        $category->save();

                        DB::commit();

                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);


                    } catch(\Exception $e) {
                        DB::rollBack();

                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);

                    }



                    // return \Response::json([
                    //     'success' => false,
                    //     //'errors' => $validator->getMessageBag()->toArray(),
                    // ], 200);

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
                'modal-input-name-edit' => ['required', \Illuminate\Validation\Rule::unique('category', 'name')->ignore($id)],
                'modal-input-description-edit' => ['required'],
            ];

            $custom_messages = [
                'modal-input-name-edit.required' => 'Το όνομα απαιτείται',
                'modal-input-name-edit.unique' => 'Το όνομα υπάρχει ήδη. Επιλέξτε διαφορετικό όνομα.',
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

                    DB::beginTransaction();

                    try{
                        $category = Category::findOrFail($id); //was findOrFail($u_id);

                        $category->name            = $request->input('modal-input-name-edit');
                        $category->description     = $request->input('modal-input-description-edit');

                        $category->update($request->all());

                        DB::commit();

                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);

                    } catch (\Exception $e) {
                        DB::rollBack();

                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);

                    }



                    // return \Response::json([
                    //     'success' => false,
                    //     //'errors' => $validator->getMessageBag()->toArray(),
                    // ], 200);

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

            if ($request->ajax()){

                $category = Category::findOrFail($id);
                $category->delete();

                return \Response::json();
            }

             return back();

         } else {
              return abort(403, 'Sorry you cannot view this page');
         }

    }

    //ajax json method in products_view.blade.php, for dynamic dropdownlist
    public function get_types($id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            //Query Builder is 10x faster than Eloquent ORM.
            $types = DB::table('types')
                    ->where("category_id", $id)
                    ->pluck("id", "name");

            return json_encode($types);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


}
