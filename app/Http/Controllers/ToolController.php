<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Tool;
use App\User;
use App\Employee;


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

    //CHARGE a tool, a SPECIAL case of Update/Edit PUT Request
    //Only the Warehouse Foreman CAN do this kind of update, i.e. Charge A Tool to somebody
    public function charge_tool(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){
            //dd($id);
            $tool_for_charging = Tool::findOrFail($id);

            /*
            $this->validate($request, [
                'file_url' => 'mimes:doc,docx,pdf,txt|max:250',  //maximumFileSize = 250kB
            ]);
            */

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
            if($request->hasfile('modal-input-file-charge')){
                    $file = $request->file('modal-input-file-charge');
                    $name = $file->getClientOriginalName();
                    $path = $file->storeAs('arxeia/xrewstika', $name);
                    $url  = \Storage::url($path);
            }
            $tool_for_charging->file_url = $url;

            $tool_for_charging->update($request->all());
            //$tool_for_charging->update($request->only(['modal-input-ischarged-charge', 'modal-input-towhom-charge']));

            if ($request->ajax()){
                return \Response::json();
            }

             return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    //UNCHARGE/DEBIT a tool, a SPECIAL case of Update/Edit PUT Request
    //Only the Warehouse Foreman CAN do this kind of update, i.e. Uncharge A Tool from somebody
    public function uncharge_tool(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

            $tool_for_uncharging = Tool::findOrFail($id);

            $tool_for_uncharging->is_charged  = 0; //$request->input('modal-input-ischarged-uncharge');
            $tool_for_uncharging->employee_id = null;
            $tool_for_uncharging->file_url    = null; //Important! Also, delete the actual xrewstiko arxeio/file here!!
            $tool_for_uncharging->comments    = $request->input('modal-input-comments-uncharge');


            $tool_for_uncharging->update($request->all());
            //$tool_for_uncharging->update($request->only(['modal-input-ischarged-uncharge']));


            if ($request->ajax()){
                return \Response::json();
            }

             return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    //Create a new tool
    public function create_tool(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

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


            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //Edit the (existing) tool details
    public function update_tool(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

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


            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //Delete an existing tool
    public function delete_tool(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman'])){

            $tool = Tool::findOrFail($id);
            $tool->delete();

            //also, delete its charging (if charged already)?

            if ($request->ajax()){
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
        if(\Gate::any(['isWarehouseForeman', 'isWarehouseWorker'])){

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



}
