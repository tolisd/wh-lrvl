<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class CompanyCEOPolicy
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

    public function view(User $user, CompanyCEO $companyceo){
        return true;
    }

    public function update(User $user, CompanyCEO $companyceo){
        return $user->id === $companyceo->companyceo_id;
    }

    public function delete(User $user, CompanyCEO $companyceo){
        return $user->id === $companyceo->companyceo_id;
    }
        
}
