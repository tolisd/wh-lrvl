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

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){

            $importassignments = ImportAssignment::all();
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
                                        ->join('importassignments', 'importassignments.warehouse_id', '=', 'employees.warehouse_id')
                                        ->join('users', 'users.id', '=', 'employees.user_id')
                                        ->whereIn('importassignments.id', $impassids)
                                        ->select('users.name', 'employees.id', 'employees.warehouse_id')
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

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman' ,'isAccountant'])){


            //validation rules
            $validation_rules = [
                'modal-input-recipient-create' => 'required',
                'modal-input-impco-create' => 'required',
                'modal-input-dtdeliv-create' => 'required',
                'modal-input-vehicleregno-create' => 'required',
                'modal-input-shipco-create' => 'required',
                'modal-input-destin-create' => 'required',
                'modal-input-chargehrs-create' => 'required',
                'modal-input-hours-create' => 'required',
                'modal-input-bulletin-create' => 'required|mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
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
                'modal-input-chargehrs-create.required' => 'Οι χρεώσιμες ώρες εργασίας απαιτούνται',
                'modal-input-hours-create.required' => 'Οι εργάσιμες ώρες απαιτούνται',
                'modal-input-bulletin-create.required' => 'Το Δελτίο Αποστολής απαιτείται',
                'modal-input-bulletin-create.mimetypes' => 'Τύποι αρχείων για το Δελτίο Αποστολής: pdf, txt, doc, docx.',
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

                    //success, 200 OK.
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
                'modal-input-recipient-edit' => 'required',
                'modal-input-impco-edit' => 'required',
                'modal-input-dtdeliv-edit' => 'required',
                'modal-input-vehicleregno-edit' => 'required',
                'modal-input-shipco-edit' => 'required',
                'modal-input-destin-edit' => 'required',
                'modal-input-chargehrs-edit' => 'required',
                'modal-input-hours-edit' => 'required',
                'modal-input-bulletin-edit' => 'required|mimetypes:application/pdf,text/plain,application/msword,application/vnd.openxmlformats-officedocument-wordprocessingml.document',
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
                'modal-input-chargehrs-edit.required' => 'Οι χρεώσιμες ώρες εργασίας απαιτούνται',
                'modal-input-hours-edit.required' => 'Οι εργάσιμες ώρες απαιτούνται',
                'modal-input-bulletin-edit.required' => 'Το Δελτίο Αποστολής απαιτείται',
                'modal-input-bulletin-edit.mimetypes' => 'Τύποι αρχείων για το Δελτίο Αποστολής: pdf, txt, doc, docx.',
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
                    //success
                    //save-update the object

                    /*
                     protected $fillable = [
                        'delivered_on',
                        'delivery_address',
                        'discrete_description',
                        'hours_worked',
                        'chargeable_hours_worked',
                        'shipment_bulletin',
                        //'shipment_address',
                        'vehicle_reg_no',

                        //'product_id',
                        'employee_id',
                        'company_id',
                        'transport_id',
                        'importassignment_id',
                    ];
                    */
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
