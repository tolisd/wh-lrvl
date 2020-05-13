<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AdministratorPolicy
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

    public function view(User $user, Administrator $administrator){
        return true;
    }

    public function update(User $user, Administrator $administrator){
        return $user->id === $administrator->administrator_id;
    }

    public function delete(User $user, Administrator $administrator){
        return $user->id === $administrator->administrator_id;
    }
}
