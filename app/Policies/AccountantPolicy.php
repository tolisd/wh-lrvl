<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AccountantPolicy
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

    public function view(User $user, Accountant $accountant){
        return true;
    }

    public function update(User $user, Accountant $accountant){
        return $user->id === $accountant->accountant_id;
    }

    public function delete(User $user, Accountant $accountant){
        return $user->id === $accountant->accountant_id;
    }

}
