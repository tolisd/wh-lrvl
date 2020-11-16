<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //added for DB retrieval
use App\User;
use Auth; //added for Auth
use Response;
use Datatables;
use Validator;
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


            //the validation rules
            $validation_rules = [
                'modal-input-name-edit' => 'required',
                'modal-input-email-edit' => 'required',
                //'modal-input-passwd-edit' => 'required',
                'modal-input-usertype-edit' => 'required',
                'modal-input-photo-edit' => 'nullable|mimetypes:image/jpeg,image/png',
            ];

            //the custom error messages for the above validation rules
            $custom_messages = [
                'modal-input-name-edit.required' => 'Το όνομα απαιτείται',
                'modal-input-email-edit.required' => 'Το ηλ.ταχυδρομείο απαιτείται',
                //'modal-input-passwd-edit.required' => 'Το συνθηματικό απαιτείται',
                'modal-input-usertype-edit.required' => 'Ο ρόλος χρήστη απαιτείται',
                'modal-input-photo-edit.mimetypes' => 'Τύποι αρχείων που υποστηρίζονται: jpg, jpeg, png.',
            ];

            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){
                    //validatiun failure, error 422
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){

                    DB::beginTransaction();

                    try{
                        $user = User::findOrFail($id); //was findOrFail($u_id);

                        $user->name      = $request->input('modal-input-name-edit');
                        $user->email     = $request->input('modal-input-email-edit');
                        // $user->password  = \Hash::make($request->input('modal-input-passwd-edit'));
                        $user->user_type = $request->input('modal-input-usertype-edit');
                        // ...image upload
                        /*
                        $path = $request->file('modal-input-photo-edit')->store('images/profile');  //stored in storage/app/images/
                        $url = \Storage::url($path);
                        */
                        if($request->hasFile('modal-input-photo-edit')){
                            //$path = $request->file('modal-input-photo-create')->store('images/profile');  //stored in storage/app/images/profile/
                            $file = $request->file('modal-input-photo-edit');
                            $name = $file->getClientOriginalName();
                            $path = $file->storeAs('images/profile', $name);
                            $url = \Storage::url($path); //stores the full path

                            $user->photo_url = $url;
                        }


                        $user->update($request->all());

                        DB::commit();

                        //validation success, 200 OK.
                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);

                    } catch (\Exception $e) {

                        DB::rollBack();

                        //validation failure, 500 OK.
                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);

                    }



                    // //validation success, 200 OK.
                    // return \Response::json([
                    //     'success' => true,
                    //     //'errors' => $validator->getMessageBag()->toArray(),
                    // ], 200);

                }
            }


            //$input = $request->input();  //take ALL input values into $input, as an assoc.array
            //$u_id = $input['data-uid'];
            //dd($u_id);

            //$user = DB::table('users')->where('id', $u_id)->first();


            /*
            if ($request->ajax()){
                return \Response::json();
            }
            */

            /*
            if ($request()->ajax()){
                //return Datatables::of(User::get());
                return response()->json($user);
            }
            */

            //return Datatables::of($user)->make(true);
            //return response()->json($user);
            //return back();
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

            //$json_user = json_encode($user);

            if ($request->ajax()){
                //$u_id = $request->input("data-uid");   //take ALL input values into $input, as an assoc.array
                //$u_id = $arr['data-uid'];
                DB::beginTransaction();

                try{
                    //User needs to be logged out BEFORE he is deleted from the Database.
                    //first, make sure user is NOT logged in (or is logged out). Important!

                    // Logout a specific user (by his/her $id) (found this solution in StackOverflow!)

                    // 1. get current (currently logged in?) user
                    $current_user = Auth::user();

                    // 2. logout user
                    $userToLogout = User::findOrFail($id);
                    // \Session::getHandler()->destroy($userToLogout->session_id); //taken from another answer in the same thread...
                    Auth::setUser($userToLogout); //from Laravel API 7.x Docs: "Set the current user" Contracts/Auth/Guard/setUser()
                    Auth::logout();

                    // 3. set again current user
                    Auth::setUser($current_user);

                    //after he/she is logged out as above, delete the actual user..!
                    $user = User::findOrFail($id); //was findOrFail($u_id);
                    $user->delete();

                    DB::commit(); //commit the changes

                    //success, 200 OK.
                    return \Response::json([
                        'success' => true,
                        //'errors' => $validator->getMessageBag()->toArray(),
                    ], 200);

                } catch(\Exception $e){

                    DB::rollBack();

                    //something happened...
                    return \Response::json([
                        'success' => false,
                        'message' => $e->getMessage(),
                        //'errors' => $validator->getMessageBag()->toArray(),
                    ], 500);
                }

                //return \Response::json();
            }

            // return back();
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

            /*
            $this->validate($request, [
                'photo_url' => 'image|mimes:jpeg,jpg,png,gif|max:100',
            ]);
            */

            $validation_rules = [
                'modal-input-name-create' => 'required',
                'modal-input-email-create' => 'required',
                'modal-input-passwd-create' => 'required|min:8',
                'modal-input-usertype-create' => 'required',
                'modal-input-photo-create' => 'nullable|mimetypes:image/jpeg,image/png', //image_jpeg include both jpg & jpeg files.
            ];

            $custom_messages = [
                'modal-input-name-create.required' => 'Το όνομα απαιτείται',
                'modal-input-email-create.required' => 'Το ηλ.ταχυδρομείο απαιτείται',
                'modal-input-passwd-create.required' => 'Το συνθηματικό απαιτείται',
                'modal-input-passwd-create.min' => 'Το συνθηματικό πρέπει να έχει τουλάχιστον 8 χαρακτήρες',
                'modal-input-usertype-create.required' => 'Ο ρόλος χρήστη απαιτείται',
                'modal-input-photo-create.mimetypes' => 'Τύποι αρχείων που υποστηρίζονται: jpg, jpeg, png.',
            ];

            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);



            if($request->ajax()){

                if($validator->fails()){
                    //validatiun failure, error 422
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){

                    DB::beginTransaction();

                    try{

                        $user = new User();

                        $user->name      = $request->input('modal-input-name-create');
                        $user->email     = $request->input('modal-input-email-create');
                        $user->password  = \Hash::make($request->input('modal-input-passwd-create'));
                        $user->user_type = $request->input('modal-input-usertype-create');
                        // Set other fields (if applicable)...
                        // ...image upload
                        if($request->hasFile('modal-input-photo-create')){
                            //$path = $request->file('modal-input-photo-create')->store('images/profile');  //stored in storage/app/images/profile/
                            $file = $request->file('modal-input-photo-create');
                            $name = $file->getClientOriginalName();
                            $path = $file->storeAs('images/profile', $name);
                            $url = \Storage::url($path); //stores the full path

                            $user->photo_url = $url; //access it in Blade as:: {{ $user->photo_url }}
                        }

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


                        DB::commit();

                        //success, 200 OK.
                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);

                    } catch(\Exception $e){
                        DB::rollBack();

                        //something happened...
                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);
                    }



                    // //success, 200 OK.
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
            return abort(403, 'Sorry, you cannot add users.');
        }

    }

    public function show_photo(Request $request, $photo){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO'])){


            $path_to_file = 'images'.DIRECTORY_SEPARATOR.'profile'.DIRECTORY_SEPARATOR.$photo;
            //$photo is the value that i pass from the view as route parameter!
            //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]

            if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path.
                                                                //so in our example we pass directly the path (/.../laravelProject/storage/app)
                                                                //is the default one (referenced with the helper storage_path('app')

                $name = $photo; //'.txt'; fixed value..

                $headers = [
                    // 'Content-Type' => 'application/pdf',
                    // 'Content-Type' => 'text/plain',
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                    // 'Content-Disposition' => 'attachment',
                    // 'Content-Disposition' => 'attachment; filename="'.$name.'"',
                ];

                return \Storage::get($path_to_file); //return [photo/file] contents


            } else {
                abort('404', 'Το αρχείο δεν υπάρχει'); // we redirect to 404 page if it doesn't exist
            }


        } else {
            return abort(403, 'Sorry, you cannot add users.');
        }

    }



    public function show_userpic(Request $request, $photo){

        // ALL user_types can view their own profile pic! ..provided one is uploaded. else BLANK <li> in the view
        if(\Gate::any(['isSuperAdmin',
                        'isCompanyCEO',
                        'isAccountant',
                        'isWarehouseForeman',
                        'isWarehouseWorker',
                        'isTechnician',
                        'isNormalUser'])){


            $path_to_file = 'images'.DIRECTORY_SEPARATOR.'profile'.DIRECTORY_SEPARATOR.$photo;
            //$photo is the value that i pass from the view as route parameter!
            //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]

            if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path.
                                                                //so in our example we pass directly the path (/.../laravelProject/storage/app)
                                                                //is the default one (referenced with the helper storage_path('app')

                $name = $photo; //'.txt'; fixed value..

                $headers = [
                    // 'Content-Type' => 'application/pdf',
                    // 'Content-Type' => 'text/plain',
                    'Cache-Control' => 'no-cache, no-store, must-revalidate',
                    'Pragma' => 'no-cache',
                    'Expires' => '0',
                    // 'Content-Disposition' => 'attachment',
                    // 'Content-Disposition' => 'attachment; filename="'.$name.'"',
                ];

                return \Storage::get($path_to_file); //return [photo/file] contents


            } else {
                abort('404', 'Το αρχείο δεν υπάρχει'); // we redirect to 404 page if it doesn't exist
            }


        } else {
            return abort(403, 'Sorry, you cannot add users.');
        }


    }
}
