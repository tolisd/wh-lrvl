<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Employee;
use App\Warehouse;
use App\Company;
use App\User;


class EmployeeController extends Controller
{
    //
    public function view_employees(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $users = User::all();
            $employees = Employee::all();
            $warehouses = Warehouse::all();
            $companies = Company::all();

            return view('employees_view', ['employees' => $employees,
                                            'warehouses' => $warehouses,
                                            'companies' => $companies,
                                            'users' => $users]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    //Create a new employee. An employee is NOT necessarily a User of the system.
    //An employee belongs to a warehouse and to a company (to WHICH company THIS warehouse belongs to!)
    public function create_employee(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $employee = new Employee();

            $employee->name             = $request->input('modal-input-name-create');
            $employee->type             = $request->input('modal-input-role-create');
            $employee->address          = $request->input('modal-input-address-create');
            $employee->phone_number     = $request->input('modal-input-telno-create');
            $employee->email            = $request->input('modal-input-email-create');
            $employee->company_id       = $request->input('modal-input-company-create');
            $employee->warehouse_id     = $request->input('modal-input-warehouse-create');

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

            $employee->user_id       = $request->input('modal-input-name-edit');
            //$employee->user->user_type  = $request->input('modal-input-role-edit');
            $employee->address          = $request->input('modal-input-address-edit');
            $employee->phone_number     = $request->input('modal-input-telno-edit');
            $employee->email            = $request->input('modal-input-email-edit');
            $employee->company_id    = $request->input('modal-input-company-edit');
            $employee->warehouse_id  = $request->input('modal-input-warehouse-edit');

            $employee->update($request->all()); //or $request->only(['', '', ...]) ??


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
