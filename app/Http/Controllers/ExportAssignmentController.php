<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use Validator;
use App\Exportassignment;
use App\Warehouse;
use App\User;
use Carbon\Carbon;


class ExportAssignmentController extends Controller
{
    //
    public function view_export_assignments(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            $exportassignments = ExportAssignment::all();
            $warehouses = Warehouse::all();
            $users = User::all();

            // $u_id                        = Auth::user()->id;
            // $user                        = User::findOrFail($u_id);
            // $export_assignments_perUser  = ExportAssignment::where('user_id', '=', $user->id)->get();

            $wh_ids = [];
            foreach($exportassignments as $ea){
                array_push($wh_ids, $ea->warehouse_id);
            }



            $export_assignments_perUser = ExportAssignment::whereIn('warehouse_id', $wh_ids)
                                                          ->get();



            return view('exportassignments_view', ['exportassignments' => $exportassignments,
                                                    'warehouses' => $warehouses,
                                                    'users' => $users,
                                                    'export_assignments_perUser' => $export_assignments_perUser,
                                                ]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function view_open_export_assignments(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker'])){

            $exportassignments = ExportAssignment::where('is_open', '=', 1)->get();
            $warehouses = Warehouse::all();
            $users = User::all();


            // $u_id                    = Auth::user()->id;
            // $user                    = User::findOrFail($u_id);
            // $open_export_assignments = ExportAssignment::where('is_open', '=', 1)->where('user_id', '=', $user->id)->get();


            $wh_ids = [];
            foreach($exportassignments as $ea){
                array_push($wh_ids, $ea->warehouse_id);
            }

            $open_export_assignments_frmn_wrkr = ExportAssignment::whereIn('warehouse_id', $wh_ids)
                                                                ->where('is_open', '=', 1)
                                                                ->get();



            return view('exportassignmentsopen_view', ['exportassignments' => $exportassignments,
                                                    'warehouses' => $warehouses,
                                                    'users' => $users,
                                                    'open_export_assignments_frmn_wrkr' => $open_export_assignments_frmn_wrkr,
                                                    ]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


    public function view_closed_export_assignments(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $exportassignments = ExportAssignment::where('is_open', '=', 0)->get();
            $warehouses = Warehouse::all();
            $users = User::all();

            return view('exportassignmentsclosed_view', ['exportassignments' => $exportassignments,
                                                        'warehouses' => $warehouses,
                                                        'users' => $users]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


    public function create_export_assignment(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){


            $validation_rules = [
                'modal-input-warehouse-create' => 'required|exists:warehouse,id',
                'modal-input-text-create' => 'required',
                'modal-input-picker-create' => 'required',
                'modal-input-files-create.*' => 'required|mimes:pdf,txt,zip,doc,docx', //mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
                'modal-input-comments-create' => 'required',
            ];

            $custom_messages = [
                'modal-input-warehouse-create.required' => '?? ?????????????? ????????????????????',
                'modal-input-text-create.required' => '???? ?????????????? ???????????????? ????????????????????',
                'modal-input-picker-create.required' => '?? ????????????????????/?????? ????????????????????',
                'modal-input-files-create.*.required' => '???????????????????? ?????????????????????? 1 ????????????',
                'modal-input-files-create.*.mimes' => '???? ???????????? ????????????. ?????????? ?????????????? ?????? ????????????????????????????: pdf, txt, doc, docx.',
                'modal-input-comments-create.required' => '???? ???????????? ??????????????????????',
            ];

            //prepare the $validator variable here
            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){

                    //failure, 422
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);

                }

                if($validator->passes()){

                    DB::beginTransaction();

                    try{
                        $files_data = [];
                        if($request->hasFile('modal-input-files-create')){
                            foreach($request->file('modal-input-files-create') as $files){
                                /*
                                $name = $files->getClientOriginalName();
                                $files->move('/arxeia/eksagwgi', $name);
                                //$files_data[] = $name;
                                array_push($files_data, $name);
                                */
                                $datetime_now = date_create();
                                $datetime = date_format($datetime_now, 'YmdHis');
                                $name = $datetime . '-' . $files->getClientOriginalName();
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
                        $exportassignment->is_open                = 1;

                        $exportassignment->user_id                = Auth::user()->id;

                        //Create a code for this assignment, 10 digits long, and get it from the input text as hashed text!
                        // $exportassignment->export_assignment_code = strtoupper(substr(\Hash::make($request->input('modal-input-text-create')), -10));

                        $digits = 5; //a random integer between 10,000 and 99,999 as my assignment code
                        $exportassignment->export_assignment_code =  rand(pow(10, $digits-1), pow(10, $digits)-1);

                        $exportassignment->save();


                        DB::commit();

                        //success, 200
                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);

                    } catch (\Exception $e) {
                        DB::rollBack();

                        //failure, 500
                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);

                    }


                    // //success, 200
                    // return \Response::json([
                    //     'success' => true,
                    //     //'errors' => $validator->getMessageBag()->toArray(),
                    // ], 200);
                }
            }


            /*
            if ($request->ajax()){
                return \Response::json();
            }

            return back();
            */
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function update_export_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            $validation_rules = [
                'modal-input-warehouse-edit' => 'required|exists:warehouse,id',
                'modal-input-text-edit' => 'required',
                'modal-input-picker-edit' => 'required',
                'modal-input-files-edit.*' => 'required|mimes:pdf,txt,zip,doc,docx', //mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
                'modal-input-comments-edit' => 'required',
                'modal-input-isopen-edit' => 'required',
            ];

            $custom_messages = [
                'modal-input-warehouse-edit.required' => '?? ?????????????? ????????????????????',
                'modal-input-text-edit.required' => '???? ?????????????? ???????????????? ????????????????????',
                'modal-input-picker-edit.required' => '?? ????????????????????/?????? ????????????????????',
                'modal-input-files-edit.*.required' => '???????????????????? ?????????????????????? 1 ????????????',
                'modal-input-files-edit.*.mimes' => '???? ???????????? ????????????. ?????????? ?????????????? ?????? ????????????????????????????: pdf, txt, doc, docx.',
                'modal-input-comments-edit.required' => '???? ???????????? ??????????????????????',
                'modal-input-isopen-edit.required' => '???? ?????????? ??????????????/?????????????? ????????????????????',
            ];


            //prepare the $validator variable here
            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);


            if($request->ajax()){

                if($validator->fails()){

                    //failure, 422
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);

                }

                if($validator->passes()){

                    DB::beginTransaction();

                    try{
                        $files_data = [];
                        if($request->hasFile('modal-input-files-edit')){
                            foreach($request->file('modal-input-files-edit') as $files){
                                /*
                                $name = $files->getClientOriginalName();
                                $files->move('/arxeia/eksagwgi', $name);
                                //$files_data[] = $name;
                                array_push($files_data, $name);
                                */
                                $datetime_now = date_create();
                                $datetime = date_format($datetime_now, 'YmdHis');
                                $name = $datetime . '-' . $files->getClientOriginalName();
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

                        $exportassignment->user_id                = Auth::user()->id;


                        $exportassignment->update($request->all());


                        DB::commit();

                        //success, 200
                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);

                    } catch (\Exception $e) {
                        DB::rollBack();

                        //failure, 500
                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);
                    }

                    // //success, 200
                    // return \Response::json([
                    //     'success' => true,
                    //     //'errors' => $validator->getMessageBag()->toArray(),
                    // ], 200);
                }
            }



            /*
            if ($request->ajax()){
                return \Response::json();
            }

            return back();
            */
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_export_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){


            if ($request->ajax()){
                $exportassignment = ExportAssignment::findOrFail($id);
                $exportassignment->delete();

                return \Response::json();
            }

            return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }



    public function open_export_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){


            if ($request->ajax()){
                DB::beginTransaction();

                try{
                    $exportassignment = ExportAssignment::findOrFail($id);

                    $exportassignment->is_open = 1; //open the assignment

                    $exportassignment->update($request->all());
                    //$export->update($request->only(['modal-input--']));

                    DB::commit();

                    //success, 200
                    return \Response::json([
                        'success' => true,
                        //'errors' => $validator->getMessageBag()->toArray(),
                    ], 200);

                } catch (\Exception $e) {
                    DB::rollBack();

                    //failure, 500
                    return \Response::json([
                        'success' => false,
                        'message' => $e->getMessage(),
                        //'errors' => $validator->getMessageBag()->toArray(),
                    ], 500);

                }
            }


            return back();
            // if ($request->ajax()){
            //     return \Response::json([
            //         'success' => true,
            //     ], 200);
            // }


        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }



    public function close_export_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            if ($request->ajax()){
                DB::beginTransaction();

                try{
                    $exportassignment = ExportAssignment::findOrFail($id);

                    $exportassignment->is_open = 0; //close the assignment

                    $exportassignment->update($request->all());
                    //$export->update($request->only(['modal-input--']));

                    DB::commit();

                    //success, 200
                    return \Response::json([
                        'success' => true,
                        //'errors' => $validator->getMessageBag()->toArray(),
                    ], 200);

                } catch (\Exception $e) {
                    DB::rollBack();

                    //failure, 500
                    return \Response::json([
                        'success' => false,
                        'message' => $e->getMessage(),
                        //'errors' => $validator->getMessageBag()->toArray(),
                    ], 500);

                }
            }


            return back();

            // if ($request->ajax()){
            //     return \Response::json([
            //         'success' => true,
            //     ], 200);
            // }

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }



    public function get_files(Request $request, $filenames){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){


                $path_to_file = 'arxeia'.DIRECTORY_SEPARATOR.'eksagwgi'.DIRECTORY_SEPARATOR . $filenames;

                //$filename is the value that i pass from the view as route parameter!
                //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]



                if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path. so in our example we pass directly the path (/.../laravelProject/storage/app) is the default one (referenced with the helper storage_path('app')


                    // $name = substr($filenames, 15);
                    $name = str_replace(' ', '_', substr($filenames, 15));


                    if(substr($name, -4) == '.txt'){

                        $headers = [
                            // 'Content-Type' => 'application/pdf',
                            'Content-Type' => 'text/plain',
                            'Cache-Control' => 'no-cache, no-store, must-revalidate',
                            'Pragma' => 'no-cache',
                            'Expires' => '0',
                            // // 'Content-Disposition' => 'attachment',
                            'Content-Disposition' => 'attachment; filename="'.$name.'"',
                        ];

                    } else if(substr($name, -4) == '.pdf'){

                        $headers = [
                            'Content-Type' => 'application/pdf',
                            // 'Content-Type' => 'text/plain',
                            'Cache-Control' => 'no-cache, no-store, must-revalidate',
                            'Pragma' => 'no-cache',
                            'Expires' => '0',
                            // 'Content-Disposition' => 'attachment',
                            'Content-Disposition' => 'attachment; filename="'.$name.'"',
                        ];

                    } else if(substr($name, -4) == '.doc'){

                        $headers = [
                            // 'Content-Type' => 'application/pdf',
                            // 'Content-Type' => 'text/plain',
                            'Content-Type' => 'application/msword',
                            'Cache-Control' => 'no-cache, no-store, must-revalidate',
                            'Pragma' => 'no-cache',
                            'Expires' => '0',
                            // 'Content-Disposition' => 'attachment',
                            'Content-Disposition' => 'attachment; filename="'.$name.'"',
                        ];

                    } else {

                        $headers = [
                            // 'Content-Type' => 'application/pdf',
                            // 'Content-Type' => 'text/plain',
                            'Cache-Control' => 'no-cache, no-store, must-revalidate',
                            'Pragma' => 'no-cache',
                            'Expires' => '0',
                            // 'Content-Disposition' => 'attachment',
                            'Content-Disposition' => 'attachment; filename="'.$name.'"',
                        ];

                    }


                    return \Storage::download($path_to_file, $name, $headers);


                } else {
                    return abort('404', '???? ???????????? ?????? ??????????????'); // we redirect to 404 page if it doesn't exist
                }



        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }



    public function get_files_closed_exp(Request $request, $filenames){


        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isNormalUser'])){


            $path_to_file = 'arxeia'.DIRECTORY_SEPARATOR.'eksagwgi'.DIRECTORY_SEPARATOR . $filenames;

            //$filename is the value that i pass from the view as route parameter!
            //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]


            if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path. so in our example we pass directly the path (/.../laravelProject/storage/app) is the default one (referenced with the helper storage_path('app')


                // $name = substr($filenames, 15);
                $name = str_replace(' ', '_', substr($filenames, 15));


                if(substr($name, -4) == '.txt'){

                    $headers = [
                        // 'Content-Type' => 'application/pdf',
                        'Content-Type' => 'text/plain',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                } else if(substr($name, -4) == '.pdf'){

                    $headers = [
                        'Content-Type' => 'application/pdf',
                        // 'Content-Type' => 'text/plain',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                } else if(substr($name, -4) == '.doc'){

                    $headers = [
                        // 'Content-Type' => 'application/pdf',
                        // 'Content-Type' => 'text/plain',
                        'Content-Type' => 'application/msword',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                } else {

                    $headers = [
                        // 'Content-Type' => 'application/pdf',
                        // 'Content-Type' => 'text/plain',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                }


                return \Storage::download($path_to_file, $name, $headers);


            } else {
                return abort('404', '???? ???????????? ?????? ??????????????'); // we redirect to 404 page if it doesn't exist
            }



        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    public function get_files_open_exp(Request $request, $filenames){


        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman', 'isWarehouseWorker'])){


            $path_to_file = 'arxeia'.DIRECTORY_SEPARATOR.'eksagwgi'.DIRECTORY_SEPARATOR . $filenames;

            //$filename is the value that i pass from the view as route parameter!
            //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]


            if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path. so in our example we pass directly the path (/.../laravelProject/storage/app) is the default one (referenced with the helper storage_path('app')


                // $name = substr($filenames, 15);
                $name = str_replace(' ', '_', substr($filenames, 15));


                if(substr($name, -4) == '.txt'){

                    $headers = [
                        // 'Content-Type' => 'application/pdf',
                        'Content-Type' => 'text/plain',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                } else if(substr($name, -4) == '.pdf'){

                    $headers = [
                        'Content-Type' => 'application/pdf',
                        // 'Content-Type' => 'text/plain',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                } else if(substr($name, -4) == '.doc'){

                    $headers = [
                        // 'Content-Type' => 'application/pdf',
                        // 'Content-Type' => 'text/plain',
                        'Content-Type' => 'application/msword',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                } else {

                    $headers = [
                        // 'Content-Type' => 'application/pdf',
                        // 'Content-Type' => 'text/plain',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                }


                return \Storage::download($path_to_file, $name, $headers);


            } else {
                return abort('404', '???? ???????????? ?????? ??????????????'); // we redirect to 404 page if it doesn't exist
            }



        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    public function view_my_export_assignments(){

        if(\Gate::any(['isNormalUser'])){

            $u_id        = Auth::user()->id;  //get the authenticated user's ID

            //the following is 2 queries, it looks much shorter and cleaner!
            $user                  = User::findOrFail($u_id);
            $my_export_assignments = ExportAssignment::where('user_id', $user->id)->where('is_open', '=', 1)->get();   //via its FK


            return view('exportassignments_my_view', ['my_export_assignments' => $my_export_assignments]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function view_my_closed_export_assignments(){

        if(\Gate::any(['isNormalUser'])){

            $u_id        = Auth::user()->id;  //get the authenticated user's ID

            //the following is 2 queries, it looks much shorter and cleaner!
            $user                         = User::findOrFail($u_id);
            $my_closed_export_assignments = ExportAssignment::where('user_id', $user->id)->where('is_open', '=', 0)->get();   //via its FK


            return view('exportassignments_myclosed_view', ['my_closed_export_assignments' => $my_closed_export_assignments]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }




}
