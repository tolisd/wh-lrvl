<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Importassignment;
use App\Warehouse;

class ImportAssignmentController extends Controller
{
    //
    public function view_import_assignments(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            $importassignments = ImportAssignment::all();
            $warehouses = Warehouse::all();

            return view('importassignments_view', ['importassignments' => $importassignments,
                                                    'warehouses' => $warehouses]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_import_assignment(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            $importassignment = new ImportAssignment();

            $importassignment->warehouse_id           = $request->input('modal-input-warehouse-create');
            $importassignment->export_assignment_text = $request->input('modal-input-text-create');
            $importassignment->export_deadline        = $request->input('modal-input-deadline-create');
            $importassignment->uploaded_files         = $request->input('modal-input-uploadedfiles-create');
            $importassignment->comments               = $request->input('modal-input-comments-create');
            $importassignment->is_open                = true;


            $importassignment->save();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function update_import_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            $importassignment = ImportAssignment::findOrFail($id);

            $importassignment->warehouse_id           = $request->input('modal-input-warehouse-edit');
            $importassignment->export_assignment_text = $request->input('modal-input-text-edit');
            $importassignment->export_deadline        = $request->input('modal-input-deadline-edit');
            $importassignment->uploaded_files         = $request->input('modal-input-uploadedfiles-edit');
            $importassignment->comments               = $request->input('modal-input-comments-edit');
            $importassignment->is_open                = $request->input('modal-input-isopen-edit');

            $importassignment->update($request->all());

            if ($request->ajax()){
                return \Response::json();
            }

            return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_import_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            $importassignment = ImportAssignment::findOrFail($id);
            $importassignment->delete();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
