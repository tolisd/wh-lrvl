<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Warehouse;
use App\Company;
use App\Employee;
use App\User;


class WarehouseController extends Controller
{
    //
    public function view_warehouses(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])){

            $warehouses = Warehouse::all(); //gets all rows from warehouse table
            $companies  = Company::all();   //gets all rows from company tables
            //$employees = Employee::all();
            //$users = User::all();

            $foremen = User::where('user_type', 'warehouse_foreman')->get(); //eager loading
            $workers = User::where('user_type', 'warehouse_worker')->get(); //eager loading

            return view('warehouses_view', ['warehouses' => $warehouses,
                                            'companies'  => $companies,
                                            'foremen' => $foremen,
                                            'workers' => $workers]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


    public function create_warehouse(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])){

            $warehouse = new Warehouse();

            $warehouse->name            = $request->input('modal-input-name-create');
            $warehouse->address         = $request->input('modal-input-address-create');
            $warehouse->city            = $request->input('modal-input-city-create');
            $warehouse->phone_number    = $request->input('modal-input-telno-create');
            $warehouse->email           = $request->input('modal-input-email-create');
            $warehouse->foreman         = $request->input('modal-input-foreman-create');
            $warehouse->workers         = json_encode($request->input('modal-input-workers-create'));
            $warehouse->company_id      = $request->input('modal-input-company-create');

            $warehouse->save();


            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    public function update_warehouse(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])){

            $warehouse = Warehouse::findOrFail($id);

            $warehouse->name            = $request->input('modal-input-name-edit');
            $warehouse->address         = $request->input('modal-input-address-edit');
            $warehouse->city            = $request->input('modal-input-city-edit');
            $warehouse->phone_number    = $request->input('modal-input-telno-edit');
            $warehouse->email           = $request->input('modal-input-email-edit');
            $warehouse->foreman         = $request->input('modal-input-foreman-edit');
            $warehouse->workers         = json_encode($request->input('modal-input-workers-edit'));
            $warehouse->company_id     = $request->input('modal-input-company-edit');



            $warehouse->update($request->all());


            if ($request->ajax()){
                return \Response::json();
            }

            return back();

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
