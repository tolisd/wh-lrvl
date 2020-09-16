<aside class="main-sidebar {{config('adminlte.classes_sidebar', 'sidebar-dark-primary elevation-4')}}">

    {{-- Sidebar brand logo --}}
    @if(config('adminlte.logo_img_xl'))
        @include('adminlte::partials.common.brand-logo-xl')
    @else
        @include('adminlte::partials.common.brand-logo-xs')
    @endif


    {{-- Sidebar menu --}}    
    <div class="sidebar">
    <!--
        <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
            <img src="dist/img/user2-160x160.jpg" class="img-circle elevation-2" alt="User Image">
            </div>
            <div class="info">
            <a href="#" class="d-block">Alexander Pierce</a>
            </div>
        </div>
    -->
        <nav class="mt-2">           
            <ul class="nav nav-pills nav-sidebar flex-column {{config('adminlte.classes_sidebar_nav', '')}}"
                data-widget="treeview" role="menu"
                @if(config('adminlte.sidebar_nav_animation_speed') != 300)
                    data-animation-speed="{{ config('adminlte.sidebar_nav_animation_speed') }}"
                @endif
                @if(!config('adminlte.sidebar_nav_accordion'))
                    data-accordion="false"
                @endif> 
                {{-- Configured sidebar links --}}    
                                                      
                @each('adminlte::partials.menuitems.menu-item', $adminlte->menu(), 'item')        
                    
               
                <!-- Add icons to the links using the .nav-icon class
                with font-awesome or any other icon font library -->

                <!--
                <li class="nav-item has-treeview menu-open">
                    <a href="#" class="nav-link active">
                    <i class="nav-icon fas fa-tachometer-alt"></i>
                    <p>
                        Starter Pages
                        <i class="right fas fa-angle-left"></i>
                    </p>
                    </a>

                    <ul class="nav nav-treeview">

                        <li class="nav-item">
                            <a href="#" class="nav-link active">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Active Page</p>
                            </a>
                        </li>

                        <li class="nav-item">
                            <a href="#" class="nav-link">
                            <i class="far fa-circle nav-icon"></i>
                            <p>Inactive Page</p>
                            </a>
                        </li>

                    </ul>

                </li>

                <li class="nav-item">
                    <a href="#" class="nav-link">
                    <i class="nav-icon far fa-circle"></i>
                    <p>
                        Simple Link
                        <span class="right badge badge-danger">New</span>
                    </p>
                    </a>
                </li>
                -->


                <!-- from here on come my custom menus -->

            <!--
                <li class="header nav-item text-white">
                <i class="nav-icon far fa-circle"></i>
                    Κυρίως Πλοήγηση
                </li>
                
            
                <li class="nav-item">
                    <a href="/home" class="nav-link">
                    <i class="nav-icon fas fa-globe"></i>
                    <p>
                        Αρχική Σελίδα
                        <span class="right badge badge-danger">New</span>
                    </p>
                    </a>
                </li>

           

              
                
                
                <li class="header nav-item text-white">
                <i class="nav-icon fa fa-file" aria-hidden="true"></i>
                    Γενικές Επιλογές
                </li>

                
                <li class="nav-item">
                    <a href="/stock/view" class="nav-link">
                    <i class="nav-icon far fa-eye"></i>                        
                        <p>
                            Διαθεσιμότητα Στοκ                        
                        </p>
                    </a>
                </li>
                

                
                <li class="nav-item">
                    <a href="/charge-toolkit" class="nav-link">
                    <i class="nav-icon fas fa-wrench"></i>
                    <p>
                        Χρέωση εργαλείων                        
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/create-invoice" class="nav-link">
                    <i class="nav-icon fas fa-plus"></i>
                    <p>
                        Δημιουργία τιμολογίου                        
                    </p>
                    </a>
                </li>

                <li class="header nav-item text-white">
                    <i class="nav-icon fas fa-briefcase"></i>
                    Διαχείριση Αναθέσεων
                </li>

                <li class="nav-item">
                    <a href="/assignments/view" class="nav-link">
                    <i class="nav-icon fas fa-eye"></i>
                    <p>
                        Δες Ανοιχτές Αναθέσεις                       
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/assignment/update" class="nav-link">
                    <i class="nav-icon far fa-file"></i>
                    <p>
                        Μεταβολή Ανάθεσης                       
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/assignment/delete" class="nav-link">
                    <i class="nav-icon fas fa-minus"></i>
                    <p>
                        Διαγραφή Ανάθεσης                       
                    </p>
                    </a>
                </li>


                <li class="header nav-item text-white"><i class="nav-icon fas fa-briefcase"></i>Δημιουργία Αναθέσεων</li>

                <li class="nav-item">
                    <a href="/assignment/import/create" class="nav-link">
                    <i class="nav-icon fas fa-arrow-right"></i>
                    <p>
                        Δημ.Νέας Ανάθ.Εισαγωγής                       
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/assignment/export/create" class="nav-link">
                    <i class="nav-icon fas fa-arrow-left"></i>
                    <p>
                        Δημ.Νέας Ανάθ.Εξαγωγής                       
                    </p>
                    </a>
                </li>

                <li class="header nav-item text-white">
                    <i class="nav-icon far fa-calendar"></i>
                    Διαχείριση Προϊόντων
                </li>

                <li class="nav-item">
                    <a href="/products/view" class="nav-link">
                    <i class="nav-icon fas fa-eye"></i>
                    <p>
                        Δες Όλα τα Προϊόντα                      
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/product/view" class="nav-link">
                    <i class="nav-icon far fa-eye"></i>
                    <p>
                        Δες Προϊόν                      
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/product/create" class="nav-link">
                    <i class="nav-icon fas fa-plus"></i>
                    <p>
                        Δημ. Νέου Προϊόντος                      
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/product/update" class="nav-link">
                    <i class="nav-icon far fa-circle"></i>
                    <p>
                        Μεταβολή Προϊόντος                      
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/product/delete" class="nav-link">
                    <i class="nav-icon fas fa-minus"></i>
                    <p>
                        Διαγραφή Προϊόντος                      
                    </p>
                    </a>
                </li>


                <li class="header nav-item text-white">
                    <i class="nav-icon far fa-address-book"></i>                    
                    Διαχείριση Χρηστών                                        
                </li>

                <li class="nav-item">
                    <a href="/profile/create" class="nav-link">
                    <i class="nav-icon fas fa-plus"></i>
                    <p>
                        Δημιουργία Νέου Χρήστη                     
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/profile/edit" class="nav-link">
                    <i class="nav-icon fas fa-user"></i>
                    <p>
                        Μεταβολή Χρήστη                     
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/profile/delete" class="nav-link">
                    <i class="nav-icon fas fa-minus"></i>
                    <p>
                        Διαγραφή Χρήστη                     
                    </p>
                    </a>
                </li>                

                <li class="nav-item">
                    <a href="/profile/view" class="nav-link">
                    <i class="nav-icon far fa-user"></i>
                    <p>
                        Δες Προφίλ Χρήστη                     
                    </p>
                    </a>
                </li>

                <li class="nav-item">
                    <a href="/profile/change-password" class="nav-link">
                    <i class="nav-icon fas fa-lock"></i>
                    <p>
                        Αλλαγή Κωδικού Χρήστη                     
                    </p>
                    </a>
                </li>
        -->


                



            </ul>  <!-- main ul for left sidebar menu -->          
        </nav>
    </div>    

</aside>
