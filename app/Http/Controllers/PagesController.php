<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class PagesController extends Controller
{
    //
    public function about(){

        if (\Gate::denies(['isSuperAdmin','isCompanyCEO'], \Auth::user())){
            abort(403,'Sorry you cannot view this webpage');
        }

        if (\Gate::allows(['isSuperAdmin','isCompanyCEO'], \Auth::user())){            
            return view('about');
        }        
    }    

    public function home(){
        if (\Gate::denies(['isSuperAdmin','isCompanyCEO'], \Auth::user())){
            abort(403,'Sorry you cannot view this webpage');
        }

        if (\Gate::allows(['isSuperAdmin', 'isCompanyCEO'], \Auth::user())){
            return view('home');
        } 
    }

    public function dashboard(){          
        if (\Gate::denies(['isSuperAdmin','isCompanyCEO'], \Auth::user())){
            abort(403,'Sorry you cannot view this webpage');
        }

        if (\Gate::allows(['isSuperAdmin', 'isCompanyCEO'], \Auth::user())){            
            return view('dashboard'); 
        }          
    }

}
