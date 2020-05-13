<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WarehouseForemanController extends Controller
{
    //

    public function create(){
        $user = Auth::user();
        
        if ($user->can('create', WarehouseForeman::class)){
            echo 'Logged-in user is allowed to create a warehouse foreman';
        } else {
            echo 'Not authorised to create.';
        }
    }

    public function view(){
        $user = Auth::user();
        $accountant = WarehouseForeman::find(1);

        if ($user->can('view', $warehouseforeman)){
            echo 'Logged-in user is allowed to view the warehouse foreman {$warehouseforeman->id}';
        } else {
            echo 'Not authorised to view.';
        }
    }

    public function update(){
        $user = Auth::user();
        $accountant = WarehouseForeman::find(1);

        if ($user->can('update', $warehouseforeman)){
            echo 'Logged-in user is allowed to update the warehouse foreman {$warehouseforeman->id}';
        } else {
            echo 'Not authorised to update.';
        }
    }

    public function delete(){
        $user = Auth::user();
        $accountant = WarehouseForeman::find(1);

        if ($user->can('delete', $warehouseforeman)){
            echo 'Logged-in user is allowed to delete the warehouse foreman {$warehouseforeman->id}';
        } else {
            echo 'Not authorised to delete.';
        }
    }
}
