<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use Validator;
use App\Warehouse;
use App\Company;
use App\Employee;
use App\User;
use App\Product;

class WarehouseController extends Controller
{
    //
    public function view_warehouses(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])){

            $warehouses = Warehouse::all(); //gets all rows from warehouse table
            $companies  = Company::all();   //gets all rows from company tables
            //$employees = Employee::all();
            //$users = User::all();

            $foremen = User::where('user_type', 'warehouse_foreman')->get();
            $workers = User::where('user_type', 'warehouse_worker')->get();

            return view('warehouses_view', ['warehouses' => $warehouses,
                                            'companies'  => $companies,
                                            'foremen' => $foremen,
                                            'workers' => $workers]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }



    //view single warehouse, along with its employees and its products!
    public function show_warehouse($id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant'])){

            //3 Eloquent queries:
            $warehouse_data = Warehouse::with('products')->where('id', $id)->get(); //eager loading done correctly
            $employees_in_warehouse = Employee::where('warehouse_id', $id)->get(); //returns an eloquent collection
            $products_in_warehouse = Product::has('warehouses')->get();

            return view('warehouse_show', ['warehouse_data'         => $warehouse_data,
                                           'employees_in_warehouse' => $employees_in_warehouse,
                                           'products_in_warehouse'  => $products_in_warehouse]);
        } else {
            return abort(403, 'Sorry, you cannot view this page.');
        }
    }



    public function create_warehouse(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])){

            //validation rules
            $rules = [
                'modal-input-name-create' => 'required|unique:warehouse,name',
                'modal-input-address-create' => 'required',
                'modal-input-city-create' => 'required',
                'modal-input-telno-create' => 'required',
                'modal-input-email-create' => 'required|email',
                'modal-input-company-create' => 'required|exists:company,id',
            ];

            //validation custom messages for rules above
            $custom_messages = [
                'modal-input-name-create.required' => 'Το όνομα απαιτείται',
                'modal-input-address-create.required' => 'Η διεύθυνση απαιτείται',
                'modal-input-city-create.required' => 'Η πόλη απαιτείται',
                'modal-input-telno-create.required' => 'Ο τηλεφωνικός αριθμός απαιτείται',
                'modal-input-email-create.required' => 'Το ηλ.ταχυδρομείο απαιτείται',
                'modal-input-company-create.required' => 'Η δήλωση εταιρείας απαιτείται',
            ];


            $validator = Validator::make($request->all(), $rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){

                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);

                } else if($validator->passes()){

                    $warehouse = new Warehouse();

                    $warehouse->name            = $request->input('modal-input-name-create');
                    $warehouse->address         = $request->input('modal-input-address-create');
                    $warehouse->city            = $request->input('modal-input-city-create');
                    $warehouse->phone_number    = $request->input('modal-input-telno-create');
                    $warehouse->email           = $request->input('modal-input-email-create');
                    //$warehouse->foreman_id      = $request->input('modal-input-foreman-create');
                    /*
                    $workers = [];
                    foreach($request->input('modal-input-workers-create') as $worker){
                        $warehouse->worker_id = $worker;
                    }
                    */
                    //$warehouse->worker_id       = $request->input('modal-input-workers-create'); //it is an array!
                    $warehouse->company_id      = $request->input('modal-input-company-create');

                    $warehouse->save();
                    //array of worker_id(s), does it matter that it is outside the save() loop?
                    /*
                    foreach($request->input('modal-input-workers-create') as $worker)
                    {
                        $warehouse->assign($worker);
                    }
                    */

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


    public function update_warehouse(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])){

            //validation rules
            $rules = [
                'modal-input-name-edit' => ['required', \Illuminate\Validation\Rule::unique('warehouse', 'name')->ignore($id)],
                'modal-input-address-edit' => ['required'],
                'modal-input-city-edit' => ['required'],
                'modal-input-telno-edit' => ['required'],
                'modal-input-email-edit' => ['required','email'],
                'modal-input-company-edit' => ['required','exists:company,id'],
            ];

            //validation custom messages for rules above
            $custom_messages = [
                'modal-input-name-edit.required' => 'Το όνομα απαιτείται',
                'modal-input-address-edit.required' => 'Η διεύθυνση απαιτείται',
                'modal-input-city-edit.required' => 'Η πόλη απαιτείται',
                'modal-input-telno-edit.required' => 'Ο τηλεφωνικός αριθμός απαιτείται',
                'modal-input-email-edit.required' => 'Το ηλ.ταχυδρομείο απαιτείται',
                'modal-input-company-edit.required' => 'Η δήλωση εταιρείας απαιτείται',
            ];


            $validator = Validator::make($request->all(), $rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){

                    //---failure, 422
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);

                } else if($validator->passes()){

                    $warehouse = Warehouse::findOrFail($id);

                    $warehouse->name            = $request->input('modal-input-name-edit');
                    $warehouse->address         = $request->input('modal-input-address-edit');
                    $warehouse->city            = $request->input('modal-input-city-edit');
                    $warehouse->phone_number    = $request->input('modal-input-telno-edit');
                    $warehouse->email           = $request->input('modal-input-email-edit');
                    //$warehouse->foreman         = $request->input('modal-input-foreman-edit');
                    //$warehouse->workers         = json_encode($request->input('modal-input-workers-edit'));
                    $warehouse->company_id     = $request->input('modal-input-company-edit');

                    $warehouse->update($request->all());


                    //---success, 200 OK.
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


    public function delete_warehouse(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])){

            $warehouse = Warehouse::findOrFail($id);
            //if the warehouse is empty of employess and/or products, then go ahead and delete it!
            $warehouse->delete();


            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
