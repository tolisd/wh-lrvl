<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Warehouse;
use App\Employee;


class WarehouseController extends Controller
{
    //
    public function view_warehouses(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])){

            $warehouses = Warehouse::all(); //gets all rows from warehouse table
            $employess  = Employee::all(); //gets all employees from employees table


            return view('warehouses_view', ['warehouses' => $warehouses,
                                            'users'      => $users]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_warehouse(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'])){

            $warehouse = new Warehouse();



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