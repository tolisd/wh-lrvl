<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use Validator;
use App\Tool;
use App\User;
use App\Employee;
use App\Toolshistory;
use Carbon\Carbon; //for Dates entry, formatting, etc.


class ToolController extends Controller
{
    //
    public function view_tools(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

            $tools_count = Tool::count();
            //$tools = DB::table('tools')->get(); //Query Builder, not Eloquent ORM, use Eloquent instead.
                                                  //It returns an eloquent collection... Needs @foreach() in the view eq. to [Tool::all();]
            $tools     = Tool::with('employee')->get(); //Eloquent ORM, eager loading
            //$users = DB::table('users')->get();
            $employees = Employee::with('user', 'tool')->get(); //eager loading, multiple relations in the model Employee.php,
                                                                //it brings up all employees
            //$users     = User::with('employee')->get(); //eager loading

            //join 3 tables to get the employee names!
            /*
            $ue_names = DB::table('users')
                                ->join('employees', 'users.id', '=', 'employees.user_id')
                                ->join('tools', 'users.id', '=', 'tools.employee_id')
                                ->select('users.name')
                                ->get();
            */
            //dd($employees);

            return view('tools_view', ['tools' => $tools,
                                        'tools_count' => $tools_count,
                                        'employees' => $employees]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }



    public function view_history(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

            $tools_history_data = Toolshistory::all();
            $employees = Employee::with('user', 'tool')->get();

            $tool_empl_names = DB::table('employees')
                                ->join('tools','tools.employee_id','=','employees.id')
                                ->join('users', 'employees.user_id','=','users.id')
                                ->join('toolshistory', 'toolshistory.tool_id','=','tools.id')
                                ->select('users.name')
                                ->get();



            return view('tools_history_view', ['tools_history_data' => $tools_history_data,
                                               'tool_empl_names'    => $tool_empl_names,
                                               'employees'          => $employees]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }






    //CHARGE a tool, a SPECIAL case of Update/Edit PUT Request
    //Only the Warehouse Foreman CAN do this kind of update, i.e. Charge A Tool to somebody
    public function charge_tool(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){
            //dd($id);

            /*
            $this->validate($request, [
                'file_url' => 'mimes:doc,docx,pdf,txt|max:250',  //maximumFileSize = 250kB
            ]);
            */

            $validation_rules = [
                'modal-input-towhom-charge' => 'required|exists:employees,id',
                'modal-input-comments-charge' => 'required',
                'modal-input-file-charge' => 'required|mimes:pdf,txt,zip,doc,docx', //mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
            ]; //maximumFileSize = 250kB, zip means: both, doc & docx

            $custom_messages = [
                'modal-input-towhom-charge.required' => 'Το όνομα εργαζομένου απαιτείται',
                'modal-input-comments-charge.required' => 'Τα σχόλια απαιτούνται',
                'modal-input-file-charge.required' => 'Το αρχείο απαιτείται',
                'modal-input-file-charge.mimes' => 'Μη έγκυρο αρχείο. Τύποι αρχείων που υποστηρίζονται: pdf, txt, doc, docx',
            ];

            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);


            if($request->ajax()){

                if($validator->fails()){
                    //failure
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){

                    DB::beginTransaction();

                    try{
                        $tool_for_charging = Tool::findOrFail($id);

                        $tool_for_charging->is_charged    = 1; //$request->input('modal-input-ischarged-charge');
                        $tool_for_charging->employee_id   = $request->input('modal-input-towhom-charge');
                        $tool_for_charging->comments      = $request->input('modal-input-comments-charge');
                        //also, now upload the xrewstiko eggrafo...it is NECESSARY for the charging to be completed successfully
                        //uncomment the following block for the file to be uploaded!
                        /*
                        $path = $request->file('modal-input-file-charge')->store('arxeia/xrewstika');  //stored in storage/app/arxeia/xrewstika/
                        $url = \Storage::url($path); //stores the full path
                        $tool_for_charging->file_url = $url; //access it in Blade as:: {{ $tool->file_url }}
                        */

                        //$url = null;
                        if($request->hasFile('modal-input-file-charge')){
                            $file = $request->file('modal-input-file-charge');
                            $datetime_now = date_create();
                            $datetime = date_format($datetime_now, 'YmdHis');
                            $name = $datetime . '-' . $file->getClientOriginalName();
                            $path = $file->storeAs('arxeia/xrewstika', $name);
                            $url  = \Storage::url($path);   // You may use the url method to get the URL for the given file.
                                                            // If you are using the local driver, this will typically just PREPEND /storage
                                                            // to the given path and return a relative URL to the file.

                            $tool_for_charging->file_url = $url;
                        }

                        $tool_for_charging->update($request->all());
                        //$tool_for_charging->update($request->only(['modal-input-ischarged-charge', 'modal-input-towhom-charge']));


                        // $album = Album::where('hash', $request->album_hash)->firstOrFail();
                        // $images = json_decode($album->images);
                        // array_push($images,  $request->image->name);
                        // $album->update(['images' => $images]);
                        // $album->save();

                        //Insert relevant data into 'toolshistory' table here
                        //CREATE the NEW object (or not?) NO! it's already been created in the other method, just FIND it!
                        $th = Toolshistory::where('tool_id', $tool_for_charging->id)->firstOrFail();
                        // $th = Toolshistory::findOrFail($id);

                        // $th->tool_id = $tool_for_charging->id;
                        // $th->charged_at = $tool_for_charging->updated_at;
                        // $th->to_whom = $tool_for_charging->employee_id;
                        // $th->file_url = $tool_for_charging->file_url;

                        // Toolshistory::where('tool_id', $id)->firstOrFail();

                        //json_decode(,true)  returns ARRAY, json_decode() returns PHP OBJECT
                        $charged_at = ($th->charged_at != null) ? json_decode($th->charged_at) : json_encode(json_decode("{}")); //''; //json_decode expects parameter 1
                                                                                                                                       //to be string, array given


                        // $emp_name = Employee::findOrFail('id', $tool_for_charging->employee_id)->user->name;

                        $charged_at->date = $tool_for_charging->updated_at->format('l, d-m-Y H:i'); //here use \Carbon for formatting the date correctly!
                        $charged_at->whom = $tool_for_charging->employee_id;
                        $charged_at->file = substr(basename($tool_for_charging->file_url), 15);

                        // $charged_arr = json_decode($charged_at, true);

                        // array_push($charged_arr, [
                        //                           'date' => $charged_at->date,
                        //                           'whom' => $charged_at->date,
                        //                           'file' => $charged_at->date
                        //                          ]);


                        $append_arr = [
                            'date' => $charged_at->date,
                            'whom' => $charged_at->whom,
                            'file' => $charged_at->file,
                        ];

                        $charged_arr = (array)$charged_at;

                        array_push($charged_arr, $append_arr);

                        // dd($charged_arr);

                        // $th->charged_at = json_encode($charged_arr, JSON_FORCE_OBJECT);

                        $charged_at_jsonObject = json_encode($charged_arr, JSON_FORCE_OBJECT);


                        // $th->charged_at->date = $tool_for_charging->updated_at;
                        // $th->charged_at->whom = $tool_for_charging->employee_id;
                        // $th->charged_at->file = $tool_for_charging->file_url;

                        $th->update(['charged_at' => $charged_at_jsonObject]);

                        $th->save();








                        DB::commit();

                        //success
                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);


                    } catch(\Exception $e) {

                        DB::rollBack();

                        //failure
                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);
                    }



                    // //success
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

    //UNCHARGE/DEBIT a tool, a SPECIAL case of Update/Edit PUT Request
    //Only the Warehouse Foreman CAN do this kind of update, i.e. Uncharge A Tool from somebody
    public function uncharge_tool(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

            $rules = [
                'modal-input-comments-uncharge' => 'required',
            ];

            $custom_messages = [
                'modal-input-comments-uncharge.required' => 'Τα σχόλια απαιτούνται',
            ];

            $validator = Validator::make($request->all(), $rules, $custom_messages);


            if($request->ajax()){

                if($validator->fails()){
                    //failure
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){

                    DB::beginTransaction();

                    try{

                        $tool_for_uncharging = Tool::findOrFail($id);

                        $tool_for_uncharging->is_charged  = 0; //$request->input('modal-input-ischarged-uncharge');
                        $tool_for_uncharging->employee_id = null;
                        $tool_for_uncharging->file_url    = null; //Important! Also, delete the actual xrewstiko arxeio/file here!!
                        $tool_for_uncharging->comments    = $request->input('modal-input-comments-uncharge');

                        $tool_for_uncharging->update($request->all());
                        //$tool_for_uncharging->update($request->only(['modal-input-ischarged-uncharge']));



                        //Add the uncharging to the 'toolshistory' table as well!
                        $th = Toolshistory::where('tool_id', $tool_for_uncharging->id)->firstOrFail();

                        //json_decode(,true)  returns ARRAY, json_decode() returns PHP OBJECT
                        $uncharged_at = ($th->uncharged_at != null) ? json_decode($th->uncharged_at) : json_encode(json_decode("{}"));

                        $uncharged_at->date = $tool_for_uncharging->updated_at->format('l, d-m-Y H:i'); //here use \Carbon for formatting the date correctly!
                        // $uncharged_at->whom = $tool_for_uncharging->employee_id;

                        $uncharged_at_jsonObject = json_encode($uncharged_at, JSON_FORCE_OBJECT);

                        $th->update(['uncharged_at' => $uncharged_at_jsonObject]);

                        $th->save();




                        DB::commit();

                        //success
                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);


                    } catch(\Exception $e) {

                        DB::rollBack();

                        //failure
                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);
                    }



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

    //Create a new tool
    public function create_tool(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){


            $validation_rules = [
                'modal-input-name-create' => 'required',
                'modal-input-code-create' => 'required|unique:tools,code',
                'modal-input-description-create' => 'required',
                'modal-input-comments-create' => 'required',
                'modal-input-quantity-create' => 'required',
            ];

            $custom_messages = [
                'modal-input-name-create.required' => 'Το όνομα απαιτείται',
                'modal-input-code-create.required' => 'Ο κωδικός απαιτείται',
                'modal-input-description-create.required' => 'Η περιγραφή απαιτείται',
                'modal-input-comments-create.required' => 'Τα σχόλια απαιτούνται',
                'modal-input-quantity-create.required' => 'Η ποσότητα απαιτείται',
            ];

            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){
                if($validator->fails()){

                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);

                }
                if($validator->passes()){

                    DB::beginTransaction();

                    try{

                        $tool = new Tool();

                        $tool->name         = $request->input('modal-input-name-create');
                        $tool->code         = $request->input('modal-input-code-create');
                        $tool->description  = $request->input('modal-input-description-create');
                        $tool->comments     = $request->input('modal-input-comments-create');
                        $tool->quantity     = $request->input('modal-input-quantity-create');
                        $tool->is_charged   = 0; //$request->input('modal-input-ischarged-create');  //should just be false? because a new tool is not charged yet...
                        //$tool->employee_id  = //$request->input('modal-input-towhom-create');
                        //$tool->file_url     = $request->input('modal-input-file-create');
                        $tool->save();



                        //HERE create the NEW $toolshistory object, and in the OTHER methods just update it!
                        $th = new Toolshistory();

                        $th->tool_id      = $tool->id; //and that's about it!
                        $th->charged_at   = json_encode(json_decode("{}"));  //[]; //It is not yet charged as it is newly created tool
                        $th->uncharged_at = json_encode(json_decode("{}"));  //[]; //It is not yet uncharged as it is newly created tool

                        //this DID the trick!!  json_encode(json_decode("{}")); <<== EMPTY JSON OBJECT!

                        $th->save();




                        DB::commit();

                        //success
                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);


                    } catch(\Exception $e) {

                        DB::rollBack();

                        //failure
                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);
                    }


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

    //Edit the (existing) tool details
    public function update_tool(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){



            $validation_rules = [
                'modal-input-name-edit' => ['required'],
                'modal-input-code-edit' => ['required', \Illuminate\Validation\Rule::unique('tools', 'code')->ignore($id)],
                'modal-input-description-edit' => ['required'],
                'modal-input-comments-edit' => ['required'],
                'modal-input-quantity-edit' => ['required'],
            ];

            $custom_messages = [
                'modal-input-name-edit.required' => 'Το όνομα απαιτείται',
                'modal-input-code-edit.required' => 'Ο κωδικός απαιτείται',
                'modal-input-description-edit.required' => 'Η περιγραφή απαιτείται',
                'modal-input-comments-edit.required' => 'Τα σχόλια απαιτούνται',
                'modal-input-quantity-edit.required' => 'Η ποσότητα απαιτείται',
            ];

            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){
                if($validator->fails()){

                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);

                }
                if($validator->passes()){

                    DB::beginTransaction();

                    try{
                        $tool = Tool::findOrFail($id);

                        $tool->name         = $request->input('modal-input-name-edit');
                        $tool->code         = $request->input('modal-input-code-edit');
                        $tool->description  = $request->input('modal-input-description-edit');
                        $tool->comments     = $request->input('modal-input-comments-edit');
                        $tool->quantity     = $request->input('modal-input-quantity-edit');
                        //$tool->is_charged   = $request->input('modal-input-ischarged-update');
                        //$tool->employee_id  = $request->input('modal-input-towhom-update');
                        //$tool->file_url     = $request->input('modal-input-file-update');

                        $tool->update($request->all());


                        DB::commit();

                        //success
                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);


                    } catch(\Exception $e) {

                        DB::rollBack();

                        //failure
                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);
                    }



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

    //Delete an existing tool
    public function delete_tool(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

            //also, delete its charging (if charged already)?

            if ($request->ajax()){

                $tool = Tool::findOrFail($id);
                $tool->delete();

                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }



    //A Warehouse Foreman can see WHICH tools are charged (and to WHOM via user_id)
    public function view_charged_tools(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

            $employees = Employee::with('user', 'tool')->get(); //eager loading, multiple relations in the model Employee.php,
                                                                //it brings up all employees

            $charged_tools = DB::table('tools')
                                ->where('is_charged', '=', '1')
                                ->get();

            return view('tools_charged_view', ['charged_tools' => $charged_tools,
                                                'employees' => $employees]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //A Warehouse Foreman can see WHICH tools are NOT charged to anyone
    public function view_non_charged_tools(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

            $non_charged_tools = DB::table('tools')
                                    ->where('is_charged', '=', '0')
                                    ->get();

            return view('tools_non_charged_view', ['non_charged_tools' => $non_charged_tools]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    //A Warehouse Worker (or Any Employee/User) CAN view WHICH tools he was CHARGED with.
    // => The tools of the currently authenticated user!
    public function view_my_charged_tools(){

        //Administrator & Manager cannot access this page, because it makes no sense to.
        if(\Gate::any(['isWarehouseForeman', 'isWarehouseWorker', 'isTechnician'])){

            $u_id        = Auth::user()->id;  //get the authenticated user's ID
            //dd($u_id);
            //$u_name      = Auth::user()->name; //get the authenticated user's name

            /*
            //Join 2 tables: employees & users
            //correct, returns the id of the currently authenticated user..

            $employee_id  = DB::table('employees')
                            ->join('users', 'employees.user_id', '=', 'users.id')
                            ->where('users.id', $u_id)
                            ->select('employees.id')
                            ->get();
            //dd($employee_id);

            $emp_id = json_decode(json_encode($employee_id), true); //turn the eloquent collection into an associative PHP array
            //dd($emp_id);

            //Join 3 tables: tools, employees & users
            //correct, returns a collection

            $my_charged_tools = DB::table('tools')
                                ->join('employees', 'tools.employee_id', '=', 'employees.id')
                                ->join('users', 'tools.employee_id', '=', 'users.id')
                                ->where('tools.employee_id', $emp_id)
                                ->select('tools.employee_id', 'tools.code', 'tools.name', 'tools.description')
                                ->get();
            //dd($my_charged_tools);
            */

            //the following is 3 queries, 1 more query than the previous solution, but it looks much shorter and cleaner!
            $user             = User::findOrFail($u_id);
            $employee         = Employee::where('user_id', $user->id)->first();   //via its FK
            $my_charged_tools = Tool::where('employee_id', $employee->id)->get(); //via its FK


            return view('tools_my_charged_view', ['my_charged_tools' => $my_charged_tools]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    //the authorised roles CAN download the file, else 403!
    public function get_file(Request $request, $filename){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

            // $file = $request->input('filename');
            // $path = storage_path('/'.'app'.'/arxeia/xrewstika/'.$filename);
            // $file = \Storage::url($filename);

            // $storagePath  = \Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();
            // // dd($storagePath); //returns the absolute url. should i use dirinfo()??
            // $path_to_file = $storagePath .'arxeia'.DIRECTORY_SEPARATOR.'xrewstika'.DIRECTORY_SEPARATOR.basename($filename); //CORRECT!

            // dd($filename); //basename, because thats what i pass from the view as route parameter!
            // $path_to_file = env('APP_URL') . request()->route()->getPrefix() . '/tools/download/storage/app/arxeia/xrewstika/' . $filename;
            // $path_to_file = 'http://localhost:8000' . request()->route()->getPrefix() . '/tools/download/storage/app/arxeia/xrewstika/' . $filename;
            // $path_to_file = \Storage::disk('local')->getDriver()->getAdapter()->applyPathPrefix($filename);

            // $file = storage_path($filename);
            // dd(\Storage::disk('local')->exists($path_to_file));
            // dd(\Storage::disk('local')->exists($filename)); //FALSE! File is empty or not downloaded at all!

            $path_to_file = 'arxeia'.DIRECTORY_SEPARATOR.'xrewstika'.DIRECTORY_SEPARATOR.$filename;
            //$filename is the value that i pass from the view as route parameter!
            //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]

            if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path. so in our example we pass directly the path (/.../laravelProject/storage/app) is the default one (referenced with the helper storage_path('app')

                $name = str_replace(' ', '_', $filename); //'xrewstiko_arxeio.txt'; fixed value..

                // $headers = [
                //     // 'Content-Type' => 'application/pdf',
                //     // 'Content-Type' => 'text/plain',
                //     'Cache-Control' => 'no-cache, no-store, must-revalidate',
                //     'Pragma' => 'no-cache',
                //     'Expires' => '0',
                //     'Content-Disposition' => 'attachment',
                //     // 'Content-Disposition' => 'attachment; filename="'.$name.'"',
                // ];

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
                return \Storage::download($path_to_file, $name, $headers);

            } else {
                abort('404', 'Το αρχείο δεν υπάρχει'); // we redirect to 404 page if it doesn't exist
            }

            // if(file_exists($filename) && is_file($filename)){
                // return response()->download(storage_path('app'.DIRECTORY_SEPARATOR.($file))); //the downloaded filename should be different than that stored in server

                // dd(response()->download($filename)); //BLANK page...
            // }

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }



}
