<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //added for DB retrieval
use App\User;
use Auth; //added for Auth
use Response;
use Datatables;
use App\Employee; //for syncing the 2 tables ('users' & 'employees') simultaneously.

class UserController extends Controller
{
    /*
    public function get_all_usertypes(){

        $usertypes = DB::table('users')->get(); //collection..

        $ut = [];

        foreach($usertypes as $key->$val){
            if($key == 'user_type'){
                foreach($val as $v){
                    array_push($ut, $v);
                }
            }
        }

        return $ut;
    }
    */

    public function update_user(Request $request, $id){
        //2 user types -> Admin, CEO.


        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO'])){

            /*
            $request->validate([
                'name'      => 'required',
                'email'     => 'required',
                'user_type' => 'required',
            ]);
            */

            //$input = $request->input();  //take ALL input values into $input, as an assoc.array
            //$u_id = $input['data-uid'];
            //dd($u_id);

            //$user = DB::table('users')->where('id', $u_id)->first();
            $user = User::findOrFail($id); //was findOrFail($u_id);

            $user->name      = $request->input('modal-input-name-edit');
            $user->email     = $request->input('modal-input-email-edit');
            $user->password  = \Hash::make($request->input('modal-input-passwd-edit'));
            $user->user_type = $request->input('modal-input-usertype-edit');

            $user->update($request->all());


            if ($request->ajax()){
                return \Response::json();
            }

            /*
            if ($request()->ajax()){
                //return Datatables::of(User::get());
                return response()->json($user);
            }
            */

            //return Datatables::of($user)->make(true);
            //return response()->json($user);
            return back();
        }
        else
        {
            return abort(403, 'Sorry, you cannot edit users.');
        }


        /*
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo']);

        if ($authenticatedUser){

             $request->validate([
                 'name'      => 'required',
                 'email'     => 'required',
                 'user_type' => 'required',
             ]);

             $u_id = $request->modal-input-uid-edit; // ->value does this get the (correct) ID?

             //var_dump($uid);
             //$user = User::findOrFail($id);
             //$users = User::all();
             //$user = $users->find($id);
             $user = User::findOrFail($u_id);
             // $user = User::where('id', $id)->first()->get();


             //$user->name      = $request->input('modal_input_name_edit');
             //$user->email     = $request->input('modal_input_email_edit');
             //$user->user_type = $request->input('modal_input_usertype_edit');

             $user->update($request->all());    //->update($request->all()); (?)

             return back();
             //return view('admin.users.view'); //aka redirect to same page...
             //return view('admin.users.view', compact('user'));
             //return redirect('/contacts')->with('success', 'Contact updated!');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
        */

    }

    public function delete_user(Request $request, $id){
        //2 user types -> Admin, CEO.
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO'])){

            //$u_id = $request->input("data-uid");   //take ALL input values into $input, as an assoc.array
            //$u_id = $arr['data-uid'];

            $user = User::findOrFail($id); //was findOrFail($u_id);
            $user->delete();

            //$json_user = json_encode($user);

            if ($request->ajax()){
                return \Response::json();
            }

            return back();
        }
        else
        {
            return abort(403, 'Sorry, you cannot delete users.');
        }

/*
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo']);

         //$this->authorize('isSuperAdmin', User::class);
         //$user = User::find($id);
         //dd($user);

        if ($authenticatedUser){

            //$u_id = $request->modal-input-uid-del;

            $arr = $request->input();
            $u_id = $arr['data-uid'];

            //$user = DB::table('users')->where('id', $uid)->get();
            $user = User::findOrFail($u_id);
            //$user = User::where('id', $id)->first()->get();

            $user->delete();
            // $user = User::findOrFail($uid);
            // $user->delete();

            //$res = Users::where('id', $u_id)->first()->delete();
            //Session::flash('success', 'Ο χρήστης διαγράφηκε επιτυχώς.');

            return response()->json();
            //return back();
            //return view('admin.users.view'); //aka redirect to same page...
            //return redirect('/users_view');
        }
        else
        {
            return abort(403, 'Sorry you cannot view this page');
        }
        */
    }


    //Add a new user to the database
    public function create_user(Request $request){

        //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo']);

        //this, if(\Gate(...)){ does not work for a reason... }
        //if(\Gate::allows(['isSuperAdmin', 'isCompanyCEO'])){
        //if($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO'])){

            /*
            $request->validate([
                'name'      => 'required',
                'email'     => 'required',
                'user_type' => 'required',
            ]);
            */

            //$input = $request->input();  //take ALL input values into $input, as an assoc.array
            //$usertype = $input[''];


            $this->validate($request, [
                'photo_url' => 'image|mimes:jpeg,jpg,png,gif|max:2048',
            ]);


            $user = new User();

            $user->name      = $request->input('modal-input-name-create');
            $user->email     = $request->input('modal-input-email-create');
            $user->password  = \Hash::make($request->input('modal-input-passwd-create'));
            $user->user_type = $request->input('modal-input-usertype-create');
            // Set other fields (if applicable)...
            /*
            // ...image upload
            $path = $request->file("modal-input-photo-create")->store("images/");  //stored in storage/app/images/
            $url = \Storage::url($path);
            $user->photo_url = $url;
            */

            $user->save(); //Save the new user into the database

            /*
            //Also, CREATE a new row in 'employees' table
            $employee = new Employee();

            $employee->name = $user->name;
            $employee->email = $user->email;
            $employee->employee_type = $user->user_type;

            //$employee->save();

            //$user = User::find(1);
            $user->profile()->save($employee); //when I create a new User, ALSO create a NEW Employee with/from the same(almost) data as the User.
            */

            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry, you cannot add users.');
        }

    }
}
