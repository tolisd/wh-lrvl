<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Title
    |--------------------------------------------------------------------------
    |
    | Here you can change the default title of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#61-title
    |
    */

    'title' => 'AdminLTE 3',
    'title_prefix' => '',
    'title_postfix' => '',

    /*
    |--------------------------------------------------------------------------
    | Favicon
    |--------------------------------------------------------------------------
    |
    | Here you can activate the favicon.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#62-favicon
    |
    */

    'use_ico_only' => false,
    'use_full_favicon' => false,

    /*
    |--------------------------------------------------------------------------
    | Logo
    |--------------------------------------------------------------------------
    |
    | Here you can change the logo of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#63-logo
    |
    */

    'logo' => '<b>Admin</b>LTE',
    'logo_img' => 'vendor/adminlte/dist/img/AdminLTELogo.png',
    'logo_img_class' => 'brand-image img-circle elevation-3',
    'logo_img_xl' => null,
    'logo_img_xl_class' => 'brand-image-xs',
    'logo_img_alt' => 'AdminLTE',

    /*
    |--------------------------------------------------------------------------
    | User Menu
    |--------------------------------------------------------------------------
    |
    | Here you can activate and change the user menu.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#64-user-menu
    |
    */

    'usermenu_enabled' => true,
    'usermenu_header' => false,
    'usermenu_header_class' => 'bg-primary',
    'usermenu_image' => false,
    'usermenu_desc' => false,

    /*
    |--------------------------------------------------------------------------
    | Layout
    |--------------------------------------------------------------------------
    |
    | Here we change the layout of your admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#65-layout
    |
    */

    'layout_topnav' => null,
    'layout_boxed' => null,
    'layout_fixed_sidebar' => null,
    'layout_fixed_navbar' => null,
    'layout_fixed_footer' => null,

    /*
    |--------------------------------------------------------------------------
    | Extra Classes
    |--------------------------------------------------------------------------
    |
    | Here you can change the look and behavior of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#66-classes
    |
    */

    'classes_body' => '',
    'classes_brand' => '',
    'classes_brand_text' => '',
    'classes_content_header' => '',
    'classes_content' => '',
    'classes_sidebar' => 'sidebar-dark-primary elevation-4',
    'classes_sidebar_nav' => '',
    'classes_topnav' => 'navbar-white navbar-light',
    'classes_topnav_nav' => 'navbar-expand-md',
    'classes_topnav_container' => 'container',

    /*
    |--------------------------------------------------------------------------
    | Sidebar
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#67-sidebar
    |
    */

    'sidebar_mini' => true,
    'sidebar_collapse' => false,
    'sidebar_collapse_auto_size' => false,
    'sidebar_collapse_remember' => false,
    'sidebar_collapse_remember_no_transition' => true,
    'sidebar_scrollbar_theme' => 'os-theme-light',
    'sidebar_scrollbar_auto_hide' => 'l',
    'sidebar_nav_accordion' => true,
    'sidebar_nav_animation_speed' => 300,

    /*
    |--------------------------------------------------------------------------
    | Control Sidebar (Right Sidebar)
    |--------------------------------------------------------------------------
    |
    | Here we can modify the right sidebar aka control sidebar of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#68-control-sidebar-right-sidebar
    |
    */

    'right_sidebar' => false,
    'right_sidebar_icon' => 'fas fa-cogs',
    'right_sidebar_theme' => 'dark',
    'right_sidebar_slide' => true,
    'right_sidebar_push' => true,
    'right_sidebar_scrollbar_theme' => 'os-theme-light',
    'right_sidebar_scrollbar_auto_hide' => 'l',

    /*
    |--------------------------------------------------------------------------
    | URLs
    |--------------------------------------------------------------------------
    |
    | Here we can modify the url settings of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#69-urls
    |
    */

    'use_route_url' => false,

    'dashboard_url' => 'home',

    'logout_url' => 'logout',

    'login_url' => 'login',

    'register_url' => 'register',

    'password_reset_url' => 'password/reset',

    'password_email_url' => 'password/email',

    'profile_url' => false,

    /*
    |--------------------------------------------------------------------------
    | Laravel Mix
    |--------------------------------------------------------------------------
    |
    | Here we can enable the Laravel Mix option for the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#610-laravel-mix
    |
    */

    'enabled_laravel_mix' => false,

    /*
    |--------------------------------------------------------------------------
    | Menu Items
    |--------------------------------------------------------------------------
    |
    | Here we can modify the sidebar/top navigation of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#611-menu
    |
    */

    'menu' => [

        [
            'text' => 'Αρχική Σελίδα',
            'url' => '/home',
            'can' => ['isSuperAdmin', 'isCompanyCEO'],	
            'icon' => 'fas fa-globe',	
        ],
    
        'ΓΕΝΙΚΕΣ ΕΠΙΛΟΓΕΣ',
        [
            'text' => 'Δες Διαθεσιμότητα Στοκ',
            'url' => '/stock/view',
            'can' => ['isSuperAdmin', 'isCompanyCEO'],	
            'icon' => 'fas fa-eye',	
        ],
    
        [
            'text' => 'Χρέωση Εργαλείων',
            'url' => '/charge-toolkit',
            'can' => ['isSuperAdmin', 'isCompanyCEO'],
            'icon' => 'fas fa-wrench',		
        ],
    
        [
            'text' => 'Δημιουργία Τιμολογίου',
            'url' => '/invoice/create',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isAccountant'],
            'icon' => 'fas fa-plus',		
        ],
    
    
        'ΔΙΑΧΕΙΡΙΣΗ ΑΝΑΘΕΣΕΩΝ',
        [
            'text' => 'Δες Ανοιχτές Αναθέσεις',
            'url' => '/assignments/view',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'],
            'icon' => 'far fa-fw fa-eye',
        ],
    
        [
            'text' => 'Δημιουργία Ανάθεσης',            
            'url' => '/assignment/create',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'],
            'icon' => 'fas fa-plus',
            
            'submenu' => [
                                    [
                                        'text' => 'Δημ.Ανάθεσης Εισαγωγής',
                                        'url'  => '/assignment/import/create',
                                        'can' => ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'],
                                        'icon' => 'fas fa-fw fa-arrow-left',
                                    ],
                                    [
                                        'text' => 'Δημ.Ανάθεσης Εξαγωγής',
                                        'url'  => '/assignment/export/create',
                                        'can' => ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'],
                                        'icon' => 'fas fa-fw fa-arrow-right',
                                    ],
                          ],
            
        ],
    
        [
            'text' => 'Μεταβολή Ανάθεσης',
            'url' => '/assignment/update',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'],
            'icon' => 'far fa-fw fa-circle',
        ],
    
        [
            'text' => 'Διαγραφή Ανάθεσης',
            'url' => '/assignment/delete',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman'],
            'icon' => 'fas fa-fw fa-minus',
        ],
    
    
    
        'ΔΙΑΧΕΙΡΙΣΗ ΣΤΟΚ',
        [
            'text' => 'Δες Όλα τα Προϊόντα',
            'url' => '/products/view',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'],
            'icon' => 'fas fa-fw fa-eye',
        ],
    
        [
            'text' => 'Δες Προϊόν',
            'url' => '/product/view/',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'],
            'icon' => 'far fa-fw fa-eye',
        ],
    
    
        [
            'text' => 'Δημιουργία Νέου Προϊόντος',
            'url' => '/product/create',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'],
            'icon' => 'fas fa-fw fa-plus',
        ],
    
        [
            'text' => 'Μεταβολή Προϊόντος',
            'url' => '/product/update',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'],
            'icon' => 'far fa-fw fa-circle',
        ],
    
        [
            'text' => 'Διαγραφή Προϊόντος',
            'url' => '/product/delete',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'],
            'icon' => 'fas fa-fw fa-minus',
        ],
      
    
    
        'ΔΙΑΧΕΙΡΙΣΗ ΧΡΗΣΤΩΝ',
        [
            'text' => 'Δημιουργία Νέου Χρήστη',
            'url' => '/profile/create',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant'],
            'icon' => 'fas fa-fw fa-plus',
        ],
        [
            'text' => 'Μεταβολή Χρήστη',
            'url' => '/profile/edit',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant'],
            'icon' => 'fas fa-fw fa-circle',
        ],
        [
            'text' => 'Διαγραφή Χρήστη',
            'url' => '/profile/delete',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant'],
            'icon' => 'fas fa-fw fa-minus',
        ],         
        [
            'text' => 'Δες Προφίλ Χρήστη',
            'url'  => '/profile/view',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant'],
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'text' => 'Αλλαγή Κωδικού Χρήστη',
            'url'  => 'profile/change-password',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant'],
            'icon' => 'fas fa-fw fa-lock',
        ],
        

        /*
        [
            'text' => 'search',
            'search' => true,
            'topnav' => true,
        ],          
        [
            'text' => 'blog',
            'url'  => 'admin/blog',
            'can'  => 'manage-blog',
        ],
        
        [
            'text'        => 'pages',
            'url'         => 'admin/pages',
            'icon'        => 'far fa-fw fa-file',
            'label'       => 4,
            'label_color' => 'success',
        ],        
        ['header' => 'account_settings'],
        [
            'text' => 'profile',
            'url'  => 'admin/settings',
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'text' => 'change_password',
            'url'  => 'admin/settings',
            'icon' => 'fas fa-fw fa-lock',
        ],
        [
            'text'    => 'multilevel',
            'icon'    => 'fas fa-fw fa-share',
            'submenu' => [
                [
                    'text' => 'level_one',
                    'url'  => '#',
                ],
                [
                    'text'    => 'level_one',
                    'url'     => '#',
                    'submenu' => [
                        [
                            'text' => 'level_two',
                            'url'  => '#',
                        ],
                        [
                            'text'    => 'level_two',
                            'url'     => '#',
                            'submenu' => [
                                [
                                    'text' => 'level_three',
                                    'url'  => '#',
                                ],
                                [
                                    'text' => 'level_three',
                                    'url'  => '#',
                                ],
                            ],
                        ],
                    ],
                ],
                [
                    'text' => 'level_one',
                    'url'  => '#',
                ],
            ],
        ],
        ['header' => 'labels'],
        [
            'text'       => 'important',
            'icon_color' => 'red',
        ],
        [
            'text'       => 'warning',
            'icon_color' => 'yellow',
        ],
        [
            'text'       => 'information',
            'icon_color' => 'cyan',
        ],
        */
    ],

    /*
    |--------------------------------------------------------------------------
    | Menu Filters
    |--------------------------------------------------------------------------
    |
    | Here we can modify the menu filters of the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#612-menu-filters
    |
    */

    'filters' => [
        JeroenNoten\LaravelAdminLte\Menu\Filters\HrefFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SearchFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ActiveFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\SubmenuFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\ClassesFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\GateFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\LangFilter::class,
        JeroenNoten\LaravelAdminLte\Menu\Filters\DataFilter::class,
    ],

    /*
    |--------------------------------------------------------------------------
    | Plugins Initialization
    |--------------------------------------------------------------------------
    |
    | Here we can modify the plugins used inside the admin panel.
    |
    | For more detailed instructions you can look here:
    | https://github.com/jeroennoten/Laravel-AdminLTE/#613-plugins
    |
    */

    'plugins' => [
        [
            'name' => 'Datatables',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],
        [
            'name' => 'Select2',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/js/select2.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/select2/4.0.3/css/select2.css',
                ],
            ],
        ],
        [
            'name' => 'Chartjs',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.0/Chart.bundle.min.js',
                ],
            ],
        ],
        [
            'name' => 'Sweetalert2',
            'active' => false,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdn.jsdelivr.net/npm/sweetalert2@8',
                ],
            ],
        ],
        [
            'name' => 'Pace',
            'active' => false,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/themes/blue/pace-theme-center-radar.min.css',
                ],
                [
                    'type' => 'js',
                    'asset' => false,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pace/1.0.2/pace.min.js',
                ],
            ],
        ],
    ],
];
