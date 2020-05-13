<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class HomeController extends Controller
{
    //

    public function index(){
        // return view('home');

        //user must be logged-in (as a super administrator!), for this view to display...
        if (\Gate::allows('isSuperAdmin', \Auth::user())){
            return view('home');            
        }      
        
        //user is not logged-in OR is NOT a super administrator, so it's a 403 Forbidden..!
        if(\Gate::denies('isSuperAdmin', \Auth::user())){
            return abort('403');
        }
    }
    
    
}
