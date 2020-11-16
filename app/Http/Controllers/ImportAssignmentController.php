<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use Validator;
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

    public function view_open_import_assignments(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker'])){

            $importassignments = ImportAssignment::where('is_open', '=', 1)->get();
            $warehouses = Warehouse::all();

            return view('importassignmentsopen_view', ['importassignments' => $importassignments,
                                                        'warehouses' => $warehouses]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


    public function view_closed_import_assignments(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $importassignments = ImportAssignment::where('is_open', '=', 0)->get();
            $warehouses = Warehouse::all();

            return view('importassignmentsclosed_view', ['importassignments' => $importassignments,
                                                        'warehouses' => $warehouses]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }




    public function create_import_assignment(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){


            $validation_rules = [
                'modal-input-warehouse-create' => 'required|exists:warehouse,id',
                'modal-input-text-create' => 'required',
                'modal-input-picker-create' => 'required',
                'modal-input-files-create.*' => 'required|mimes:doc,docx,pdf,txt,zip', //mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
                'modal-input-comments-create' => 'required',
            ];

            $custom_messages = [
                'modal-input-warehouse-create.required' => 'Η αποθήκη απαιτείται',
                'modal-input-text-create.required' => 'Το κείμενο ανάθεσης απαιτείται',
                'modal-input-picker-create.required' => 'Η ημερομηνία/ώρα απαιτείται',
                'modal-input-files-create.*.required' => 'Απαιτείται τουλάχιστον 1 αρχείο',
                'modal-input-files-create.*.mimes' => 'Μη έγκυρο αρχείο. Τύποι αρχείων που υποστηρίζονται: pdf, txt, doc, docx',
                'modal-input-comments-create.required' => 'Τα σχόλια απαιτούνται',
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
                         //dd(Carbon::parse($request->input('modal-input-picker-create')));

                        $files_data = [];
                        if($request->hasFile('modal-input-files-create')){
                            foreach($request->file('modal-input-files-create') as $files){
                                /*
                                $name = $files->getClientOriginalName();
                                $files->move('/arxeia/eisagwgi', $name);
                                //$files_data[] = $name;
                                array_push($files_data, $name);
                                */
                                $datetime_now = date_create();
                                $datetime = date_format($datetime_now, 'YmdHis');
                                $name = $datetime . '-' . $files->getClientOriginalName();
                                $path = $files->storeAs('arxeia/eisagwgi', $name);
                                $url  = \Storage::url($path); //stores the full path, stored in storage/app/images/profile/
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

                        //Create a code for this assignment, 10 digits long, and get it from the input text as hashed text!
                        //$importassignment->import_assignment_code = strtoupper(substr(\Hash::make($request->input('modal-input-text-create')), -10));

                        $digits = 5; //a random integer between 10,000 and 99,999 as my assignment code
                        $importassignment->import_assignment_code = rand(pow(10, $digits-1), pow(10, $digits)-1);


                        $importassignment->save();


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

    public function update_import_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){


            $validation_rules = [
                'modal-input-warehouse-edit' => 'required|exists:warehouse,id',
                'modal-input-text-edit' => 'required',
                'modal-input-picker-edit' => 'required',
                'modal-input-files-edit.*' => 'required|mimes:doc,docx,pdf,txt,zip', //mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
                'modal-input-comments-edit' => 'required',
                'modal-input-isopen-edit' => 'required',
            ];

            $custom_messages = [
                'modal-input-warehouse-edit.required' => 'Η αποθήκη απαιτείται',
                'modal-input-text-edit.required' => 'Το κείμενο ανάθεσης απαιτείται',
                'modal-input-picker-edit.required' => 'Η ημερομηνία/ώρα απαιτείται',
                'modal-input-files-edit.*.required' => 'Απαιτείται τουλάχιστον 1 αρχείο',
                'modal-input-files-edit.*.mimes' => 'Μη έγκυρο αρχείο. Τύποι αρχείων που υποστηρίζονται: pdf, txt, doc, docx',
                'modal-input-comments-edit.required' => 'Τα σχόλια απαιτούνται',
                'modal-input-isopen-edit.required' => 'Το πεδίο Ανοικτή/Κλειστή απαιτείται'
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
                                $files->move('/arxeia/eisagwgi', $name);
                                //$files_data[] = $name;
                                array_push($files_data, $name);
                                */
                                $datetime_now = date_create();
                                $datetime = date_format($datetime_now, 'YmdHis');
                                $name = $datetime . '-' . $files->getClientOriginalName();
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

    public function delete_import_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){


            if ($request->ajax()){

                $importassignment = ImportAssignment::findOrFail($id);
                $importassignment->delete();

                return \Response::json();
            }

            return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    public function open_import_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            if ($request->ajax()){

                DB::beginTransaction();

                try{
                    $importassignment = ImportAssignment::findOrFail($id);

                    $importassignment->is_open = 1; //open the assignment

                    $importassignment->update($request->all());
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




    public function close_import_assignment(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isNormalUser'])){

            if ($request->ajax()){

                DB::beginTransaction();

                try{
                    $importassignment = ImportAssignment::findOrFail($id);

                    $importassignment->is_open = 0; //close the assignment

                    $importassignment->update($request->all());
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

                // $files = json_decode($filenames, true);
                // dd($files);

                // "/storage/arxeia/eisagwgi/20201112165651-xrewstiko3.txt"
                // $file = basename($filenames); //"xrewstiko3.txt"
                // dd($file);

                $path_to_file = 'arxeia'.DIRECTORY_SEPARATOR.'eisagwgi'.DIRECTORY_SEPARATOR . $filenames;

                // dd($path_to_file);


                //$filename is the value that i pass from the view as route parameter!
                //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]

                // return \Storage::disk('local')->download($path_to_file);

                if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path. so in our example we pass directly the path (/.../laravelProject/storage/app) is the default one (referenced with the helper storage_path('app')

                    // $name = substr(basename($filenames), 15); //'xrewstiko_arxeio.txt'; fixed value..
                    $name = substr($filenames, 15);


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


                    //force download the file! I also needed to add the "download" html attribute in the view
                    // return response()->download($path_to_file);
                    // File can also be downloaded as: return \Storage::download($path_to_file);, ie without the $headers...

                    // return response()->download($path_to_file, $name, $headers);
                    // return \Response::download($path_to_file, $name, $headers);
                    // return \Storage::disk('local')->download(storage_path($path_to_file), $name, $headers);
                    return \Storage::download($path_to_file, $name, $headers);


                } else {
                    return abort('404', 'Το αρχείο δεν υπάρχει'); // we redirect to 404 page if it doesn't exist
                }



        } else {
            return abort(403, 'Sorry you cannot view this page');
        }


    }


    public function get_files_closed_imp(Request $request, $filenames){


        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){


            $path_to_file = 'arxeia'.DIRECTORY_SEPARATOR.'eisagwgi'.DIRECTORY_SEPARATOR . $filenames;

            //$filename is the value that i pass from the view as route parameter!
            //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]


            if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path. so in our example we pass directly the path (/.../laravelProject/storage/app) is the default one (referenced with the helper storage_path('app')


                $name = substr($filenames, 15);


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
                return abort('404', 'Το αρχείο δεν υπάρχει'); // we redirect to 404 page if it doesn't exist
            }



        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    public function get_files_open_imp(Request $request, $filenames){


        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman', 'isWarehouseWorker'])){


            $path_to_file = 'arxeia'.DIRECTORY_SEPARATOR.'eisagwgi'.DIRECTORY_SEPARATOR . $filenames;

            //$filename is the value that i pass from the view as route parameter!
            //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]


            if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path. so in our example we pass directly the path (/.../laravelProject/storage/app) is the default one (referenced with the helper storage_path('app')


                $name = substr($filenames, 15);


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
                return abort('404', 'Το αρχείο δεν υπάρχει'); // we redirect to 404 page if it doesn't exist
            }



        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
