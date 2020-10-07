<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    /**
     * The policy mappings for the application.
     *
     * @var array
     */
    protected $policies = [
        //'App\Model' => 'App\Policies\ModelPolicy',
        Accountant::class => AccountantPolicy::class, //registered accountant policy
        CompanyCEO::class => CompanyCEOPolicy::class, //registered company_ceo policy
        WarehouseForeman::class => WarehouseForemanPolicy::class, //registered warehouseForeman policy
        WarehouseWorker::class => WarehouseWorkerPolicy::class, //registered warehouseWorker policy
        Administrator::class => AdministratorPolicy::class, //registered administrator policy
        Assignment::class => AssignmentPolicy::class,
        Product::class => ProductPolicy::class,
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //added the following 7 Roles within the application/company
        //check if specific user is authorised

        Gate::define('isSuperAdmin', function($user){
            return $user->user_type == 'super_admin';
        });

        Gate::define('isCompanyCEO', function($user){
            return $user->user_type == 'company_ceo';
        });

        Gate::define('isAccountant', function($user){
            return $user->user_type == 'accountant';
        });

        Gate::define('isWarehouseForeman', function($user){
            return $user->user_type == 'warehouse_foreman';
        });

        Gate::define('isWarehouseWorker', function($user){
            return $user->user_type == 'warehouse_worker';
        });

        Gate::define('isTechnician', function($user){ //added new role
            return $user->user_type == 'technician';
        });

        Gate::define('isNormalUser', function($user){
            return $user->user_type == 'normal_user';
        });


        //Permissions on Actions
        Gate::define('see-dashboard', 'DashboardController@view_dashboard');

        //Assignment Gates Definitions
        Gate::define('create-assignment', 'AssignmentPolicy@create_assignment');
        Gate::define('view-assignments', 'AssignmentPolicy@view_assignments');
        Gate::define('view-single-assignment', 'AssignmentPolicy@view_single_assignment');
        Gate::define('update-assignment', 'AssignmentPolicy@update_assignment');
        Gate::define('delete-assingment', 'AssignmentPolicy@delete_assignment');

        //Product Gates Definitions
        Gate::define('view-products', 'ProductPolicy@view_products');
        Gate::define('view-single-product', 'ProductPolicy@view_single_product');
        Gate::define('create-new-product', 'ProductPolicy@create_new_product');
        Gate::define('update-product', 'ProductPolicy@update_product');
        Gate::define('delete-product', 'ProductPolicy@delete_product');
    }
}
