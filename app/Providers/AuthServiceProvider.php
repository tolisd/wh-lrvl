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
        'App\Model' => 'App\Policies\ModelPolicy',
        Accountant::class => AccountantPolicy::class, //registered accountant policy
        CompanyCEO::class => CompanyCEOPolicy::class, //registered company_ceo policy
        WarehouseForeman::class => WarehouseForemanPolicy::class, //registered warehouseForeman policy
        WarehouseWorker::class => WarehouseWorkerPolicy::class, //registered warehouseWorker policy
        Administrator::class => AdministratorPolicy::class, //registered administrator policy
    ];

    /**
     * Register any authentication / authorization services.
     *
     * @return void
     */
    public function boot()
    {
        $this->registerPolicies();

        //added the following 6 roles within the company
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

        Gate::define('isNormalUser', function($user){
            return $user->user_type == 'normal_user';
        });

        //more permissions
        Gate::define('see-dashboard', function($user){
            return ($user->user_type == 'super_admin') 
                   || ($user->user_type == 'company_ceo') 
                   || ($user->user_type == 'accountant');
        });

    }
}
