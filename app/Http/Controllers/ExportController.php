<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Export;
use App\Exportassignment;
use App\Company;

class ExportController extends Controller
{
    //
    public function view_exports(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $exportassignments = ExportAssignment::all();
            $exports = Export::all();
            $companies = Company::all();

            return view('exports_view', ['exportassignments' => $exportassignments,
                                            'companies' => $companies,
                                            'exports' => $exports]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_export(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $export = new Export();



            $export->save();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function update_export(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $export = Export::findOrFail($id);




            $export->update($request->all());

            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_export(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $export = Export::findOrFail($id);
            $export->delete();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
