<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class WarehouseWorkerPolicy
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

    public function view(User $user, WarehouseWorker $warehouseworker){
        return true;
    }

    public function update(User $user, WarehouseWorker $warehouseworker){
        return $user->id === $warehouseworker->warehouseworker_id;
    }

    public function delete(User $user, WarehouseWorker $warehouseworker){
        return $user->id === $warehouseworker->warehouseworker_id;
    }
}
