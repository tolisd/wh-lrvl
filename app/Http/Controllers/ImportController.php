<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Import;
use App\Importassignment;
use App\Company;
use Carbon\Carbon;

class ImportController extends Controller
{
    //
    public function view_imports(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $importassignments = ImportAssignment::all();
            $imports = Import::all();
            $companies = Company::all();

            return view('imports_view', ['importassignments' => $importassignments,
                                                    'companies' => $companies,
                                                    'imports' => $imports]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function create_import(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $import = new Import();




            $import->save();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function update_import(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $import = Import::findOrFail($id);




            $import->update($request->all());

            if ($request->ajax()){
                return \Response::json();
            }

            return back();


        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_import(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $import = Import::findOrFail($id);
            $import->delete();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
