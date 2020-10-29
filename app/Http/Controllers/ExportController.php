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
                'modal-input-recipient-create' => 'required',
                'modal-input-expco-create' => 'required',
                'modal-input-dtdeliv-create' => 'required',
                'modal-input-vehicleregno-create' => 'required',
                'modal-input-shipco-create' => 'required',
                'modal-input-sendplace-create' => 'required',
                'modal-input-destin-create' => 'required',
                'modal-input-chargehrs-create' => 'required',
                'modal-input-hours-create' => 'required',
                'modal-input-bulletin-create' => 'required|mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
                'modal-input-dtitle-create' => 'required',
                'modal-input-exportassignment-create' => 'required',
                'modal-input-products-create' => 'required',
            ];

            //custom error messages for the above validation rules
            $custom_messages = [
                'modal-input-recipient-create.required' => 'Ο Υπεύθυνος Παράδοσης απαιτείται',
                'modal-input-expco-create.required' => 'Η Εταιρεία Ααράδοσης απαιτείται',
                'modal-input-dtdeliv-create.required' => 'Η Ημ/νία & Ώρα Παράδοσης απαιτείται',
                'modal-input-vehicleregno-create.required' => 'Ο Αρ.Κυκλ. Μεταφορικού Μέσου απαιτείται',
                'modal-input-shipco-create.required' => 'Η Μεταφορική Εταιρεία απαιτείται',
                'modal-input-sendplace-create.required' => 'Ο Τόπος Αποστολής απαιτείται',
                'modal-input-destin-create.required' => 'Ο Τόπος Προορισμού απαιτείται',
                'modal-input-chargehrs-create.required' => 'Οι χρεώσιμες εργάσιμες ώρες απαιτούνται',
                'modal-input-hours-create.required' => 'Οι εργάσιμες ώρες απαιτούνται',
                'modal-input-bulletin-create.required' => 'Το Δελτίο Αποστολής απαιτείται',
                'modal-input-bulletin-create.mimetypes' => 'Τύποι αρχείων για το Δελτίο Αποστολής: pdf, txt, doc, docx.',
                'modal-input-dtitle-create.required' => 'Ο Διακριτός Τίτλος Παράδοσης απαιτείται',
                'modal-input-exportassignment-create.required' => 'Η Ανάθεση Εξαγωγής απαιτείται',
                'modal-input-products-create.required' => 'Τα Προϊόντα απαιτούνται',
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

                    $export->employee_id             = $request->input('modal-input-recipient-create');
                    $export->company_id              = $request->input('modal-input-expco-create');
                    $export->delivered_on            = Carbon::createFromFormat('d-m-Y H:i', $request->input('modal-input-dtdeliv-create'));
                    $export->vehicle_reg_no          = $request->input('modal-input-vehicleregno-create');
                    $export->transport_id            = $request->input('modal-input-shipco-create');
                    $export->destination_address     = $request->input('modal-input-destin-create');
                    $export->shipment_address        = $request->input('modal-input-sendplace-create');
                    $export->chargeable_hours_worked = $request->input('modal-input-chargehrs-create');
                    $export->hours_worked            = $request->input('modal-input-hours-create');
                    $export->item_description        = $request->input('modal-input-dtitle-create');
                    $export->exportassignment_id     = $request->input('modal-input-exportassignment-create');

                    if($request->hasFile('modal-input-bulletin-create')){
                        $file = $request->file('modal-input-bulletin-create');

                        $datetime_now = date_create();
                        $datetime = date_format($datetime_now, 'YmdHis');
                        $name = $datetime . '-' . $file->getClientOriginalName();
                        $path = $file->storeAs('arxeia/exagwgis', $name);
                        $url  = \Storage::url($path);

                        $export->shipment_bulletin = $url;
                    }

                    $export->save();

                    //also, update the pivot table, ie save the relation in the pivot table!
                    $export->products()->sync($request->input('modal-input-products-create'));


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
                'modal-input-recipient-edit' => 'required',
                'modal-input-expco-edit' => 'required',
                'modal-input-dtdeliv-edit' => 'required',
                'modal-input-vehicleregno-edit' => 'required',
                'modal-input-shipco-edit' => 'required',
                'modal-input-sendplace-edit' => 'required',
                'modal-input-destin-edit' => 'required',
                'modal-input-chargehrs-edit' => 'required',
                'modal-input-hours-edit' => 'required',
                'modal-input-bulletin-edit' => 'required|mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
                'modal-input-dtitle-edit' => 'required',
                'modal-input-exportassignment-edit' => 'required',
                'modal-input-products-edit' => 'required',
            ];

            //custom error messages for the above validation rules
            $custom_messages = [
                'modal-input-recipient-edit.required' => 'Ο Υπεύθυνος Παράδοσης απαιτείται',
                'modal-input-expco-edit.required' => 'Η Εταιρεία Παράδοσης απαιτείται',
                'modal-input-dtdeliv-edit.required' => 'Η Ημ/νία & Ώρα Παράδοσης απαιτείται',
                'modal-input-vehicleregno-edit.required' => 'Ο Αρ.Κυκλ. Μεταφορικού Μέσου απαιτείται',
                'modal-input-shipco-edit.required' => 'Η Μεταφορική Εταιρεία απαιτείται',
                'modal-input-sendplace-edit.required' => 'Ο Τόπος Αποστολής απαιτείται',
                'modal-input-destin-edit.required' => 'Ο Τόπος Προορισμού απαιτείται',
                'modal-input-chargehrs-edit.required' => 'Οι χρεώσιμες εργάσιμες ώρες απαιτούνται',
                'modal-input-hours-edit.required' => 'Οι εργάσιμες ώρες απαιτούνται',
                'modal-input-bulletin-edit.required' => 'Το Δελτίο Αποστολής απαιτείται',
                'modal-input-bulletin-edit.mimetypes' => 'Τύποι αρχείων για το Δελτίο Αποστολής: pdf, txt, doc, docx.',
                'modal-input-dtitle-edit.required' => 'Ο Διακριτός Τίτλος Παράδοσης απαιτείται',
                'modal-input-exportassignment-edit.required' => 'Η Ανάθεση Εξαγωγής απαιτείται',
                'modal-input-products-edit.required' => 'Τα Προϊόντα απαιτούνται',
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

                    $export->employee_id             = $request->input('modal-input-recipient-edit');
                    $export->company_id              = $request->input('modal-input-expco-edit');
                    $export->delivered_on            = Carbon::createFromFormat('d-m-Y H:i', $request->input('modal-input-dtdeliv-edit'));
                    $export->vehicle_reg_no          = $request->input('modal-input-vehicleregno-edit');
                    $export->transport_id            = $request->input('modal-input-shipco-edit');
                    $export->destination_address     = $request->input('modal-input-destin-edit');
                    $export->shipment_address        = $request->input('modal-input-sendplace-edit');
                    $export->chargeable_hours_worked = $request->input('modal-input-chargehrs-edit');
                    $export->hours_worked            = $request->input('modal-input-hours-edit');
                    $export->item_description        = $request->input('modal-input-dtitle-edit');
                    $export->exportassignment_id     = $request->input('modal-input-exportassignment-edit');

                    if($request->hasFile('modal-input-bulletin-edit')){
                        $file = $request->file('modal-input-bulletin-edit');

                        $datetime_now = date_create();
                        $datetime = date_format($datetime_now, 'YmdHis');
                        $name = $datetime . '-' . $file->getClientOriginalName();
                        $path = $file->storeAs('arxeia/exagwgis', $name);
                        $url  = \Storage::url($path);

                        $export->shipment_bulletin = $url;
                    }

                    $export->update($request->all());

                    //also, update the pivot table, ie save the relation in the pivot table!
                    $export->products()->sync($request->input('modal-input-products-edit'));

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
