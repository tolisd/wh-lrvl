<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User; //added for User
use Auth;   //added for Auth



class HomeController extends Controller
{
    //

    public function index(){
        // return view('home');

        /*
        //user must be logged-in (as a super administrator!), for this view to display...
        if (\Gate::allows('isSuperAdmin', \Auth::user())){
            return view('home');
        }

        //user is not logged-in OR is NOT a super administrator, so it's a 403 Forbidden..!
        if (\Gate::denies('isSuperAdmin', \Auth::user())){
            return abort('403');
        }
        */

        //--------------------------------

        /*
        if (\Gate::allows('isCompanyCEO', \Auth::user())){
            return view('home');
        }

        if (\Gate::denies('isCompanyCEO', \Auth::user())){
            return abort('403');
        }
        */


        /*
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'accountant', 'warehouse_worker', 'normal_user']);

        if($authenticatedUser){
            return view('home');
        } else
        {
            return abort(403, 'Sorry you cannot view this home webpage');
        }
        */

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker', 'isNormalUser'])){
            return view('dashboard');
        }
        else {
            return abort(403, 'Sorry you cannot view this home webpage');
        }





    }


}
