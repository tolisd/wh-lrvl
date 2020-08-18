<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Company;

class CompanyController extends Controller
{
    //
    public function view_companies(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $companies = Company::all();

            return view('companies_view', ['companies' => $companies]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_company(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $company = new Company();

            $company->name          = $request->input('modal-input-name-create');
            $company->AFM           = $request->input('modal-input-afm-create');
            $company->DOY           = $request->input('modal-input-doy-create');
            $company->postal_code   = $request->input('modal-input-pcode-create');
            $company->city          = $request->input('modal-input-city-create');
            $company->phone_number  = $request->input('modal-input-telno-create');
            $company->email         = $request->input('modal-input-email-create');

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

            $company = Company::findOrFail($id);  //get this row with [$company->id == $id]

            $company->name          = $request->input('modal-input-name-edit');
            $company->AFM           = $request->input('modal-input-afm-edit');
            $company->DOY           = $request->input('modal-input-doy-edit');
            $company->postal_code   = $request->input('modal-input-pcode-edit');
            $company->city          = $request->input('modal-input-city-edit');
            $company->phone_number  = $request->input('modal-input-telno-edit');
            $company->email         = $request->input('modal-input-email-edit');

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
            //delete a company if it has no employees and no warehouses...
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
