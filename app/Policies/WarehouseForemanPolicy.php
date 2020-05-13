<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehouseForemanPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function create(User $user){
        return $user->id > 0;
    }

    public function view(User $user, WarehouseForeman $warehouseforeman){
        return true;
    }

    public function update(User $user, WarehouseForeman $warehouseforeman){
        return $user->id === $warehouseforeman->warehouseforeman_id;
    }

    public function delete(User $user, WarehouseForeman $warehouseforeman){
        return $user->id === $warehouseforeman->warehouseforeman_id;
    }
}
