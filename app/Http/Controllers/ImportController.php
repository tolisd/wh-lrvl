<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use Validator;
use App\Import;
use App\Importassignment;
use App\Company;
use App\Transport; //transport_companies, i.e. shipping companies
use App\Employee;
use App\User;
use App\Product;
use App\Warehouse;
use Carbon\Carbon;

class ImportController extends Controller
{
    //
    public function view_imports(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker'])){

            $importassignments = ImportAssignment::where('is_open', '=', 1)->get(); //was: ImportAssignment::all();, but I only want the currently OPEN ones
            $imports = Import::all();
            $companies = Company::all();
            $transport_companies = Transport::all();
            $employees = Employee::all();
            $users = User::all();
            $warehouses = Warehouse::all();
            $products = Product::all(); //what if there are a lot of products in the DB? ==> chunk & DB Facade (aka Query Builder)


            $impassids = [];
            foreach($imports as $import){
                array_push($impassids, $import->importassignment_id);
            }
            //dd($impassids);

            /*
            $warehouse_id = \Request::get('warehouse_id');
            dd($warehouse_id);

            $employee_id = \Request::get('employee_id');
            dd($employee_id); //null
            $importassignment_id = \Request::get('importassignment_id'); //null
            dd($importassignment_id);
            */

            //DB Facade aka Query Builder, for faster join!
            $employees_per_warehouse = DB::table('employees')
                                        //->join('imports', 'employees.id', '=', 'imports.employee_id')
                                        ->join('employee_warehouse','employee_warehouse.employee_id','=','employees.id')
                                        ->join('importassignments', 'importassignments.warehouse_id', '=', 'employee_warehouse.warehouse_id')
                                        ->join('users', 'users.id', '=', 'employees.user_id')
                                        ->where('users.user_type', 'warehouse_worker')
                                        ->whereIn('importassignments.id', $impassids)
                                        ->select('users.name', 'employees.id', 'employee_warehouse.warehouse_id')
                                        ->get();

            //dd($employees_per_warehouse); gets the names!
            /*
            $employee_names = DB::table('employees')
                            ->join('users', 'users.id', '=', 'employees.user_id')
                            ->join('warehouse', 'employees.warehouse_id','=','warehouse.id')
                            ->join('importassignments', 'importassignments.warehouse_id', '=', 'warehouse.id')
                            ->select('users.name', 'employees.id', 'employees.warehouse_id')
                            ->get();
            */

            //dd($employee_names);


            return view('imports_view', ['importassignments' => $importassignments,
                                        'companies' => $companies,
                                        'employees' => $employees,
                                        'users' => $users,
                                        'warehouses' => $warehouses,
                                        'employees_per_warehouse' => $employees_per_warehouse,
                                        //'employee_names' => $employee_names,
                                        'transport_companies' => $transport_companies,
                                        'imports' => $imports,
                                        'products' => $products,
                                        ]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function create_import(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker'])){


            //validation rules
            $validation_rules = [
                'modal-input-recipient-create' => 'required',
                'modal-input-impco-create' => 'required',
                'modal-input-dtdeliv-create' => 'required',
                'modal-input-vehicleregno-create' => 'required',
                'modal-input-shipco-create' => 'required',
                'modal-input-destin-create' => 'required',
                'modal-input-chargehrs-create' => 'required|numeric',
                'modal-input-hours-create' => 'required|numeric',
                'modal-input-bulletin-create' => 'required|mimes:pdf,txt,zip,doc,docx', //mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
                'modal-input-dtitle-create' => 'required',
                'modal-input-importassignment-create' => 'required',
                //'modal-input-products-create' => 'required',
            ];

            //custom error messages for the above validation rules
            $custom_messages = [
                'modal-input-recipient-create.required' => 'Ο Υπεύθυνος Παραλαβής απαιτείται',
                'modal-input-impco-create.required' => 'Η Εταιρεία Εισαγωγής απαιτείται',
                'modal-input-dtdeliv-create.required' => 'Η Ημ/νία & Ώρα Παραλαβής απαιτείται',
                'modal-input-vehicleregno-create.required' => 'Ο Αρ.Κυκλ. Μεταφορικού Μέσου απαιτείται',
                'modal-input-shipco-create.required' => 'Η Μεταφορική Εταιρεία απαιτείται',
                'modal-input-destin-create.required' => 'Ο Τόπος Αποστολής απαιτείται',
                'modal-input-chargehrs-create.required' => 'Οι χρεώσιμες εργάσιμες ώρες απαιτούνται',
                'modal-input-chargehrs-create.numeric' => 'Οι χρεώσιμες εργάσιμες πρέπει να είναι αριθμός',
                'modal-input-hours-create.required' => 'Οι εργάσιμες ώρες απαιτούνται',
                'modal-input-hours-create.numeric' => 'Οι εργάσιμες ώρες πρέπει να είναι αριθμός',
                'modal-input-bulletin-create.required' => 'Το Δελτίο Αποστολής απαιτείται',
                'modal-input-bulletin-create.mimes' => 'Τύποι αρχείων για το Δελτίο Αποστολής: pdf, txt, doc, docx.',
                'modal-input-dtitle-create.required' => 'Ο Διακριτός Τίτλος Παραλαβής απαιτείται',
                'modal-input-importassignment-create.required' => 'Η Ανάθεση Εισαγωγής απαιτείται',
                //'modal-input-products-create.required' => 'Τα προϊόντα απαιτούνται',
            ];

            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){

                    //validation failure, error 422
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

                        $import = new Import();

                        $import->employee_id             = $request->input('modal-input-recipient-create');
                        $import->company_id              = $request->input('modal-input-impco-create');
                        $import->delivered_on            = Carbon::createFromFormat('d-m-Y H:i', $request->input('modal-input-dtdeliv-create'));
                        $import->vehicle_reg_no          = $request->input('modal-input-vehicleregno-create');
                        $import->transport_id            = $request->input('modal-input-shipco-create');
                        $import->delivery_address        = $request->input('modal-input-destin-create');
                        $import->chargeable_hours_worked = $request->input('modal-input-chargehrs-create');
                        $import->hours_worked            = $request->input('modal-input-hours-create');
                        $import->discrete_description    = $request->input('modal-input-dtitle-create');
                        //$import->shipment_address = $request->input('modal-input--create');
                        //$import->product_id = $request->input('modal-input-products-create');
                        $import->importassignment_id     = $request->input('modal-input-importassignment-create');

                        if($request->hasFile('modal-input-bulletin-create')){
                            $file = $request->file('modal-input-bulletin-create');

                            $datetime_now = date_create();
                            $datetime = date_format($datetime_now, 'YmdHis');
                            $name = $datetime . '-' . $file->getClientOriginalName();
                            $path = $file->storeAs('arxeia/eisagwgis', $name);
                            $url  = \Storage::url($path);

                            $import->shipment_bulletin = $url;
                        }

                        $import->save();


                        DB::commit();

                        //success, 200 OK.
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


                    // //success, 200 OK.
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

    public function update_import(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker'])){

             //validation rules
             $validation_rules = [
                'modal-input-recipient-edit' => 'required',
                'modal-input-impco-edit' => 'required',
                'modal-input-dtdeliv-edit' => 'required',
                'modal-input-vehicleregno-edit' => 'required',
                'modal-input-shipco-edit' => 'required',
                'modal-input-destin-edit' => 'required',
                'modal-input-chargehrs-edit' => 'required|numeric',
                'modal-input-hours-edit' => 'required|numeric',
                'modal-input-bulletin-edit' => 'required|mimes:pdf,txt,zip,doc,docx', //mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
                'modal-input-dtitle-edit' => 'required',
                'modal-input-importassignment-edit' => 'required',
                //'modal-input-products-edit' => 'required',
            ];

            //custom error messages for the above validation rules
            $custom_messages = [
                'modal-input-recipient-edit.required' => 'Ο Υπεύθυνος Παραλαβής απαιτείται',
                'modal-input-impco-edit.required' => 'Η Εταιρεία Εισαγωγής απαιτείται',
                'modal-input-dtdeliv-edit.required' => 'Η Ημ/νία & Ώρα Παραλαβής απαιτείται',
                'modal-input-vehicleregno-edit.required' => 'Ο Αρ.Κυκλ. Μεταφορικού Μέσου απαιτείται',
                'modal-input-shipco-edit.required' => 'Η Μεταφορική Εταιρεία απαιτείται',
                'modal-input-destin-edit.required' => 'Ο Τόπος Αποστολής απαιτείται',
                'modal-input-chargehrs-edit.required' => 'Οι χρεώσιμες εργάσιμες ώρες απαιτούνται',
                'modal-input-chargehrs-edit.numeric' => 'Οι χρεώσιμες εργάσιμες πρέπει να είναι αριθμός',
                'modal-input-hours-edit.required' => 'Οι εργάσιμες ώρες απαιτούνται',
                'modal-input-hours-edit.numeric' => 'Οι εργάσιμες ώρες πρέπει να είναι αριθμός',
                'modal-input-bulletin-edit.required' => 'Το Δελτίο Αποστολής απαιτείται',
                'modal-input-bulletin-edit.mimes' => 'Μη έγκυρο αρχείο. Τύποι αρχείων για το Δελτίο Αποστολής: pdf, txt, doc, docx.',
                'modal-input-dtitle-edit.required' => 'Ο Διακριτός Τίτλος Παραλαβής απαιτείται',
                'modal-input-importassignment-edit.required' => 'Η Ανάθεση Εισαγωγής απαιτείται',
                //'modal-input-products-edit.required' => 'Τα Προϊόντα απαιτούνται',
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

                    DB::beginTransaction();

                    try{
                        //success
                        //save-update the object

                        $import = Import::findOrFail($id);


                        $import->delivered_on            = Carbon::createFromFormat('d-m-Y H:i', $request->input('modal-input-dtdeliv-edit'));
                        $import->delivery_address        = $request->input('modal-input-destin-edit');
                        $import->discrete_description    = $request->input('modal-input-dtitle-edit');
                        $import->hours_worked            = $request->input('modal-input-hours-edit');
                        $import->chargeable_hours_worked = $request->input('modal-input-chargehrs-edit');

                        if($request->hasFile('modal-input-bulletin-edit')){
                            $file = $request->file('modal-input-bulletin-edit');

                            $datetime_now = date_create();
                            $datetime = date_format($datetime_now, 'YmdHis');
                            $name = $datetime . '-' . $file->getClientOriginalName();
                            $path = $file->storeAs('arxeia/eisagwgis', $name);
                            $url  = \Storage::url($path);

                            $import->shipment_bulletin = $url;
                        }

                        $import->vehicle_reg_no          = $request->input('modal-input-vehicleregno-edit');
                        //$import->shipment_address = $request->input('modal-input--create');
                        //$import->product_id = $request->input('modal-input-products-create');
                        $import->employee_id             = $request->input('modal-input-recipient-edit');
                        $import->company_id              = $request->input('modal-input-impco-edit');
                        $import->transport_id            = $request->input('modal-input-shipco-edit');
                        $import->importassignment_id     = $request->input('modal-input-importassignment-edit');



                        $import->update($request->all());


                        DB::commit();

                        //success, 200 OK.
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

    public function delete_import(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker'])){


            if ($request->ajax()){

                $import = Import::findOrFail($id);
                $import->delete();

                return \Response::json();
            }

            return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    public function get_deltio_imp(Request $request, $filename){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant', 'isWarehouseWorker'])){


            $path_to_file = 'arxeia'.DIRECTORY_SEPARATOR.'eisagwgis'.DIRECTORY_SEPARATOR . $filename;

            //$filename is the value that i pass from the view as route parameter!
            //in this case it is:: ['filename' => substr(basename($tool->file_url), 15)]


            if (\Storage::disk('local')->exists($path_to_file)){ // note that disk()->exists() expect a relative path, from your disk root path. so in our example we pass directly the path (/.../laravelProject/storage/app) is the default one (referenced with the helper storage_path('app')


                // $name = substr($filenames, 15);
                $name = str_replace(' ', '_', substr($filename, 15));


                if(substr($name, -4) == '.txt'){

                    $headers = [
                        // 'Content-Type' => 'application/pdf',
                        'Content-Type' => 'text/plain',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                } else if(substr($name, -4) == '.pdf'){

                    $headers = [
                        'Content-Type' => 'application/pdf',
                        // 'Content-Type' => 'text/plain',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                } else if(substr($name, -4) == '.doc'){

                    $headers = [
                        // 'Content-Type' => 'application/pdf',
                        // 'Content-Type' => 'text/plain',
                        'Content-Type' => 'application/msword',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                } else {

                    $headers = [
                        // 'Content-Type' => 'application/pdf',
                        // 'Content-Type' => 'text/plain',
                        'Cache-Control' => 'no-cache, no-store, must-revalidate',
                        'Pragma' => 'no-cache',
                        'Expires' => '0',
                        // 'Content-Disposition' => 'attachment',
                        'Content-Disposition' => 'attachment; filename="'.$name.'"',
                    ];

                }


                return \Storage::download($path_to_file, $name, $headers);


            } else {
                return abort('404', 'Το αρχείο δεν υπάρχει'); // we redirect to 404 page if it doesn't exist
            }



        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
