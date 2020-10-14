<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use Validator;
use App\Product;
use App\Type;
use App\Category;

class TypeController extends Controller
{
    //
    //==================================================================================================
    //[PRODUCT] TYPES::

    //View all products types
    public function view_types(Request $request){
        //4 user types -> Admin, CEO, Foreman, Worker
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $types = Type::all(); // Eloquent, both variables.
            $categories = Category::all();

            return view('type_view', ['types' => $types,
                                      'categories' => $categories]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //Create a new product type
    public function create_type(Request $request){
        //4 user types -> Admin, CEO, Foreman, Worker
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $validation_rules = [
                'modal-input-name-create' => 'required',  //also make unique? ..but it is not unique in the DB..
                'modal-input-description-create' => 'required',
                'modal-input-category-create' => 'required|exists:category,id',
            ];

            $custom_messages = [
                'modal-input-name-create.required' => 'Το όνομα απαιτείται',
                'modal-input-description-create.required' => 'Η περιγραφή απαιτείται',
                'modal-input-category-create.required' => 'Η κατηγορία απαιτείται',
            ];


            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){
                    //failure, 422
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){

                    $type = new Type();

                    $type->name            = $request->input('modal-input-name-create');
                    $type->description     = $request->input('modal-input-description-create');
                    $type->category_id     = $request->input('modal-input-category-create');

                    $type->save();

                    //success
                    return \Response::json([
                        'success' => true,
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

    //Edit/Update an existing product type
    public function update_type(Request $request, $id){
        //4 user types -> Admin, CEO, Foreman, Worker
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $validation_rules = [
                'modal-input-name-edit' => 'required', //also make unique? ..but it is not unique in the DB..
                'modal-input-description-edit' => 'required',
                'modal-input-category-edit' => 'required|exists:category,id',
            ];

            $custom_messages = [
                'modal-input-name-edit.required' => 'Το όνομα απαιτείται',
                'modal-input-description-edit.required' => 'Η περιγραφή απαιτείται',
                'modal-input-category-edit.required' => 'Η κατηγορία απαιτείται',
            ];


            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){
                    //failure, 422
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){

                    //update the object in the database
                    $type = Type::findOrFail($id);

                    $type->name            = $request->input('modal-input-name-edit');
                    $type->description     = $request->input('modal-input-description-edit');
                    $type->category_id     = $request->input('modal-input-category-edit');

                    $type->update($request->all());


                    //success
                    return \Response::json([
                        'success' => true,
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

    //Delete an existing product type
    public function delete_type(Request $request, $id){
        //4 user types -> Admin, CEO, Foreman, Worker
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $type = Type::findOrFail($id);
            $type->delete();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
