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
use App\Warehouse;
use Carbon\Carbon;


class ExportController extends Controller
{
    //
    public function view_exports(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman','isAccountant', 'isWarehouseWorker'])){

            $exportassignments = ExportAssignment::where('is_open', '=', 1)->get(); // was: ExportAssignment::all();, but I only want the currently OPEN ones
            $exports = Export::all();
            $companies = Company::all();
            $transport_companies = Transport::all();
            $employees = Employee::all();
            $warehouses = Warehouse::all();
            $products = Product::all(); //what if there a lot of products in the DB? chunk()? => use Query Builder instead..
            $prod_wh = Product::with('warehouses', 'exports')->get(); //no need for exports or warehouses for that matter


            $expassids = [];
            foreach($exports as $export){
                array_push($expassids, $export->exportassignment_id);
            }

            $employees_per_warehouse = DB::table('employees')
                                        //->join('exports', 'employees.id', '=', 'exports.employee_id')
                                        ->join('employee_warehouse', 'employees.id','=','employee_warehouse.employee_id')
                                        ->join('exportassignments', 'exportassignments.warehouse_id', '=', 'employee_warehouse.warehouse_id')
                                        ->join('users', 'users.id', '=', 'employees.user_id')
                                        ->where('users.user_type', 'warehouse_worker')
                                        ->whereIn('exportassignments.id', $expassids)
                                        ->select('users.name', 'employees.id', 'employee_warehouse.warehouse_id')
                                        ->get();

            /*
            $wh_ids = []; //the id's of the warehouses for which there have been created export assignments!!
            foreach($exportassignments as $exportassignment){
                foreach($warehouses as $wh){
                    if($wh->id == $exportassignment->warehouse_id){
                        array_push($wh_ids, $wh->id);
                    }
                }
            }
            //dd($wh_ids);

            //the products of the above mentioned warehouses!
            $all_products_in_warehouse = Product::whereHas('warehouses', function($query) use($wh_ids){
                $query->whereIn('warehouse_id', $wh_ids);
            })->get();
            */

            $products_in_warehouse = DB::table('product_warehouse')
                                     ->join('products', 'products.id', '=', 'product_warehouse.product_id')
                                     ->select('product_warehouse.product_id', 'product_warehouse.warehouse_id',  'products.name')
                                     ->get();


            //$all_products_in_warehouse = Product::with('warehouses')->get(); //this is correct but returns ALL products from ALL warehouses...
            //dd($all_products_in_warehouse);

            return view('exports_view', ['exportassignments' => $exportassignments,
                                        'companies' => $companies,
                                        'employees' => $employees,
                                        'employees_per_warehouse' => $employees_per_warehouse,
                                        'products_in_warehouse' => $products_in_warehouse,
                                        'transport_companies' => $transport_companies,
                                        'exports' => $exports,
                                        'products' => $products,
                                        'prod_wh' => $prod_wh,
                                        'warehouses' => $warehouses]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_export(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isAccountant', 'isWarehouseWorker'])){

            //validation rules
            $validation_rules = [
                'modal-input-recipient-create' => 'required',
                'modal-input-expco-create' => 'required',
                'modal-input-dtdeliv-create' => 'required',
                'modal-input-vehicleregno-create' => 'required',
                'modal-input-shipco-create' => 'required',
                'modal-input-sendplace-create' => 'required',
                'modal-input-destin-create' => 'required',
                'modal-input-chargehrs-create' => 'required|numeric|gt:modal-input-hours-create',
                'modal-input-hours-create' => 'required|numeric',
                // 'modal-input-bulletin-create' => 'required|mimes:txt,pdf,zip,doc,docx', //mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
                'modal-input-dtitle-create' => 'required',
                'modal-input-exportassignment-create' => 'required',
                'modal-input-prod-create.*' => 'required',
                'modal-input-prodqty-create.*' => 'required|numeric|gt:0',
            ];

            //custom error messages for the above validation rules
            $custom_messages = [
                'modal-input-recipient-create.required' => 'Ο Υπεύθυνος Παράδοσης απαιτείται',
                'modal-input-expco-create.required' => 'Η Εταιρεία Παράδοσης απαιτείται',
                'modal-input-dtdeliv-create.required' => 'Η Ημ/νία & Ώρα Παράδοσης απαιτείται',
                'modal-input-vehicleregno-create.required' => 'Ο Αρ.Κυκλ. Μεταφορικού Μέσου απαιτείται',
                'modal-input-shipco-create.required' => 'Η Μεταφορική Εταιρεία απαιτείται',
                'modal-input-sendplace-create.required' => 'Ο Τόπος Αποστολής απαιτείται',
                'modal-input-destin-create.required' => 'Ο Τόπος Προορισμού απαιτείται',
                'modal-input-chargehrs-create.required' => 'Οι χρεώσιμες εργάσιμες ώρες απαιτούνται',
                'modal-input-chargehrs-create.numeric' => 'Οι χρεώσιμες εργάσιμες πρέπει να είναι αριθμός',
                'modal-input-chargehrs-create.gt' => 'Οι ΧρΩΕ πρέπει να είναι περισσότερες από τις ΩΕ',
                'modal-input-hours-create.required' => 'Οι εργάσιμες ώρες απαιτούνται',
                'modal-input-hours-create.numeric' => 'Οι εργάσιμες ώρες πρέπει να είναι αριθμός',
                // 'modal-input-bulletin-create.required' => 'Το Δελτίο Αποστολής απαιτείται',
                // 'modal-input-bulletin-create.mimes' => 'Τύποι αρχείων για το Δελτίο Αποστολής: pdf, txt, doc, docx',
                'modal-input-dtitle-create.required' => 'Ο Διακριτός Τίτλος Παράδοσης απαιτείται',
                'modal-input-exportassignment-create.required' => 'Η Ανάθεση Εξαγωγής απαιτείται',
                'modal-input-prod-create.*.required' => 'Τα Προϊόντα απαιτούνται',
                'modal-input-prodqty-create.*.required' => 'Η Ποσότητα απαιτείται',
                'modal-input-prodqty-create.*.numeric' => 'Η Ποσότητα πρέπει να είναι αριθμός',
                'modal-input-prodqty-create.*.gt' => 'Εισάγατε μηδενική ή αρνητική Ποσότητα',
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

                    // dd($request->input('modal-input-products-create'));

                    DB::beginTransaction();

                    try{
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

                        // if($request->hasFile('modal-input-bulletin-create')){
                        //     $file = $request->file('modal-input-bulletin-create');

                        //     $datetime_now = date_create();
                        //     $datetime = date_format($datetime_now, 'YmdHis');
                        //     $name = $datetime . '-' . $file->getClientOriginalName();
                        //     $path = $file->storeAs('arxeia/exagwgis', $name);
                        //     $url  = \Storage::url($path);

                        //     $export->shipment_bulletin = $url;
                        // }
                        $export->shipment_bulletin = null; //I do not need it for the export-information

                        $export->save();


                        //also, update the pivot table, ie save the relation in the pivot table!
                        // usually it is array of id's passed into the relationship

                        // $export->products()->sync($request->input('modal-input-products-create'));

                        //take the warehouse_id from the hidden input
                        // $w_id = $request->input('modal-input-warehouse-create');

                        $prd_arr = $request->input('modal-input-prod-create');    //array of product id's
                        $qty_arr = $request->input('modal-input-prodqty-create'); //array of quantity id's

                        // $export->products()->sync($prd_arr); //the products.. this is OK,
                                                             //but where is their quantity? it should go into the 'product_warehouse' table.

                        $extra = array_map(function($qty){
                            return ['quantity' => $qty];
                        }, $qty_arr);

                        $data = array_combine($prd_arr, $extra);
                        // dd($data);

                        $export->products()->sync($data); //OK data is PASSED into export_product table CORRECTLY!





                        // find the warehouse id from the ExportAssignment!
                        $wh_id = DB::table('exportassignments')
                                ->where('id', $request->input('modal-input-exportassignment-create'))
                                ->pluck('warehouse_id')->toArray();

                        foreach($wh_id as $key=>$value){
                            $warehouse_id = $value;
                        } //returns only the 1 value.

                        $warehouse = Warehouse::find($warehouse_id);

                        $old_quantities = [];

                        foreach($prd_arr as $prd){

                            //get the old values for quantities (the already stored values in the DB), for THIS warehouse_id
                            $old_quantities[] = DB::table('product_warehouse')
                            ->where('warehouse_id', $wh_id)
                            ->where('product_id', $prd)
                            ->pluck('quantity')
                            ->toArray();
                            // ->lists('quantity')
                            // ->all();
                        }

                        // dd($old_quantities); //associative array

                        $old_qty = [];
                        // $olq_qty = array_values($old_quantities);
                        foreach($old_quantities as $key=>$value){
                            foreach($value as $k=>$v){
                               $old_qty[] = $v;
                            }
                        }

                        $old_qtys = array_values($old_qty);
                        // dd($old_qty);

                        //subtraction!
                        $new_quantities = array_map(function($o, $q){
                            if($o < $q){
                                return $o;
                            }
                            return $o - $q;
                        }, $old_qtys, $qty_arr);


                        $extra1 = array_map(function($qt){
                            return ['quantity' => $qt];
                        }, $new_quantities);

                        $data1 = array_combine($prd_arr, $extra1);


                        $warehouse->products()->syncWithoutDetaching($data1);








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
                    //     //'errors' => $validator->getMessageBag()->toArray(),
                    // ], 200);

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
                'modal-input-chargehrs-edit' => 'required|numeric',
                'modal-input-hours-edit' => 'required|numeric',
                // 'modal-input-bulletin-edit' => 'required|mimes:txt,pdf,zip,doc,docx', //mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
                'modal-input-dtitle-edit' => 'required',
                'modal-input-exportassignment-edit' => 'required',
                // 'modal-input-prod-edit.*' => 'required',
                // 'modal-input-prodqty-edit.*' => 'required|numeric|gt:0',
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
                'modal-input-chargehrs-edit.numeric' => 'Οι χρεώσιμες εργάσιμες πρέπει να είναι αριθμός',
                'modal-input-chargehrs-edit.gt' => 'Οι ΧρΩΕ πρέπει να είναι περισσότερες από τις ΩΕ',
                'modal-input-hours-edit.required' => 'Οι εργάσιμες ώρες απαιτούνται',
                'modal-input-hours-edit.numeric' => 'Οι εργάσιμες ώρες πρέπει να είναι αριθμός',
                // 'modal-input-bulletin-edit.required' => 'Το Δελτίο Αποστολής απαιτείται',
                // 'modal-input-bulletin-edit.mimes' => 'Τύποι αρχείων για το Δελτίο Αποστολής: pdf, txt, doc, docx.',
                'modal-input-dtitle-edit.required' => 'Ο Διακριτός Τίτλος Παράδοσης απαιτείται',
                'modal-input-exportassignment-edit.required' => 'Η Ανάθεση Εξαγωγής απαιτείται',
                // 'modal-input-prod-edit.*.required' => 'Τα Προϊόντα απαιτούνται',
                // 'modal-input-prodqty-edit.*.required' => 'Η Ποσότητα απαιτείται',
                // 'modal-input-prodqty-edit.*.numeric' => 'Η Ποσότητα πρέπει να είναι αριθμός',
                // 'modal-input-prodqty-edit.*.gt' => 'Εισάγατε μηδενική ή αρνητική Ποσότητα',
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

                    DB::beginTransaction();

                    try{
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

                        // if($request->hasFile('modal-input-bulletin-edit')){
                        //     $file = $request->file('modal-input-bulletin-edit');

                        //     $datetime_now = date_create();
                        //     $datetime = date_format($datetime_now, 'YmdHis');
                        //     $name = $datetime . '-' . $file->getClientOriginalName();
                        //     $path = $file->storeAs('arxeia/exagwgis', $name);
                        //     $url  = \Storage::url($path);

                        //     $export->shipment_bulletin = $url;
                        // }
                        $export->shipment_bulletin = null;

                        $export->update($request->all());

                        //No, NOT products in the update..!
                        //also, update the pivot table, ie save the relation in the pivot table!
                        // $export->products()->sync($request->input('modal-input-products-edit'));


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
                    //     //'errors' => $validator->getMessageBag()->toArray(),
                    // ], 200);

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

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker'])){

            if ($request->ajax()){

                $export = Export::findOrFail($id);
                $export->delete();

                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    // public function get_deltio_exp(Request $request, $filename){

    //     if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker'])){


    //         $path_to_file = 'arxeia'.DIRECTORY_SEPARATOR.'eksagwgis'.DIRECTORY_SEPARATOR . $filename;

    //         //$filename is the value that i pass from the view as route parameter!
    //         //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]


    //         if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path. so in our example we pass directly the path (/.../laravelProject/storage/app) is the default one (referenced with the helper storage_path('app')


    //             // $name = substr($filenames, 15);
    //             $name = str_replace(' ', '_', substr($filename, 15));


    //             if(substr($name, -4) == '.txt'){

    //                 $headers = [
    //                     // 'Content-Type' => 'application/pdf',
    //                     'Content-Type' => 'text/plain',
    //                     'Cache-Control' => 'no-cache, no-store, must-revalidate',
    //                     'Pragma' => 'no-cache',
    //                     'Expires' => '0',
    //                     // // 'Content-Disposition' => 'attachment',
    //                     'Content-Disposition' => 'attachment; filename="'.$name.'"',
    //                 ];

    //             } else if(substr($name, -4) == '.pdf'){

    //                 $headers = [
    //                     'Content-Type' => 'application/pdf',
    //                     // 'Content-Type' => 'text/plain',
    //                     'Cache-Control' => 'no-cache, no-store, must-revalidate',
    //                     'Pragma' => 'no-cache',
    //                     'Expires' => '0',
    //                     // 'Content-Disposition' => 'attachment',
    //                     'Content-Disposition' => 'attachment; filename="'.$name.'"',
    //                 ];

    //             } else if(substr($name, -4) == '.doc'){

    //                 $headers = [
    //                     // 'Content-Type' => 'application/pdf',
    //                     // 'Content-Type' => 'text/plain',
    //                     'Content-Type' => 'application/msword',
    //                     'Cache-Control' => 'no-cache, no-store, must-revalidate',
    //                     'Pragma' => 'no-cache',
    //                     'Expires' => '0',
    //                     // 'Content-Disposition' => 'attachment',
    //                     'Content-Disposition' => 'attachment; filename="'.$name.'"',
    //                 ];

    //             } else {

    //                 $headers = [
    //                     // 'Content-Type' => 'application/pdf',
    //                     // 'Content-Type' => 'text/plain',
    //                     'Cache-Control' => 'no-cache, no-store, must-revalidate',
    //                     'Pragma' => 'no-cache',
    //                     'Expires' => '0',
    //                     // 'Content-Disposition' => 'attachment',
    //                     'Content-Disposition' => 'attachment; filename="'.$name.'"',
    //                 ];

    //             }


    //             return \Storage::download($path_to_file, $name, $headers);


    //         } else {
    //             return abort('404', 'Το αρχείο δεν υπάρχει'); // we redirect to 404 page if it doesn't exist
    //         }



    //     } else {
    //         return abort(403, 'Sorry you cannot view this page');
    //     }

    // }
}
