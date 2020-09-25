<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Exportassignment;
use App\Warehouse;
use Carbon\Carbon;


class ExportAssignmentController extends Controller
{
    //
    public function view_export_assignments(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            $exportassignments = ExportAssignment::all();
            $warehouses = Warehouse::all();

            return view('exportassignments_view', ['exportassignments' => $exportassignments,
                                                    'warehouses' => $warehouses]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_export_assignment(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            $files_data = [];
            if($request->hasfile('modal-input-files-create')){
                foreach($request->file('modal-input-files-create') as $files){
                    /*
                    $name = $files->getClientOriginalName();
                    $files->move('/arxeia/eksagwgi', $name);
                    //$files_data[] = $name;
                    array_push($files_data, $name);
                    */
                    $name = $files->getClientOriginalName();
                    $path = $files->storeAs('/arxeia/eksagwgi', $name);
                    $url  = \Storage::url($path);
                    array_push($files_data, $url);
                }
            }

            $exportassignment = new ExportAssignment();

            $exportassignment->warehouse_id           = $request->input('modal-input-warehouse-create');
            $exportassignment->export_assignment_text = $request->input('modal-input-text-create');
            $exportassignment->export_deadline        = Carbon::createFromFormat('d-m-Y H:i', $request->input('modal-input-picker-create'));
            $exportassignment->uploaded_files         = json_encode($files_data);
            $exportassignment->comments               = $request->input('modal-input-comments-create');
            $exportassignment->is_open                = true;

            $exportassignment->save();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function update_export_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            $files_data = [];
            if($request->hasfile('modal-input-files-edit')){
                foreach($request->file('modal-input-files-edit') as $files){
                    /*
                    $name = $files->getClientOriginalName();
                    $files->move('/arxeia/eksagwgi', $name);
                    //$files_data[] = $name;
                    array_push($files_data, $name);
                    */
                    $name = $files->getClientOriginalName();
                    $path = $files->storeAs('/arxeia/eksagwgi', $name);
                    $url  = \Storage::url($path);
                    array_push($files_data, $url);
                }
            }

            $exportassignment = ExportAssignment::findOrFail($id);

            $exportassignment->warehouse_id           = $request->input('modal-input-warehouse-edit');
            $exportassignment->export_assignment_text = $request->input('modal-input-text-edit');
            $exportassignment->export_deadline        = Carbon::createFromFormat('d-m-Y H:i', $request->input('modal-input-picker-edit'));
            $exportassignment->uploaded_files         = json_encode($files_data);
            $exportassignment->comments               = $request->input('modal-input-comments-edit');
            $exportassignment->is_open                = $request->input('modal-input-isopen-edit');


            $exportassignment->update($request->all());


            if ($request->ajax()){
                return \Response::json();
            }

            return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_export_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            $exportassignment = ExportAssignment::findOrFail($id);
            $exportassignment->delete();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
