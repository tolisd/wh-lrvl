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

    'right_sidebar' => true,
    'right_sidebar_icon' => 'fas fa-cog',
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

    //'dashboard_url' => 'home', //is changed to the following line:
    'dashboard_url' => 'dashboard',

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

        /*
        [
            'text' => 'Αρχική Σελίδα',
            'url' => '/home',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant', 'isWarehouseWorker', 'isNormalUser'],
            'icon' => 'fas fa-globe',
        ],
        */
        [
            'text' => 'Αρχική Σελίδα',
            'route' => 'admin.dashboard',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-globe',
        ],
		[
            'text' => 'Αρχική Σελίδα',
            'route' => 'manager.dashboard',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-globe',
        ],
		[
            'text' => 'Αρχική Σελίδα',
            'route' => 'accountant.dashboard',
            'can' => 'isAccountant',
            'icon' => 'fas fa-globe',
        ],
		[
            'text' => 'Αρχική Σελίδα',
            'route' => 'foreman.dashboard',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-globe',
        ],
		[
            'text' => 'Αρχική Σελίδα',
            'route' => 'worker.dashboard',
            'can' => 'isWarehouseWorker',
            'icon' => 'fas fa-globe',
        ],
		[
            'text' => 'Αρχική Σελίδα',
            'route' => 'user.dashboard',
            'can' => 'isNormalUser',
            'icon' => 'fas fa-globe',
        ],

        [
            'header' => 'ΓΕΝΙΚΕΣ ΕΠΙΛΟΓΕΣ',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman']
        ],
        [
            'text' => 'Διαθεσιμότητα Στοκ',
            'route' => 'admin.stock.view',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-server',
        ],
        [
            'text' => 'Διαθεσιμότητα Στοκ',
            'route' => 'manager.stock.view',
            'can' =>   'isCompanyCEO',
            'icon' => 'fas fa-server',
        ],


        [
            'text' => 'Επιλογές Εργαλείων',
            'url' => '',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-wrench',

            'submenu' => [
                                    [
                                        'text' => 'Όλα τα Εργαλεία',
                                        'route'  => 'admin.tools.view',
                                        'can' => 'isSuperAdmin',
                                        'icon' => 'fas fa-tools',
                                    ],
                                    [
                                        'text' => 'Χρεωμένα Εργαλεία',
                                        'route'  => 'admin.tools.charged.view',
                                        'can' => 'isSuperAdmin',
                                        'icon' => 'fas fa-fw fa-circle',
                                    ],
									[
                                        'text' => 'Μη Χρεωμένα Εργαλεία',
                                        'route'  => 'admin.tools.noncharged.view',
                                        'can' => 'isSuperAdmin',
                                        'icon' => 'far fa-fw fa-circle',
                                    ],

                          ],
		],
		[
            'text' => 'Επιλογές Εργαλείων',
            'url' => '',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-wrench',

            'submenu' => [
                                    [
                                        'text' => 'Όλα τα Εργαλεία',
                                        'route'  => 'manager.tools.view',
                                        'can' => 'isCompanyCEO',
                                        'icon' => 'fas fa-tools',
                                    ],
                                    [
                                        'text' => 'Χρεωμένα Εργαλεία',
                                        'route'  => 'manager.tools.charged.view',
                                        'can' => 'isCompanyCEO',
                                        'icon' => 'fas fa-fw fa-circle',
                                    ],
									[
                                        'text' => 'Μη Χρεωμένα Εργαλεία',
                                        'route'  => 'manager.tools.noncharged.view',
                                        'can' => 'isCompanyCEO',
                                        'icon' => 'far fa-fw fa-circle',
                                    ],

                          ],
		],
		[
            'text' => 'Επιλογές Εργαλείων',
            'url' => '',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-wrench',

            'submenu' => [
                                    [
                                        'text' => 'Όλα τα Εργαλεία',
                                        'route'  => 'foreman.tools.view',
                                        'can' => 'isWarehouseForeman',
                                        'icon' => 'fas fa-tools',
                                    ],
                                    [
                                        'text' => 'Χρεωμένα Εργαλεία',
                                        'route'  => 'foreman.tools.charged.view',
                                        'can' => 'isWarehouseForeman',
                                        'icon' => 'fas fa-fw fa-circle',
                                    ],
									[
                                        'text' => 'Μη Χρεωμένα Εργαλεία',
                                        'route'  => 'foreman.tools.noncharged.view',
                                        'can' => 'isWarehouseForeman',
                                        'icon' => 'far fa-fw fa-circle',
                                    ],
									[
                                        'text' => 'Τα Χρεωμένα μου Εργαλεία',
                                        'route'  => 'foreman.tools.mycharged.view',
                                        'can' => 'isWarehouseForeman',
                                        'icon' => 'fas fa-toolbox',
                                    ],
                          ],
		],
		[
            'text' => 'Επιλογές Εργαλείων',
            'url' => '',
            'can' => 'isWarehouseWorker',
            'icon' => 'fas fa-wrench',

            'submenu' => [
                                    [
                                        'text' => 'Τα Χρεωμένα μου Εργαλεία',
                                        'route'  => 'worker.tools.mycharged.view',
                                        'can' => 'isWarehouseWorker',
                                        'icon' => 'fas fa-toolbox',
                                    ],
                          ],
		],


        /*
        [
            'text' => 'Χρέωση Εργαλείων',
            'route' => 'admin.chargetoolkit',
            'can' => 'isSuperAdmin',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Χρέωση Εργαλείων',
            'route' => 'manager.chargetoolkit',
            'can' => 'isCompanyCEO',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Χρέωση Εργαλείων',
            'route' => 'foreman.chargetoolkit',
            'can' => 'isWarehouseForeman',
            'icon' => 'far fa-fw fa-circle',
        ],
        */

        /*
        [
            'text' => 'Δημιουργία Τιμολογίου',
            'route' => 'admin.invoicecreate',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-plus',
        ],
        [
            'text' => 'Δημιουργία Τιμολογίου',
            'route' => 'manager.invoicecreate',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-plus',
        ],
        [
            'text' => 'Δημιουργία Τιμολογίου',
            'route' => 'accountant.invoicecreate',
            'can' =>  'isAccountant',
            'icon' => 'fas fa-plus',
        ],
        */


        [
            'header' => 'ΔΙΑΧΕΙΡΙΣΗ ΑΝΑΘΕΣΕΩΝ',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman', 'isNormalUser'],
        ],
        [
            'text' => 'Αναθέσεις Εισαγωγής',
            'route' => 'admin.assignments.import.view',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-arrow-left',
        ],
        [
            'text' => 'Αναθέσεις Εισαγωγής',
            'route' => 'manager.assignments.import.view',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-arrow-left',
        ],
        [
            'text' => 'Αναθέσεις Εισαγωγής',
            'route' => 'accountant.assignments.import.view',
            'can' => 'isAccountant',
            'icon' => 'fas fa-fw fa-arrow-left',
        ],
        [
            'text' => 'Αναθέσεις Εισαγωγής',
            'route' => 'foreman.assignments.import.view',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-fw fa-arrow-left',
        ],
        [
            'text' => 'Αναθέσεις Εισαγωγής',
            'route' => 'user.assignments.import.view',
            'can' => 'isNormalUser',
            'icon' => 'fas fa-fw fa-arrow-left',
        ],


        [
            'text' => 'Αναθέσεις Εξαγωγής',
            'route' => 'admin.assignments.export.view',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-arrow-right',
        ],
        [
            'text' => 'Αναθέσεις Εξαγωγής',
            'route' => 'manager.assignments.export.view',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-arrow-right',
        ],
        [
            'text' => 'Αναθέσεις Εξαγωγής',
            'route' => 'accountant.assignments.export.view',
            'can' => 'isAccountant',
            'icon' => 'fas fa-fw fa-arrow-right',
        ],
        [
            'text' => 'Αναθέσεις Εξαγωγής',
            'route' => 'foreman.assignments.export.view',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-fw fa-arrow-right',
        ],
        [
            'text' => 'Αναθέσεις Εξαγωγής',
            'route' => 'user.assignments.export.view',
            'can' => 'isNormalUser',
            'icon' => 'fas fa-fw fa-arrow-right',
        ],




        [
            'text' => 'Πληροφορίες Εισαγωγών',
            'route' => 'admin.imports.view',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-circle',
        ],
        [
            'text' => 'Πληροφορίες Εισαγωγών',
            'route' => 'manager.imports.view',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-circle',
        ],
        [
            'text' => 'Πληροφορίες Εισαγωγών',
            'route' => 'accountant.imports.view',
            'can' => 'isAccountant',
            'icon' => 'fas fa-fw fa-circle',
        ],
        [
            'text' => 'Πληροφορίες Εισαγωγών',
            'route' => 'foreman.imports.view',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-fw fa-circle',
        ],


        [
            'text' => 'Πληροφορίες Εξαγωγών',
            'route' => 'admin.exports.view',
            'can' => 'isSuperAdmin',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Πληροφορίες Εξαγωγών',
            'route' => 'manager.exports.view',
            'can' => 'isCompanyCEO',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Πληροφορίες Εξαγωγών',
            'route' => 'accountant.exports.view',
            'can' => 'isAccountant',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Πληροφορίες Εξαγωγών',
            'route' => 'foreman.exports.view',
            'can' => 'isWarehouseForeman',
            'icon' => 'far fa-fw fa-circle',
        ],


        /*
        [
            'text' => 'Δημιουργία Ανάθεσης',
            'url' => '',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-plus',

            'submenu' => [
                                    [
                                        'text' => 'Δημ. Ανάθεσης Εισαγωγής',
                                        'route'  => 'admin.assignment.import.create',
                                        'can' => 'isSuperAdmin',
                                        'icon' => 'fas fa-fw fa-arrow-left',
                                    ],
                                    [
                                        'text' => 'Δημ. Ανάθεσης Εξαγωγής',
                                        'route'  => 'admin.assignment.export.create',
                                        'can' => 'isSuperAdmin',
                                        'icon' => 'fas fa-fw fa-arrow-right',
                                    ],
                          ],

        ],
        [
            'text' => 'Δημιουργία Ανάθεσης',
            'url' => '',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-plus',

            'submenu' => [
                                    [
                                        'text' => 'Δημ. Ανάθεσης Εισαγωγής',
                                        'route'  => 'manager.assignment.import.create',
                                        'can' => 'isCompanyCEO',
                                        'icon' => 'fas fa-fw fa-arrow-left',
                                    ],
                                    [
                                        'text' => 'Δημ. Ανάθεσης Εξαγωγής',
                                        'route'  => 'manager.assignment.export.create',
                                        'can' => 'isCompanyCEO',
                                        'icon' => 'fas fa-fw fa-arrow-right',
                                    ],
                          ],

        ],
        [
            'text' => 'Δημιουργία Ανάθεσης',
            'url' => '',
            'can' => 'isAccountant',
            'icon' => 'fas fa-plus',

            'submenu' => [
                                    [
                                        'text' => 'Δημ. Ανάθεσης Εισαγωγής',
                                        'route'  => 'accountant.assignment.import.create',
                                        'can' => 'isAccountant',
                                        'icon' => 'fas fa-fw fa-arrow-left',
                                    ],
                                    [
                                        'text' => 'Δημ. Ανάθεσης Εξαγωγής',
                                        'route'  => 'accountant.assignment.export.create',
                                        'can' => 'isAccountant',
                                        'icon' => 'fas fa-fw fa-arrow-right',
                                    ],
                          ],

        ],
        [
            'text' => 'Δημιουργία Ανάθεσης',
            'url' => '',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-plus',

            'submenu' => [
                                    [
                                        'text' => 'Δημ. Ανάθεσης Εισαγωγής',
                                        'route'  => 'foreman.assignment.import.create',
                                        'can' => 'isWarehouseForeman',
                                        'icon' => 'fas fa-fw fa-arrow-left',
                                    ],
                                    [
                                        'text' => 'Δημ. Ανάθεσης Εξαγωγής',
                                        'route'  => 'foreman.assignment.export.create',
                                        'can' => 'isWarehouseForeman',
                                        'icon' => 'fas fa-fw fa-arrow-right',
                                    ],
                          ],

        ],
        */




    /*
        [
            'text' => 'Μεταβολή Ανάθεσης',
            'route' => 'admin.assignment.update',
            'can' => 'isSuperAdmin',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Μεταβολή Ανάθεσης',
            'route' => 'manager.assignment.update',
            'can' => 'isCompanyCEO',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Μεταβολή Ανάθεσης',
            'route' => 'accountant.assignment.update',
            'can' => 'isAccountant',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Μεταβολή Ανάθεσης',
            'route' => 'foreman.assignment.update',
            'can' => 'isWarehouseForeman',
            'icon' => 'far fa-fw fa-circle',
        ],
    */
    /*

        [
            'text' => 'Διαγραφή Ανάθεσης',
            'route' => 'admin.assignment.delete',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-minus',
        ],
        [
            'text' => 'Διαγραφή Ανάθεσης',
            'route' => 'manager.assignment.delete',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-minus',
        ],
        [
            'text' => 'Διαγραφή Ανάθεσης',
            'route' => 'accountant.assignment.delete',
            'can' => 'isAccountant',
            'icon' => 'fas fa-fw fa-minus',
        ],
        [
            'text' => 'Διαγραφή Ανάθεσης',
            'route' => 'foreman.assignment.delete',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-fw fa-minus',
        ],
    */


        [
            'header' => 'ΔΙΑΧΕΙΡΙΣΗ ΠΡΟΪΟΝΤΩΝ',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'],
        ],


        [
            'text' => 'Επιλογές Προϊόντων',
            'url' => '',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-cubes',

            'submenu' => [
                                    [
										'text' => 'Όλα τα Προϊόντα',
										'route' => 'admin.products.view',
										'can' => 'isSuperAdmin',
										'icon' => 'fas fa-pallet',
									],
                                    [
										'text' => 'Κατηγορίες Προϊόντων',
										'route' => 'admin.category.view',
										'can' => 'isSuperAdmin',
										'icon' => 'fas fa-border-style',
									],
									[
										'text' => 'Είδη Προϊόντων',
										'route' => 'admin.type.view',
										'can' => 'isSuperAdmin',
										'icon' => 'fas fa-compress',
                                    ],
                                    [
										'text' => 'Μονάδες Μέτρησης',
										'route' => 'admin.measunit.view',
										'can' => 'isSuperAdmin',
										'icon' => 'fas fa-balance-scale',
									],
                         ],
        ],

		[
            'text' => 'Επιλογές Προϊόντων',
            'url' => '',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-cubes',

            'submenu' => [
                                    [
										'text' => 'Όλα τα Προϊόντα',
										'route' => 'manager.products.view',
										'can' => 'isCompanyCEO',
										'icon' => 'fas fa-pallet',
									],
                                    [
										'text' => 'Κατηγορίες Προϊόντων',
										'route' => 'manager.category.view',
										'can' => 'isCompanyCEO',
										'icon' => 'fas fa-border-style',
									],
									[
										'text' => 'Είδη Προϊόντων',
										'route' => 'manager.type.view',
										'can' => 'isCompanyCEO',
										'icon' => 'fas fa-compress',
                                    ],
                                    [
										'text' => 'Μονάδες Μέτρησης',
										'route' => 'manager.measunit.view',
										'can' => 'isCompanyCEO',
										'icon' => 'fas fa-balance-scale',
									],

                         ],
        ],


		[
            'text' => 'Επιλογές Προϊόντων',
            'url' => '',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-cubes',

            'submenu' => [
                                    [
										'text' => 'Όλα τα Προϊόντα',
										'route' => 'foreman.products.view',
										'can' => 'isWarehouseForeman',
										'icon' => 'fas fa-pallet',
									],
                                    [
										'text' => 'Κατηγορίες Προϊόντων',
										'route' => 'foreman.category.view',
										'can' => 'isWarehouseForeman',
										'icon' => 'fas fa-border-style',
									],
									[
										'text' => 'Είδη Προϊόντων',
										'route' => 'foreman.type.view',
										'can' => 'isWarehouseForeman',
										'icon' => 'fas fa-compress',
                                    ],
                                    [
										'text' => 'Μονάδες Μέτρησης',
										'route' => 'foreman.measunit.view',
										'can' => 'isWarehouseForeman',
										'icon' => 'fas fa-balance-scale',
									],

                         ],
        ],

		[
            'text' => 'Επιλογές Προϊόντων',
            'url' => '',
            'can' => 'isWarehouseWorker',
            'icon' => 'fas fa-cubes',

            'submenu' => [
                                    [
										'text' => 'Όλα τα Προϊόντα',
										'route' => 'worker.products.view',
										'can' => 'isWarehouseWorker',
										'icon' => 'fas fa-pallet',
									],
                                    [
										'text' => 'Κατηγορίες Προϊόντων',
										'route' => 'worker.category.view',
										'can' => 'isWarehouseWorker',
										'icon' => 'fas fa-border-style',
									],
									[
										'text' => 'Είδη Προϊόντων',
										'route' => 'worker.type.view',
										'can' => 'isWarehouseWorker',
										'icon' => 'fas fa-compress',
                                    ],
                                    [
										'text' => 'Μονάδες Μέτρησης',
										'route' => 'worker.measunit.view',
										'can' => 'isWarehouseWorker',
										'icon' => 'fas fa-balance-scale',
									],

                         ],
        ],


        /*
        [
            'text' => 'Όλα τα Προϊόντα',
            'route' => 'admin.products.view',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-server',
        ],
        [
            'text' => 'Όλα τα Προϊόντα',
            'route' => 'manager.products.view',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-server',
        ],
        [
            'text' => 'Όλα τα Προϊόντα',
            'route' => 'foreman.products.view',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-fw fa-server',
        ],
        [
            'text' => 'Όλα τα Προϊόντα',
            'route' => 'worker.products.view',
            'can' => 'isWarehouseWorker',
            'icon' => 'fas fa-fw fa-server',
        ],
        */

        /*
        [
            'text' => 'Δες Προϊόν',
            'route' => 'admin.product.view',
            'can' => 'isSuperAdmin',
            'icon' => 'far fa-fw fa-eye',
        ],
        [
            'text' => 'Δες Προϊόν',
            'route' => 'manager.product.view',
            'can' => 'isCompanyCEO',
            'icon' => 'far fa-fw fa-eye',
        ],
        [
            'text' => 'Δες Προϊόν',
            'route' => 'foreman.product.view',
            'can' => 'isWarehouseForeman',
            'icon' => 'far fa-fw fa-eye',
        ],
        [
            'text' => 'Δες Προϊόν',
            'route' => 'worker.product.view',
            'can' => 'isWarehouseWorker',
            'icon' => 'far fa-fw fa-eye',
        ],
        */

/*
        [
            'text' => 'Δημιουργία Νέου Προϊόντος',
            'route' => 'admin.product.create',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-plus',
        ],
        [
            'text' => 'Δημιουργία Νέου Προϊόντος',
            'route' => 'manager.product.create',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-plus',
        ],
        [
            'text' => 'Δημιουργία Νέου Προϊόντος',
            'route' => 'foreman.product.create',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-fw fa-plus',
        ],
        [
            'text' => 'Δημιουργία Νέου Προϊόντος',
            'route' => 'worker.product.create',
            'can' => 'isWarehouseWorker',
            'icon' => 'fas fa-fw fa-plus',
        ],

*/

/*
        [
            'text' => 'Κατηγορίες Προϊόντων',
            'route' => 'admin.category.view',
            'can' => 'isSuperAdmin',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Κατηγορίες Προϊόντων',
            'route' => 'manager.category.view',
            'can' => 'isCompanyCEO',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Κατηγορίες Προϊόντων',
            'route' => 'foreman.category.view',
            'can' => 'isWarehouseForeman',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Κατηγορίες Προϊόντων',
            'route' => 'worker.category.view',
            'can' => 'isWarehouseWorker',
            'icon' => 'far fa-fw fa-circle',
        ],



        [
            'text' => 'Είδη Προϊόντων',
            'route' => 'admin.type.view',
            'can' => 'isSuperAdmin',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Είδη Προϊόντων',
            'route' => 'manager.type.view',
            'can' => 'isCompanyCEO',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Είδη Προϊόντων',
            'route' => 'foreman.type.view',
            'can' => 'isWarehouseForeman',
            'icon' => 'far fa-fw fa-circle',
        ],
        [
            'text' => 'Είδη Προϊόντων',
            'route' => 'worker.type.view',
            'can' => 'isWarehouseWorker',
            'icon' => 'far fa-fw fa-circle',
        ],
        */

    /*
        [
            'text' => 'Διαγραφή Προϊόντος',
            'route' => 'admin.product.delete',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-minus',
        ],
        [
            'text' => 'Διαγραφή Προϊόντος',
            'route' => 'manager.product.delete',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-minus',
        ],
        [
            'text' => 'Διαγραφή Προϊόντος',
            'route' => 'foreman.product.delete',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-fw fa-minus',
        ],
        [
            'text' => 'Διαγραφή Προϊόντος',
            'route' => 'worker.product.delete',
            'can' => 'isWarehouseWorker',
            'icon' => 'fas fa-fw fa-minus',
        ],
    */


        [
            'header' => 'ΔΙΑΧΕΙΡΙΣΗ ΧΡΗΣΤΩΝ',
            'can' => ['isSuperAdmin', 'isCompanyCEO'],
        ],


        [
            'text' => 'Χρήστες Εφαρμογής',
            'route'  => 'admin.users.view',
            'can' => 'isSuperAdmin',
            'icon' => 'far fa-user',
        ],
        [
            'text' => 'Χρήστες Εφαρμογής',
            'route'  => 'manager.users.view',
            'can' => 'isCompanyCEO',
            'icon' => 'far fa-user',
        ],




        [
            'header' => 'ΔΙΑΦΟΡΑ',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isAccountant'],
        ],


        [
            'text' => 'Εταιρικές Επιλογές',
            'url' => '',
            'can' => 'isSuperAdmin',
            'icon' => 'far fa-building',

            'submenu' => [
                                    [
                                        'text' => 'Εργαζόμενοι',
                                        'route'  => 'admin.employees.view',
                                        'can' => 'isSuperAdmin',
                                        'icon' => 'fas fa-fw fa-users',
                                    ],
                                    [
                                        'text' => 'Εταιρείες',
                                        'route'  => 'admin.companies.view',
                                        'can' => 'isSuperAdmin',
                                        'icon' => 'fas fa-industry',
                                    ],
									[
                                        'text' => 'Αποθήκες',
                                        'route'  => 'admin.warehouses.view',
                                        'can' => 'isSuperAdmin',
                                        'icon' => 'fas fa-warehouse',
                                    ],

                          ],
        ],

        [
            'text' => 'Εταιρικές Επιλογές',
            'url' => '',
            'can' => 'isCompanyCEO',
            'icon' => 'far fa-building',

            'submenu' => [
                                    [
                                        'text' => 'Εργαζόμενοι',
                                        'route'  => 'manager.employees.view',
                                        'can' => 'isCompanyCEO',
                                        'icon' => 'fas fa-fw fa-users',
                                    ],
                                    [
                                        'text' => 'Εταιρείες',
                                        'route'  => 'manager.companies.view',
                                        'can' => 'isCompanyCEO',
                                        'icon' => 'fas fa-industry',
                                    ],
									[
                                        'text' => 'Αποθήκες',
                                        'route'  => 'manager.warehouses.view',
                                        'can' => 'isCompanyCEO',
                                        'icon' => 'fas fa-warehouse',
                                    ],

                          ],
        ],

        [
            'text' => 'Εταιρικές Επιλογές',
            'url' => '',
            'can' => 'isAccountant',
            'icon' => 'far fa-building',

            'submenu' => [
                                    [
                                        'text' => 'Εργαζόμενοι',
                                        'route'  => 'accountant.employees.view',
                                        'can' => 'isAccountant',
                                        'icon' => 'fas fa-fw fa-users',
                                    ],
                                    [
                                        'text' => 'Εταιρείες',
                                        'route'  => 'accountant.companies.view',
                                        'can' => 'isAccountant',
                                        'icon' => 'fas fa-industry',
                                    ],
									[
                                        'text' => 'Αποθήκες',
                                        'route'  => 'accountant.warehouses.view',
                                        'can' => 'isAccountant',
                                        'icon' => 'fas fa-warehouse',
                                    ],

                          ],
        ],


        [
            'header' => 'ΑΠΟΣΥΝΔΕΣΗ',
            'can' => ['isSuperAdmin', 'isCompanyCEO', 'isAccountant', 'isWarehouseForeman', 'isWarehouseWorker', 'isNormalUser'],
        ],


        [
            'text' => 'Αποσύνδεση',
            'url'  => '/logout',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-power-off text-danger',
        ],
        [
            'text' => 'Αποσύνδεση',
            'url'  => '/logout',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-power-off text-danger',
        ],
        [
            'text' => 'Αποσύνδεση',
            'url'  => '/logout',
            'can' => 'isAccountant',
            'icon' => 'fas fa-power-off text-danger',
        ],
        [
            'text' => 'Αποσύνδεση',
            'url'  => '/logout',
            'can' => 'isWarehouseForeman',
            'icon' => 'fas fa-power-off text-danger',
        ],
        [
            'text' => 'Αποσύνδεση',
            'url'  => '/logout',
            'can' => 'isWarehouseWorker',
            'icon' => 'fas fa-power-off text-danger',
        ],
        [
            'text' => 'Αποσύνδεση',
            'url'  => '/logout',
            'can' => 'isNormalUser',
            'icon' => 'fas fa-power-off text-danger',
        ],










/*
        [
            'text' => 'Δημιουργία Νέου Χρήστη',
            'route' => 'admin.user.create',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-plus',
        ],
        [
            'text' => 'Δημιουργία Νέου Χρήστη',
            'route' => 'manager.user.create',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-plus',
        ],
*/
/*
        [
            'text' => 'Μεταβολή Χρήστη',
            'route' => 'admin.user.update',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-circle',
        ],
        [
            'text' => 'Μεταβολή Χρήστη',
            'route' => 'manager.user.update',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-circle',
        ],


        [
            'text' => 'Διαγραφή Χρήστη',
            'route' => 'admin.user.delete',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-minus',
        ],
        [
            'text' => 'Διαγραφή Χρήστη',
            'route' => 'manager.user.delete',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-minus',
        ],





        [
            'text' => 'Δες Προφίλ Χρήστη',
            'route'  => 'admin.user.view',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-user',
        ],
        [
            'text' => 'Δες Προφίλ Χρήστη',
            'route'  => 'manager.user.view',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-user',
        ],




        [
            'text' => 'Αλλαγή Κωδικού Χρήστη',
            'route'  => 'admin.user.change-password',
            'can' => 'isSuperAdmin',
            'icon' => 'fas fa-fw fa-lock',
        ],
        [
            'text' => 'Αλλαγή Κωδικού Χρήστη',
            'route'  => 'manager.user.change-password',
            'can' => 'isCompanyCEO',
            'icon' => 'fas fa-fw fa-lock',
        ],
 */




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
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/js/dataTables.bootstrap4.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.19/css/dataTables.bootstrap4.min.css',
                ],
            ],
        ],

        [
            'name' => 'Buttons',
            'active' => true,
            'files' => [
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.21/css/jquery.dataTables.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/buttons/1.6.2/css/buttons.dataTables.min.css',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/buttons/1.6.3/css/buttons.bootstrap4.min.css',
                ],


                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/1.10.21/js/jquery.dataTables.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/buttons/1.6.2/js/dataTables.buttons.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/buttons/1.6.1/js/buttons.html5.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/buttons/1.6.1/js/buttons.print.min.js',
                ],
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdn.datatables.net/buttons/1.6.3/js/buttons.bootstrap4.min.js',
                ],

            ],
        ],

        [
            'name' => 'DateTimePicker',
            'active' => true,
            'files' => [
                [
                    'type' => 'js',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.full.min.js',
                ],
                [
                    'type' => 'css',
                    'asset' => true,
                    'location' => '//cdnjs.cloudflare.com/ajax/libs/jquery-datetimepicker/2.5.20/jquery.datetimepicker.min.css',
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
            'active' => true,
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
