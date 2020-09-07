<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth

class MeasureController extends Controller
{
    //
    public function view_measunits(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $measunits = DB::table('measunits')->get();

            return view('measunit_view', ['measunits' => $measunits]);

        } else{
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_measunit(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $measunit = new MeasureUnit();

            $measunit->name            = $request->input('modal-input-name-create');
            $measunit->description     = $request->input('modal-input-description-create');

            $measunit->save();


            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function update_measunit(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $measunit = MeasureUnit::findOrFail($id);

            $measunit->name            = $request->input('modal-input-name-edit');
            $measunit->description     = $request->input('modal-input-description-edit');

            $measunit->update($request->all());


            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else{
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_measunit(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $measunit = MeasureUnit::findOrFail($id);
            $measunit->delete();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();


        } else{
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
