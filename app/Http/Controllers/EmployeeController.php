<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use Validator;
use App\Employee;
use App\Warehouse;
use App\Company;
use App\User;


class EmployeeController extends Controller
{
    //
    public function view_employees(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $employees = Employee::all();
            $warehouses = Warehouse::with('company')->get(); //eager loading, was ::all();
            $companies = Company::all();
            $users = User::all();

            return view('employees_view', ['employees' => $employees,
                                            'warehouses' => $warehouses,
                                            'companies' => $companies,
                                            'users' => $users]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    //Create a new employee. An employee is NOT necessarily a User of the system.
    //An employee belongs to a warehouse and to a company (to WHICH company THIS warehouse belongs to!)
    public function create_employee(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){


            //validation rules
            $rules = [
                'modal-input-name-create' => 'required|exists:users,id', //unique?, The field under validation must exist on a given database table.
                'modal-input-company-create' => 'required|exists:company,id', //The field under validation must exist on a given database table.
                'modal-input-address-create' => 'required',
                'modal-input-telno-create' => 'required',
                'modal-input-warehouse-create' => 'required'  //|exists:warehouse,id', //The field under validation must exist on a given database table.
            ];

            //custom validation error messages
            $custom_messages = [
                'modal-input-address-create.required' => '?? ?????????????????? ????????????????????',
                'modal-input-telno-create.required' => '?? ?????????????????????? ?????????????? ????????????????????',
                'modal-input-name-create.required' => '???? ?????????? ?????????????????????? ????????????????????',
                'modal-input-company-create.required' => '?? ???????????????? ????????????????????',
                'modal-input-warehouse-create.required' => '??/???? ??????????????/-???? ??????????????????????',
            ];

            //prepare the $validator variable
            $validator = Validator::make($request->all(), $rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){
                    //failure
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){
                    //success

                    DB::beginTransaction();

                    try{
                        //save the object in database
                        $employee = new Employee();

                        $employee->user_id          = $request->input('modal-input-name-create');
                        $employee->company_id       = $request->input('modal-input-company-create');
                        //$employee->employee_type  = $request->input('modal-input-role-create');
                        $employee->address          = $request->input('modal-input-address-create');
                        $employee->phone_number     = $request->input('modal-input-telno-create');
                        //$employee->email          = $request->input('modal-input-email-create');
                        // $employee->warehouse_id     = $request->input('modal-input-warehouse-create');

                        $employee->save();

                        $employee->warehouses()->sync($request->input('modal-input-warehouse-create'));

                        //establish the association between the 2 entities. very important!

                        // $user = User::findOrFail($employee->user_id);
                        // $user->employee()->save($employee);  //store the object

                        DB::commit();

                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);

                    } catch (\Exception $e) {

                        DB::rollBack();

                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);
                    }


                    // return \Response::json([
                    //     'success' => true,
                    //     'errors' => $validator->getMessageBag()->toArray(),
                    // ], 200);
                }
            }


            //From Laravel 7.x Docs---:
            //When updating a belongsTo relationship, you may use the associate method.
            //This method will set the foreign key on the child model:

            //$account = App\Account::find(10);

            /*
            $user = new User(); //insert this line instead
            $user->save();

            $user->employee()->associate($employee); //associate the 2 objects with each other
            $user->save(); //store the object
            */

            //  $user->employee()->associate($employee);
            //  $user->save();

            //$user = User::find(1);  //to the user that is associated with the employee
            //$user->employee->save($employee);
            //dd($user);

            /*
            if ($request->ajax()){
                return \Response::json();
            }

            return back();
            */

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    public function update_employee(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){


            //validation rules
            $rules = [
                'modal-input-name-edit' => 'required|exists:users,id', //The field under validation must exist on a given database table.
                'modal-input-company-edit' => 'required|exists:company,id', //The field under validation must exist on a given database table.
                'modal-input-address-edit' => 'required',
                'modal-input-telno-edit' => 'required',
                'modal-input-warehouse-edit' => 'required', //|exists:warehouse,id', //The field under validation must exist on a given database table.
            ];

            //custom validation error messages
            $custom_messages = [
                'modal-input-address-edit.required' => '?? ?????????????????? ????????????????????',
                'modal-input-telno-edit.required' => '?? ?????????????????????? ?????????????? ????????????????????',
                'modal-input-name-edit.required' => '???? ?????????? ?????????????????????? ????????????????????',
                'modal-input-company-edit.required' => '?? ???????????????? ????????????????????',
                'modal-input-warehouse-edit.required' => '??/???? ??????????????/-???? ??????????????????????',
            ];

            //prepare the $validator variable
            $validator = Validator::make($request->all(), $rules, $custom_messages);


            if($request->ajax()){

                if($validator->fails()){
                    //failure
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){
                    //success

                    DB::beginTransaction();

                    try{
                        //update & save the object in database

                        $employee = Employee::findOrFail($id);

                        $employee->user_id         = $request->input('modal-input-name-edit');
                        $employee->company_id      = $request->input('modal-input-company-edit');
                        //$employee->employee_type  = $request->input('modal-input-role-edit');
                        $employee->address          = $request->input('modal-input-address-edit');
                        $employee->phone_number     = $request->input('modal-input-telno-edit');
                        //$employee->email            = $request->input('modal-input-email-edit');
                        // $employee->warehouse_id     = $request->input('modal-input-warehouse-edit');
                        /*
                        $path = $request->file("modal-input-photo-edit")->store("images/");  //stored in storage/images/
                        $url = Storage::url($path);
                        $employee->photo_url = $url;
                        */
                        $employee->update($request->all()); //or $request->only(['', '', ...]) ??

                        $employee->warehouses()->sync($request->input('modal-input-warehouse-edit'));

                        //establish the association between the 2 entities. very important!

                        //$user = User::findOrFail($employee->user_id);
                        //$user->employee()->save($employee);  //store the object
                        DB::commit();

                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);

                    } catch (\Exception $e) {

                        DB::rollBack();

                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);
                    }


                    // return \Response::json([
                    //     'success' => true,
                    //     'errors' => $validator->getMessageBag()->toArray(),
                    // ], 200);
                }
            }


            // $user->employee()->associate($employee);
            // $user->save();

            /*
            if ($request->ajax()){
                return \Response::json();
            }

            return back();
            */
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_employee(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            if ($request->ajax()){

                // $employee = Employee::findOrFail($id);
                // // Should check if employee/user is logged out (or not logged in) first?
                // // or BETTER, first should \Auth::logout() user, THEN AFTER delete him from the DB!
                // // I DID the above in UserController.php, first user is logged out and afterwards he/she is deleted from the DB
                // // VERY IMPORTANT!
                // // Before deleting an Employee, check if he/she is involved in other activities in the program!!!
                // $employee->delete();


                $employee_has_charged_tools = DB::table('employees')
                ->where('employees.id', '=', $id)
                ->whereExists(function($query){
                // ->whereNotExists(function($query){
                    //$query->select(DB::raw(1))
                        $query->select('tools.employee_id')
                            ->from('tools')
                            ->whereRaw('tools.employee_id = employees.id');
                })->get();


                $employee_has_import_assignments_info = DB::table('employees')
                                                ->where('employees.id', '=', $id)
                                                ->whereExists(function($query){
                                                // ->whereNotExists(function($query){
                                                    //$query->select(DB::raw(1))
                                                    $query->select('imports.employee_id')
                                                            ->from('imports')
                                                            ->whereRaw('imports.employee_id = employees.id');
                                                })->get();


                // $employee_belongsto_warehouse = DB::table('employees')
                //                         ->where('employees.id', '=', $id)
                //                         // ->whereNotExists(function($query){
                //                         ->whereExists(function($query){
                //                             //$query->select(DB::raw(1))
                //                             $query->select('employee_warehouse.employee_id')
                //                                     ->from('employee_warehouse')
                //                                     ->whereRaw('employee_warehouse.employee_id = employees.id');
                //                         })->get();


                if(
                    ($employee_has_charged_tools->isEmpty()) &&
                    ($employee_has_import_assignments_info->isEmpty())
                    // ($employee_belongsto_warehouse->isEmpty())
                ){
                        //employee is NOT involved in other tables via its FK, so just GO AHeAD AND DELETE him!!!
                        // dd($employee_has_charged_tools);
                        // dd('all seems ok for employee deletion!');

                        //$employee->warehouses->delete(); //delete from the employee_warehouse table (FK), no it doesnt work!
                                                            //must set onDelete(cascade) in employee-wrehouse

                }

                $employee = Employee::findOrFail($id);
                $employee->delete();



                return \Response::json();
            }

            //  return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
