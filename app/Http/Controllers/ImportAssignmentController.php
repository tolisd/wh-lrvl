<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Importassignment;
use App\Warehouse;
use Carbon\Carbon;

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

            //dd(Carbon::parse($request->input('modal-input-picker-create')));

            $files_data = [];
            if($request->hasfile('modal-input-files-create')){
                foreach($request->file('modal-input-files-create') as $files){
                    /*
                    $name = $files->getClientOriginalName();
                    $files->move('/arxeia/eisagwgi', $name);
                    //$files_data[] = $name;
                    array_push($files_data, $name);
                    */
                    $name = $files->getClientOriginalName();
                    $path = $files->storeAs('arxeia/eisagwgi', $name);
                    $url  = \Storage::url($path);
                    array_push($files_data, $url);
                }
            }

            //dd($files_data);

            /*
            $path = $request->file('modal-input-photo-create')->store('images/profile');  //stored in storage/app/images/profile/
            $url = \Storage::url($path); //stores the full path
            $user->photo_url = $url; //access it in Blade as:: {{ $user->photo_url }}
            */

            $importassignment = new ImportAssignment();

            $importassignment->warehouse_id           = $request->input('modal-input-warehouse-create');
            $importassignment->import_assignment_text = $request->input('modal-input-text-create');
            //$importassignment->import_deadline      = $request->input(strtotime('modal-input-picker-create'));
            //$importassignment->import_deadline        = Carbon::create($request->input('modal-input-picker-create'))->format('d-m-Y H:i');
            $importassignment->import_deadline        = Carbon::createFromFormat('d-m-Y H:i', $request->input('modal-input-picker-create'));
            $importassignment->uploaded_files         = json_encode($files_data);
            $importassignment->comments               = $request->input('modal-input-comments-create');
            $importassignment->is_open                = 1; //true

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

            $files_data = [];
            if($request->hasfile('modal-input-files-edit')){
                foreach($request->file('modal-input-files-edit') as $files){
                    /*
                    $name = $files->getClientOriginalName();
                    $files->move('/arxeia/eisagwgi', $name);
                    //$files_data[] = $name;
                    array_push($files_data, $name);
                    */
                    $name = $files->getClientOriginalName();
                    $path = $files->storeAs('/arxeia/eisagwgi', $name);
                    $url  = \Storage::url($path);
                    array_push($files_data, $url);
                }
            }

            $importassignment = ImportAssignment::findOrFail($id);

            $importassignment->warehouse_id           = $request->input('modal-input-warehouse-edit');
            $importassignment->import_assignment_text = $request->input('modal-input-text-edit');
            $importassignment->import_deadline        = Carbon::createFromFormat('d-m-Y H:i', $request->input('modal-input-picker-edit'));
            $importassignment->uploaded_files         = json_encode($files_data);
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
