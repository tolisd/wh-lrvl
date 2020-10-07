<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Import;
use App\Importassignment;
use App\Company;
use App\Transport; //transport_companies
use App\Employee;
use Carbon\Carbon;

class ImportController extends Controller
{
    //
    public function view_imports(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $importassignments = ImportAssignment::all();
            $imports = Import::all();
            $companies = Company::all();
            $transport_companies = Transport::all();
            $employees = Employee::all();

            return view('imports_view', ['importassignments' => $importassignments,
                                        'companies' => $companies,
                                        'employees' => $employees,
                                        'transport_companies' => $transport_companies,
                                        'imports' => $imports]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function create_import(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){


            //validation rules
            $validation_rules = [

            ];

            //custom error messages for the above validation rules
            $custom_messages = [

            ];

            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){

                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);

                }

                if($validator->passes()){
                    //success
                    //save the object

                    $import = new Import();

                    $import->save();

                    return \Response::json([
                        'success' => true,
                        //'errors' => $validator->getMessageBag()->toArray(),
                    ], 200);

                }
            }

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

    public function update_import(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

             //validation rules
             $validation_rules = [

            ];

            //custom error messages for the above validation rules
            $custom_messages = [

            ];

            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){

                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);

                }

                if($validator->passes()){
                    //success
                    //save-update the object
                    $import = Import::findOrFail($id);

                    $import->update($request->all());


                    return \Response::json([
                        'success' => true,
                        //'errors' => $validator->getMessageBag()->toArray(),
                    ], 200);

                }
            }


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

    public function delete_import(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $import = Import::findOrFail($id);
            $import->delete();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
