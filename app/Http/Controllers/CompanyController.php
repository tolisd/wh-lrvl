<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Employee;
use App\Warehouse;
use App\Company;

class CompanyController extends Controller
{
    //
    public function view_companies(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $employees = Employee::all();
            $warehouses = Warehouse::all();
            $companies = Company::all();

            return view('companies_view', ['employees' => $employees,
                                            'warehouses' => $warehouses,
                                            'companies' => $companies]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_company(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $company = new Company();


            $company->save();

            if ($request->ajax()){
                return \Response::json();
            }

             return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function update_company(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $company = Company::findOrFail($id);



            $company->update($request->all());


            if ($request->ajax()){
                return \Response::json();
            }

             return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_company(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $company = Company::findOrFail($id);
            $company->delete();

            if ($request->ajax()){
                return \Response::json();
            }

             return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
