<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AccountantController extends Controller
{
    //

    public function home(){
        return view('home');
    }

    public function create(){
        $user = Auth::user();
        
        if ($user->can('create', Accountant::class)){
            echo 'Logged-in user is allowed to create an accountant';
        } else {
            echo 'Not authorised to create.';
        }
    }

    public function view(){
        $user = Auth::user();
        $accountant = Accountant::find(1);

        if ($user->can('view', $accountant)){
            echo 'Logged-in user is allowed to view the accountant {$accountant->id}';
        } else {
            echo 'Not authorised to view.';
        }
    }

    public function update(){
        $user = Auth::user();
        $accountant = Accountant::find(1);

        if ($user->can('update', $accountant)){
            echo 'Logged-in user is allowed to update the accountant {$accountant->id}';
        } else {
            echo 'Not authorised to update.';
        }
    }

    public function delete(){
        $user = Auth::user();
        $accountant = Accountant::find(1);

        if ($user->can('delete', $accountant)){
            echo 'Logged-in user is allowed to delete the accountant {$accountant->id}';
        } else {
            echo 'Not authorised to delete.';
        }
    }

}
