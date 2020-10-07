<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use Validator;
use App\Export;
use App\Exportassignment;
use App\Company;
use App\Transport; //transport_companies
use App\Employee;
use Carbon\Carbon;


class ExportController extends Controller
{
    //
    public function view_exports(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $exportassignments = ExportAssignment::all();
            $exports = Export::all();
            $companies = Company::all();
            $transport_companies = Transport::all();
            $employees = Employee::all();

            return view('exports_view', ['exportassignments' => $exportassignments,
                                        'companies' => $companies,
                                        'employees' => $employees,
                                        'transport_companies' => $transport_companies,
                                        'exports' => $exports]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_export(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            //validation rules
            $validation_rules = [
                'modal-input-exportassignment-create' => 'required',

            ];

            //custom error messages for the above validation rules
            $custom_messages = [
                'modal-input-exportassignment-create.required' => 'Το πεδίο Ανάθεση Εξαγωγής απαιτείται',

            ];

            //prepare the $validator variable for these validation rules
            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);


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
                    //save the object
                    $export = new Export();

                    $export->exportassignment_id = $request->input('modal-input-exportassignment-create');



                    $export->save();

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

    public function update_export(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){


            //validation rules
            $validation_rules = [

            ];

            //custom error messages for the above validation rules
            $custom_messages = [

            ];

            //prepare the $validator variable for these validation rules and custom error messages
            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

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
                    //save the object
                    $export = Export::findOrFail($id);

                    $export->update($request->all());

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

    public function delete_export(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $export = Export::findOrFail($id);
            $export->delete();

            if ($request->ajax()){
                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
