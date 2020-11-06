<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; //added for DB retrieval
use Auth; //added for Auth
use App\User;
use App\Product;

use App\Assignment;
use App\ImportAssignment;
use App\ExportAssignment;

use App\Tool;
use App\Warehouse;
use App\Employee;



class DashboardController extends Controller
{
    //Collect all the common routes in here as far as the Dashboard is concerned

    //VIEW the MAIN dashboard screen!
    public function index(){
        //who CAN view the (initial screen of) the Dashboard, (remove or add the types here)
        //in this case, each and every user_type can view the Dashboard (but NOT all menus)

        /*
        $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman', 'warehouse_worker', 'normal_user']);

        //---use this?
        // $this->authorize('see-dashboard', $user);

        //---or this? it seems to do the job!
        if ($authenticatedUser){

            $usersCount = User::count();
            $productsCount = Product::count(); //added: 'use App\Product;'
            //ToDo: ass the assignments count here as well!
            $assignmentsCount = Assignment::count();

            //pass the value of $usersCount & $productsCount into the view
            return view('dashboard', ['usersCount' => $usersCount,
                                      'prodCount'  => $productsCount,
                                      'assignCount' => $assignmentsCount ]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
        */

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker', 'isNormalUser', 'isTechnician'])){

            $usersCount = User::count();
            $productsCount = Product::count(); //added: 'use App\Product;'

            //$assignmentsCount = Assignment::count(); //I do NOT use this table anymore..
            //...instead, I use the 2 following tables
            $import_assignments_count = ImportAssignment::where('is_open', '=', 1)->count();
            $export_assignments_count = ExportAssignment::where('is_open', '=', 1)->count();

            $tools_count = Tool::count();

            //warehouses & products ([N-to-M] 1 warehouse has many products. A product can belong to more than one warehouses )
            //$warehouses = Warehouse::with('product')->get();
            $warehouses = Warehouse::with('employees')->get(); //was ::all();
            $employees = Employee::all(); //with('warehouses')->get();
            $users = User::all();
            /*
            $employees = Employee::all();
            $users = User::all();
            */
            //$products = Product::all();
           // $products_count =  //No..! as there will be too many products in the DB!


            //I want the name of the (1) Proistamenos of THIS (by name) Apothiki.
            /*
            $warehouse_ids = Warehouse::all()->pluck('id'); //get the id's of the available warehouses, as en eloquent collection
            $user_ids = User::where('user_type', 'warehouse_foreman')->pluck('id')->get(); //eloquent collection, all the IDs of proistamenoi
            $pr_ap = Employee::();

            $proistamenoi_apothikwn = [];
            foreach($warehouse_ids as $wh_id){
                $proistamenoi_apothikwn = Employee::where('warehouse_id', $wh_id)->select('name');
            }

            $warehouse_id = Warehouse::where('id', '')->find('', '')->pluck('id')->first();  //get the id of this warehouse
            $proistamenos = Employee::where('warehouse_id', $warehouse_id)->find('id', $user_id)->first(); //get the row of the employee for this warehouse
            */



            // $foreman_employees = DB::table('employees')
            //                     ->join('employee_warehouse', 'employee_warehouse.employee_id','=','employees.id')
            //                     ->join('users','users.id','=','employees.user_id')
            //                     ->where('users.user_type', 'warehouse_foreman')
            //                     ->get();

            // $employees_warehouse = Employee::with('warehouses')->get();

            $worker_employees = DB::table('employees')
                                ->join('employee_warehouse', 'employee_warehouse.employee_id','=','employees.id')
                                ->join('users','users.id','=','employees.user_id')

                                ->join('warehouse','warehouse.id','=','employee_warehouse.warehouse_id')

                                ->where('users.user_type', 'warehouse_worker')
                                ->get();



            $u_id = Auth::user()->id;
            // $w_id = \Request::get('warehouse_id');

            //the following is 3 queries, 1 more query than the previous solution, but it looks much shorter and cleaner!
            // $user             = User::findOrFail($u_id);
            // $employee         = Employee::where('user_id', $user->id)->first();   //via its FK
            // $my_warehouses    = Tool::where('employee_id', $employee->id)->get(); //via its FK

            // $user_roles = ['super_admin','company_ceo','warehouse_foreman']; //only these types can view warehouse infoboxes

            $my_warehouses = DB::table('users')
                            ->join('employees', 'employees.user_id', '=', 'users.id')
                            //->join('warehouse', 'warehouse.id', '=', 'employees.warehouse_id')
                            ->join('employee_warehouse', 'employees.id', '=', 'employee_warehouse.employee_id')
                            ->join('warehouse', 'employee_warehouse.warehouse_id', '=', 'warehouse.id')
                            //->where('users.user_type', 'warehouse_foreman')
                            ->where('users.id', $u_id)
                            //->select('employee_warehouse.warehouse_id',)
                            ->get();  //CORRECT!! only thelogged in foreman can see HIS OWN warehouses!

            //dd($my_warehouses); //correct! It brings up only his own warehouses..!



            // dd($my_warehouses);

            // $no_of_products_in_this_warehouse = DB::table('product_warehouse')
            //                                     ->join('products', 'products.id', '=', 'product_warehouse.product_id')
            //                                     //->join('warehouse', 'warehouse.id', '=', 'product_warehouse.warehouse_id')
            //                                     ->join('employees', 'employees.user_id', '=', 'users.id')
            //                                     ->join('users', 'users.id', '=', 'employees.user_id')
            //                                     ->join('employee_warehouse', 'employee_warehouse.warehouse_id','=','product_warehouse.warehouse_id')
            //                                     ->where('users.user_type', 'warehouse_foreman')
            //                                     ->where('users.id', $u_id)
            //                                     ->count();


            $products_in_warehouses = DB::table('employee_warehouse')
                                    ->join('employees','employees.id','=','employee_warehouse.employee_id')
                                    ->join('users','users.id','=','employees.user_id')
                                    ->join('product_warehouse', 'product_warehouse.warehouse_id','=','employee_warehouse.warehouse_id')
                                    ->where('users.user_type', 'warehouse_foreman')
                                    ->where('users.id', $u_id)
                                    ->count();

            //dd([$my_warehouses, $products_in_warehouses]); //array of products in warehouses



            // $no_of_products_in_this_warehouse = DB::table('product_warehouse')
            //                                     ->join('products', 'products.id', '=', 'product_warehouse.product_id')
            //                                     ->join('warehouse', 'warehouse.id', '=', 'product_warehouse.warehouse_id')
            //                                     ->join('employees', 'employees.warehouse_id', '=', 'warehouse.id')
            //                                     ->join('users', 'users.id', '=', 'employees.user_id')
            //                                     //->where('product_warehouse.warehouse_id', $w_id)
            //                                     //->whereIn('product_warehouse.warehouse_id', $my_warehouses->id)
            //                                     ->where('users.user_type', 'warehouse_foreman')
            //                                     ->where('users.id', $u_id)
            //                                     ->count();

            // dd($no_of_products_in_this_warehouse);




            return view('dashboard', [ 'usersCount' => $usersCount,
                                        'prodCount'  => $productsCount,
                                        //'assignCount' => $assignmentsCount,
                                        'import_assignments_count' => $import_assignments_count,
                                        'export_assignments_count' => $export_assignments_count,
                                        'tools_count' => $tools_count,
                                        'warehouses' => $warehouses,
                                        'my_warehouses' => $my_warehouses,
                                        'products_in_warehouses' => $products_in_warehouses,
                                        'employees' => $employees,
                                        //'employees_warehouse' => $employees_warehouse,
                                        // 'foreman_employees' => $foreman_employees,
                                        'worker_employees' => $worker_employees,
                                        'users' => $users,
                                        //'products' => $products,
                                        ]);
        }
        else {
            return abort(403, 'Sorry you cannot view this home webpage');
        }
    }


    public function view_stock(){
        //3 user types -> Admin, CEO, Foreman
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman']);

        if ($authenticatedUser){
            return view('view_stock');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function view_products(){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){
            return view('products_view');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function view_product(){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){
            return view('product_view');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_product(){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){
            return view('create_product');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function update_product(){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){
            return view('update_product');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function delete_product(){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){
            return view('delete_product');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }



    //---insert more methods (most of the route methods that is) in here

    public function charge_toolkit(){
         //3 user types -> Admin, CEO, Foreman
         $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman']);

         if ($authenticatedUser){
             return view('charge_toolkit');
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }
    }

    public function create_invoice(){
        //3 user types -> Admin, CEO, Accountant
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant']);

        if ($authenticatedUser){
            return view('create_invoice');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
   }


    public function view_assignments(){
        //4 user types -> Admin, CEO, Accountant, Foreman
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){
            return view('assignments_view');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


    public function create_import_assignment(){
        //4 user types -> Admin, CEO, Accountant, Foreman
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){
            return view('create_import_assignment');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


    public function create_export_assignment(){
         //4 user types -> Admin, CEO, Accountant, Foreman
         $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

         if ($authenticatedUser){
             return view('create_export_assignment');
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }
    }

    public function update_assignment(){
        //4 user types -> Admin, CEO, Accountant, Foreman
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){
            return view('update_assignment');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


   public function delete_assignment(){
        //4 user types -> Admin, CEO, Accountant, Foreman
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant', 'warehouse_foreman']);

        if ($authenticatedUser){
            return view('delete_assignment');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
   }


   public function create_user(){
        //2 user types -> Admin, CEO.
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo']);

        if ($authenticatedUser){





            return redirect()->back();
            //return view('user_create');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
   }


   public function update_user(Request $request, $uid){

   }


   public function delete_user(Request $request, $uid){


   }

   public function view_user(){
        //2 user types -> Admin, CEO.
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo']);

        if ($authenticatedUser){
            return view('user_view');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function view_users(){
        //2 user types -> Admin, CEO.
        $authenticatedUser = Auth::user()->user_type(['super_admin', 'company_ceo']);

        if ($authenticatedUser){

            $users = DB::table('users')->get(); //get all users from database via Facade

            return view('users_view', ['users' => $users]);     //pass in the view, the $users var.

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }




    }

    public function change_user_password(){
        //2 user types -> Admin, CEO.
        $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo']);


        if ($authenticatedUser){
            return view('user_change_password');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }






    public function view_dashboard(User $user){
        $user = Auth::user()->user_type(['super_admin', 'company_ceo', 'accountant']); //get authorised user via method in User Model

        $this->authorize('see-dashboard', $user);

        return view('dashboard');

        /*
        if (Gate::forUser($user)->allows('view-assignments', $assignment)){
            //the currently authorized user CAN view assignments

        }
        */

        /*
        return ($user->user_type == 'super_admin')
            || ($user->user_type == 'company_ceo')
            || ($user->user_type == 'accountant');
        */
    }
}
