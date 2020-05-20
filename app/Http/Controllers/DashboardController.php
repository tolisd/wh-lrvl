<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Auth; //added for Auth

class DashboardController extends Controller
{
    //Collect all the common routes in here as far as the Dashboard is concerned

    public function index(){
        //who CAN view the (initial screen of) the Dashboard, (remove or add the types here)
        //in this case, each and every user_type can view the Dashboard (but not all menus)
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman', 'warehouse_worker', 'normal_user']);
        
        //---use this?
        // $this->authorize('see-dashboard', $user);  
        
        //---or this? it seems to do the job!
        if ($authenticatedUser){
            $users = User::count();      
            return view('dashboard', ['users' => $users]);        //pass the value of $users into the view
        } else {
            return abort(403, 'Sorry you cannot view this page');
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


    
    //---insert more methods (most of the route methods that is) in here




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
