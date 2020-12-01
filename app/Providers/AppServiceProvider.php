<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

use Illuminate\Contracts\Events\Dispatcher;
use JeroenNoten\LaravelAdminLte\Events\BuildingMenu;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
        // config(['app.locale' => 'el_GR']);
        // \Carbon\Carbon::setLocale('el_GR');
        // \Carbon\Carbon::setLocale(LC_ALL, app()->getLocale());
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot(Dispatcher $events)
    {
        //

        // Localization Carbon, added this line as el_GR in app.locale
        // \Carbon\Carbon::setLocale(config('app.locale'));
        // \Carbon\Carbon::setLocale(LC_ALL, 'el_GR');
        setlocale(LC_ALL, "el_GR.UTF-8");
        \Carbon\Carbon::setLocale(config('app.locale'));


        $events->listen(BuildingMenu::class, function (BuildingMenu $event) {
            /*
            $event->menu->add('MAIN NAVIGATION');
            $event->menu->add([
                'text' => 'Back to Home',
                'url' => '/home',
            ]);

            $event->menu->add(['header'=>'GENERAL OPTIONS', 'can'=>['isSuperAdmin','isCompanyCEO']]);
            $event->menu->add([
                'text' => 'View Stock Availability',
                'url' => '/stock/view',
                'can' => ['isSuperAdmin', 'isCompanyCEO'],
            ]);
            $event->menu->add([
                'text' => 'Charge Toolkit',
                'url' => '/charge-toolkit',
                'can' => 'isSuperAdmin',
            ]);
            $event->menu->add([
                'text' => 'Create Invoice',
                'url' => '/invoice/create',
                'can' => 'isSuperAdmin',
            ]);

            $event->menu->add(['header'=>'MANAGE ASSIGNMENTS', 'can'=>['isSuperAdmin']]);
            $event->menu->add([
                'text' => 'View Open Assignments',
                'url' => '/assignments/view',
                'can' => 'isSuperAdmin',
                'icon' => 'far fa-fw fa-file',
            ]);
            $event->menu->add([
                'text' => 'Update Assignment',
                'url' => '/assignment/{id}/update',
                'can' => 'isSuperAdmin',
                'icon' => 'far fa-fw fa-file',
            ]);
            $event->menu->add([
                'text' => 'Delete Assignment',
                'url' => '/assignment/delete',
                'can' => 'isSuperAdmin',
                'icon' => 'far fa-fw fa-file',
            ]);

            $event->menu->add(['header'=>'CREATE NEW ASSIGNMENT', 'can'=>['isSuperAdmin']]);
            $event->menu->add([
                'text' => 'Create New Import Assignment',
                'url' => '/assignment/import/create',
                'can' => 'isSuperAdmin',
                'icon' => 'far fa-fw fa-file',
            ]);
            $event->menu->add([
                'text' => 'Create New Export Assignment',
                'url' => '/assignment/export/create',
                'can' => 'isSuperAdmin',
                'icon' => 'far fa-fw fa-file',
            ]);


            $event->menu->add(['header'=>'MANAGE STOCK', 'can'=>['isSuperAdmin']]);
            $event->menu->add([
                'text' => 'View All Products',
                'url' => '/products/view',
                'can' => 'isSuperAdmin',
                'icon' => 'far fa-fw fa-file',
            ]);
            $event->menu->add([
                'text' => 'View Single Product',
                'url' => '/product/{id}/view',
                'can' => 'isSuperAdmin',
                'icon' => 'far fa-fw fa-file',
            ]);
            $event->menu->add([
                'text' => 'Create New Product',
                'url' => '/product/create',
                'can' => 'isSuperAdmin',
                'icon' => 'far fa-fw fa-file',
            ]);
            $event->menu->add([
                'text' => 'Update Product',
                'url' => '/product/update',
                'can' => 'isSuperAdmin',
                'icon' => 'far fa-fw fa-file',
            ]);
            $event->menu->add([
                'text' => 'Delete Product',
                'url' => '/product/delete',
                'can' => 'isSuperAdmin',
                'icon' => 'far fa-fw fa-file',
            ]);

            $event->menu->add(['header'=>'USER SETTINGS', 'can'=>['isSuperAdmin']]);
            $event->menu->add([
                'text' => 'Create New User',
                'url' => '/profile/create',
                'can' => 'isSuperAdmin',
            ]);
            $event->menu->add([
                'text' => 'Edit Existing User',
                'url' => '/profile/{id}/edit',
                'can' => 'isSuperAdmin',
            ]);
            $event->menu->add([
                'text' => 'Delete Existing User',
                'url' => '/profile/{id}/delete',
                'can' => 'isSuperAdmin',
            ]);

            $event->menu->add(['header'=>'ACCOUNT SETTINGS', 'can'=>['isSuperAdmin']]);
            $event->menu->add([
                'text' => 'View User Profile',
                'url' => '/profile/{id}/view',
                'can' => 'isSuperAdmin',
                'icon' => 'fas fa-fw fa-user',
            ]);
            $event->menu->add([
                'text' => 'Change User Password',
                'url' => '/password/{id}/change-password',
                'can' => 'isSuperAdmin',
                'icon' => 'fas fa-fw fa-lock',
            ]);
            */

        });
    }
}
