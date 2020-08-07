<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseWorkerController extends Controller
{
    //

    public function home(){
        //Warehouse foreman
        if (\Gate::allows('isWarehouseWorker', \Auth::user())){
            return view('home');
        }

        if (\Gate::denies('isWarehouseWorker', \Auth::user())){
            return abort('403');
        }
    }

    public function create(){
        $user = Auth::user();

        if ($user->can('create', WarehouseWorker::class)){
            echo 'Logged-in user is allowed to create a warehouse worker';
        } else {
            echo 'Not authorised to create.';
        }
    }

    public function view(){
        $user = Auth::user();
        $warehouseworker = WarehouseWorker::find(1);

        if ($user->can('view', $warehouseworker)){
            echo 'Logged-in user is allowed to view the warehouse worker {$warehouseworker->id}';
        } else {
            echo 'Not authorised to view.';
        }
    }

    public function update(){
        $user = Auth::user();
        $warehouseworker = WarehouseWorker::find(1);

        if ($user->can('update', $warehouseworker)){
            echo 'Logged-in user is allowed to update the warehouse worker {$warehouseworker->id}';
        } else {
            echo 'Not authorised to update.';
        }
    }

    public function delete(){
        $user = Auth::user();
        $warehouseworker = WarehouseWorker::find(1);

        if ($user->can('delete', $warehouseforeman)){
            echo 'Logged-in user is allowed to delete the warehouse worker {$warehouseworker->id}';
        } else {
            echo 'Not authorised to delete.';
        }
    }
}
