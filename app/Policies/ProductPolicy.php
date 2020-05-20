<?php

namespace App\Policies;

use App\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class ProductPolicy
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

    
    public function view_products(User $user, Product $product){

    }

    public function view_single_product(User $user, Product $product){

    }

    public function create_new_product(User $user, Product $product){

    }

    public function update_product(User $user, Product $product){

    }

    public function delete_product(User $user, Product $product){

    }
}
