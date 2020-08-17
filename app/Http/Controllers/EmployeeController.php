<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Employee;
use App\Warehouse;
use App\Company;


class EmployeeController extends Controller
{
    //
    public function view_employees(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $employees = Employee::all();
            $warehouses = Warehouse::all();
            $companies = Company::all();

            return view('employees_view', ['employees' => $employees,
                                            'warehouses' => $warehouses,
                                            'companies' => $companies]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_employee(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $employee = new Employee();




            $employee->save();


            if ($request->ajax()){
                return \Response::json();
            }

             return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function update_employee(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $employee = Employee::findOrFail($id);




            $employee->update($request->all());


            if ($request->ajax()){
                return \Response::json();
            }

             return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_employee(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $employee = Employee::findOrFail($id);
            //should check if employee/user is logged out first!
            $employee->delete();



            if ($request->ajax()){
                return \Response::json();
            }

             return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
