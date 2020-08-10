<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Product;
use App\Type;

class TypeController extends Controller
{
    //
    //==================================================================================================
    //[PRODUCT] TYPES::

    //View all products types
    public function view_types(Request $request){
        //4 user types -> Admin, CEO, Foreman, Worker
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $types = DB::table('types')->get();

            return view('type_view', ['types' => $types]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //Create a new product type
    public function create_type(Request $request){
        //4 user types -> Admin, CEO, Foreman, Worker
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $type = new Type();

            $type->name            = $request->input('modal-input-name-create');
            $type->description     = $request->input('modal-input-description-create');

            $type->save();


            if ($request->ajax()){
                return \Response::json();
            }

            return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //Edit/Update an existing product type
    public function update_type(Request $request, $id){
        //4 user types -> Admin, CEO, Foreman, Worker
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $type = Type::findOrFail($id);

            $type->name            = $request->input('modal-input-name-edit');
            $type->description     = $request->input('modal-input-description-edit');

            $type->update($request->all());


            if ($request->ajax()){
                return \Response::json();
            }

            return back();
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
