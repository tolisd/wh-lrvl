<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CompanyCEOController extends Controller
{
    //
    public function index(){
       // return view('dashboard');
        
        if (\Gate::allows('isCompanyCEO', \Auth::user())){
            return view('dashboard');            
        }   
        
        if (\Gate::denies('isCompanyCEO', \Auth::user())){
            return abort('403');
        }
    }

    public function home(){
        //Company CEO
        if (\Gate::allows('isCompanyCEO', \Auth::user())){
            return view('home');            
        }   
        
        if (\Gate::denies('isCompanyCEO', \Auth::user())){
            return abort('403');
        }
    }    

    public function private(){
        //Company CEO
        if (\Gate::allows('isCompanyCEO', \Auth::user())){
            return view('private');            
        }   
        
        if (\Gate::denies('isCompanyCEO', \Auth::user())){
            return abort('403');
        }
    }

    public function about(){
        if (\Gate::denies('isCompanyCEO', \Auth::user())){
            abort(403,'Sorry you cannot view this webpage');
        }

        if (\Gate::allows('isCompanyCEO', \Auth::user())){            
            return view('about');
        } 
    }


    public function create(){
        $user = Auth::user();
        
        if ($user->can('create', CompanyCEO::class)){
            echo 'Logged-in user is allowed to create a Company CEO';
        } else {
            echo 'Not authorised to create.';
        }
    }

    public function view(){
        $user = Auth::user();
        $companyceo = CompanyCEO::find(1);

        if ($user->can('view', $companyceo)){
            echo 'Logged-in user is allowed to view the Company CEO {$companyceo->id}';
        } else {
            echo 'Not authorised to view.';
        }
    }

    public function update(){
        $user = Auth::user();
        $companyceo = CompanyCEO::find(1);

        if ($user->can('update', $companyceo)){
            echo 'Logged-in user is allowed to update the Company CEO {$companyceo->id}';
        } else {
            echo 'Not authorised to update.';
        }
    }

    public function delete(){
        $user = Auth::user();
        $companyceo = CompanyCEO::find(1);

        if ($user->can('delete', $companyceo)){
            echo 'Logged-in user is allowed to delete the Company CEO {$companyceo->id}';
        } else {
            echo 'Not authorised to delete.';
        }
    }
}
