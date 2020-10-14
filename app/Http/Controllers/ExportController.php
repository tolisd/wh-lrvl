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
use App\Product;
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
            $products = Product::all(); //what if there a lot of products in the DB? chunk()? => use Query Builder instead..

            return view('exports_view', ['exportassignments' => $exportassignments,
                                        'companies' => $companies,
                                        'employees' => $employees,
                                        'transport_companies' => $transport_companies,
                                        'exports' => $exports,
                                        'products' => $products]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_export(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            //validation rules
            $validation_rules = [
                'modal-input-exportassignment-create' => 'required',
                'modal-input-recipient-create' => 'required',
                'modal-input-expco-create' => 'required',
                'modal-input-dtdeliv-create' => 'required',
                'modal-input-vehicleregno-create' => 'required',
                'modal-input-shipco-create' => 'required',
                'modal-input-sendplace-create' => 'required',
                'modal-input-destin-create' => 'required',
                'modal-input-chargehrs-create' => 'required',
                'modal-input-hours-create' => 'required',
                'modal-input-bulletin-create' => 'required',
                'modal-input-dtitle-create' => 'required',
                'modal-input-products-create' => 'required',
            ];

            //custom error messages for the above validation rules
            $custom_messages = [
                'modal-input-exportassignment-create.required' => 'Το πεδίο ανάθεση εξαγωγής απαιτείται',
                'modal-input-recipient-create.required' => 'Ο υπεύθυνος παράδοσης απαιτείται',
                'modal-input-expco-create.required' => 'Η εταιρεία παράδοσης απαιτείται',
                'modal-input-dtdeliv-create.required' => 'Η ημ/νία & ώρα παράδοσης απαιτείται',
                'modal-input-vehicleregno-create.required' => 'Ο αριθμός κυκλοφορίας μεταφορικού μέσου απαιτείται',
                'modal-input-shipco-create.required' => 'Η μεταφορική εταιρεία απαιτείται',
                'modal-input-sendplace-create.required' => 'Ο τόπος αποστολής απαιτείται',
                'modal-input-destin-create.required' => 'Ο προορισμός απαιτείται',
                'modal-input-chargehrs-create.required' => 'Οι χρεώσιμες εργάσιμες ώρες απαιτούνται',
                'modal-input-hours-create.required' => 'Οι εργάσιμες ώρες απαιτούνται',
                'modal-input-bulletin-create.required' => 'Το δελτίο αποστολής απαιτείται',
                'modal-input-dtitle-create.required' => 'Ο διακριτός τίτλος παράδοσης απαιτείται',
                'modal-input-products-create.required' => 'Τα προϊόντα απαιτούνται',
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
                'modal-input-exportassignment-edit' => 'required',
                'modal-input-recipient-edit' => 'required',
                'modal-input-expco-edit' => 'required',
                'modal-input-dtdeliv-edit' => 'required',
                'modal-input-vehicleregno-edit' => 'required',
                'modal-input-shipco-edit' => 'required',
                'modal-input-sendplace-edit' => 'required',
                'modal-input-destin-edit' => 'required',
                'modal-input-chargehrs-edit' => 'required',
                'modal-input-hours-edit' => 'required',
                'modal-input-bulletin-edit' => 'required',
                'modal-input-dtitle-edit' => 'required',
                'modal-input-products-edit' => 'required',
            ];

            //custom error messages for the above validation rules
            $custom_messages = [
                'modal-input-exportassignment-edit.required' => 'Το πεδίο ανάθεση εξαγωγής απαιτείται',
                'modal-input-recipient-edit.required' => 'Ο υπεύθυνος παράδοσης απαιτείται',
                'modal-input-expco-edit.required' => 'Η εταιρεία παράδοσης απαιτείται',
                'modal-input-dtdeliv-edit.required' => 'Η ημ/νία & ώρα παράδοσης απαιτείται',
                'modal-input-vehicleregno-edit.required' => 'Ο αριθμός κυκλοφορίας μεταφορικού μέσου απαιτείται',
                'modal-input-shipco-edit.required' => 'Η μεταφορική εταιρεία απαιτείται',
                'modal-input-sendplace-edit.required' => 'Ο τόπος αποστολής απαιτείται',
                'modal-input-destin-edit.required' => 'Ο προορισμός απαιτείται',
                'modal-input-chargehrs-edit.required' => 'Οι χρεώσιμες εργάσιμες ώρες απαιτούνται',
                'modal-input-hours-edit.required' => 'Οι εργάσιμες ώρες απαιτούνται',
                'modal-input-bulletin-edit.required' => 'Το δελτίο αποστολής απαιτείται',
                'modal-input-dtitle-edit.required' => 'Ο διακριτός τίτλος παράδοσης απαιτείται',
                'modal-input-products-edit.required' => 'Τα προϊόντα απαιτούνται',
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
