<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class AssignmentPolicy
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

    public function view_assignments(User $user, Assignment $assignment){
        /*
        if (($user->user_type === 'super_admin') || ($user->user_type === 'company_ceo')){
            return true;
        }
        */

        //OR use the following boolean, instead of the boolean above
        return $user->user_type(['super_admin', 'company_ceo']);

        //return () && $assignment->user_id;       
        // return $user->id == $assignment->$user_id; 
    }

    public function view_single_assignment(User $user, Assignment $assignment){
        if (($user->user_type === 'super_admin') || ($user->user_type === 'company_ceo')){
            return true;
        }
    }

    public function create_assignment(User $user, Assignment $assignment){
        if (($user->user_type === 'super_admin') || ($user->user_type === 'company_ceo')){
            return true;
        }
    }

    public function update_assignment(User $user, Assignment $assignment){
        if (($user->user_type === 'super_admin') || ($user->user_type === 'company_ceo')){
            return true;
        }
    }

    public function delete_assignment(User $user, Assignment $assignment){
        if (($user->user_type === 'super_admin') || ($user->user_type === 'company_ceo')){
            return true;
        }
    }
}
