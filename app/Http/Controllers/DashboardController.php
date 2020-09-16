<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //added for DB retrieval
use App\User;
use App\Product;
use App\Assignment;
use Auth; //added for Auth

class DashboardController extends Controller
{
    //Collect all the common routes in here as far as the Dashboard is concerned

    //VIEW the MAIN dashboard screen!
    public function index(){
        //who CAN view the (initial screen of) the Dashboard, (remove or add the types here)
        //in this case, each and every user_type can view the Dashboard (but NOT all menus)

        /*
        $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman', 'warehouse_worker', 'normal_user']);

        //---use this?
        // $this->authorize('see-dashboard', $user);

        //---or this? it seems to do the job!
        if ($authenticatedUser){

            $usersCount = User::count();
            $productsCount = Product::count(); //added: 'use App\Product;'
            //ToDo: ass the assignments count here as well!
            $assignmentsCount = Assignment::count();

            //pass the value of $usersCount & $productsCount into the view
            return view('dashboard', ['usersCount' => $usersCount,
                                      'prodCount'  => $productsCount,
                                      'assignCount' => $assignmentsCount ]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
        */

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker', 'isNormalUser'])){

            $usersCount = User::count();
            $productsCount = Product::count(); //added: 'use App\Product;'
            $assignmentsCount = Assignment::count();

            return view('dashboard', ['usersCount' => $usersCount,
                                        'prodCount'  => $productsCount,
                                        'assignCount' => $assignmentsCount ]);
        }
        else {
            return abort(403, 'Sorry you cannot view this home webpage');
        }




    }


    public function view_stock(){
        //3 user types -> Admin, CEO, Foreman
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman']);

        if ($authenticatedUser){
            return view('view_stock');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function view_products(){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){
            return view('products_view');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function view_product(){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){
            return view('product_view');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_product(){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){
            return view('create_product');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function update_product(){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){
            return view('update_product');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function delete_product(){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){
            return view('delete_product');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }



    //---insert more methods (most of the route methods that is) in here

    public function charge_toolkit(){
         //3 user types -> Admin, CEO, Foreman
         $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman']);

         if ($authenticatedUser){
             return view('charge_toolkit');
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }
    }

    public function create_invoice(){
        //3 user types -> Admin, CEO, Accountant
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant']);

        if ($authenticatedUser){
            return view('create_invoice');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
   }


    public function view_assignments(){
        //4 user types -> Admin, CEO, Accountant, Foreman
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){
            return view('assignments_view');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


    public function create_import_assignment(){
        //4 user types -> Admin, CEO, Accountant, Foreman
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){
            return view('create_import_assignment');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


    public function create_export_assignment(){
         //4 user types -> Admin, CEO, Accountant, Foreman
         $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

         if ($authenticatedUser){
             return view('create_export_assignment');
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }
    }

    public function update_assignment(){
        //4 user types -> Admin, CEO, Accountant, Foreman
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){
            return view('update_assignment');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


   public function delete_assignment(){
        //4 user types -> Admin, CEO, Accountant, Foreman
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){
            return view('delete_assignment');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
   }


   public function create_user(){
        //2 user types -> Admin, CEO.
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo']);

        if ($authenticatedUser){





            return redirect()->back();
            //return view('user_create');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
   }


   public function update_user(Request $request, $uid){

   }


   public function delete_user(Request $request, $uid){


   }

   public function view_user(){
        //2 user types -> Admin, CEO.
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo']);

        if ($authenticatedUser){
            return view('user_view');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function view_users(){
        //2 user types -> Admin, CEO.
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo']);

        if ($authenticatedUser){

            $users = DB::table('users')->get(); //get all users from database via Facade

            return view('users_view', ['users' => $users]);     //pass in the view, the $users var.

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }




    }

    public function change_user_password(){
        //2 user types -> Admin, CEO.
        $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo']);


        if ($authenticatedUser){
            return view('user_change_password');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }






    public function view_dashboard(User $user){
        $user = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant']); //get authorised user via method in User Model

        $this->authorize('see-dashboard', $user);

        return view('dashboard');

        /*
        if (Gate::forUser($user)->allows('view-assignments', $assignment)){
            //the currently authorized user CAN view assignments

        }
        */

        /*
        return ($user->user_type == 'super_admin')
            || ($user->user_type == 'company_ceo')
            || ($user->user_type == 'accountant');
        */
    }
}
