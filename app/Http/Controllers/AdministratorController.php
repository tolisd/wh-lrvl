<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdministratorController extends Controller
{
    //
    public function index(){
        //return view('dashboard');
    }

    public function dashboard(){
       //user must be logged-in (as a super administrator!), for this view to display...
       if (\Gate::allows('isSuperAdmin', \Auth::user())){
           return view('dashboard');            
       }      
    
        //user is not logged-in OR is NOT a super administrator, so it's a 403 Forbidden..!
        if(\Gate::denies('isSuperAdmin', \Auth::user())){
            return abort('403');
        } 
    }

    public function home(){
        //user must be logged-in (as a super administrator!), for this view to display...
        if (\Gate::allows('isSuperAdmin', \Auth::user())){
            return view('home');            
        }      
        
        //user is not logged-in OR is NOT a super administrator, so it's a 403 Forbidden..!
        if(\Gate::denies('isSuperAdmin', \Auth::user())){
            return abort('403');
        }
    }

    public function private(){

        //user must be logged-in (as a super administrator!), for this view to display...
        if (\Gate::allows('isSuperAdmin', \Auth::user())){
            return view('private');            
        }      
        
        //user is not logged-in OR is NOT a super administrator, so it's a 403 Forbidden..!
        if (\Gate::denies('isSuperAdmin', \Auth::user())){
            return abort('403');
        }
           
    }

    public function about(){
        if (\Gate::denies('isSuperAdmin', \Auth::user())){
            abort(403,'Sorry you cannot view this webpage');
        }

        if (\Gate::allows('isSuperAdmin', \Auth::user())){            
            return view('about');
        } 
    }
   




    public function create(){
        $user = Auth::user();
        
        if ($user->can('create', Administrator::class)){
            echo 'Logged-in user is allowed to create an administrator';
        } else {
            echo 'Not authorised to create.';
        }
    }

    public function view(){
        $user = Auth::user();
        $accountant = Administrator::find(1);

        if ($user->can('view', $administrator)){
            echo 'Logged-in user is allowed to view the administrator {$administrator->id}';
        } else {
            echo 'Not authorised to view.';
        }
    }

    public function update(){
        $user = Auth::user();
        $accountant = Administrator::find(1);

        if ($user->can('update', $administrator)){
            echo 'Logged-in user is allowed to update the administrator {$administrator->id}';
        } else {
            echo 'Not authorised to update.';
        }
    }

    public function delete(){
        $user = Auth::user();
        $accountant = Administrator::find(1);

        if ($user->can('delete', $administrator)){
            echo 'Logged-in user is allowed to delete the administrator {$administrator->id}';
        } else {
            echo 'Not authorised to delete.';
        }
    }
}
