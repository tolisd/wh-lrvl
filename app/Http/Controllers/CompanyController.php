<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Company;
use Validator;

class CompanyController extends Controller
{
    //
    public function view_companies(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $companies = Company::all();

            return view('companies_view', ['companies' => $companies]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_company(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            //rules for validation
            $rules = [
                'modal-input-name-create' => 'required',
                'modal-input-afm-create' => 'required|numeric',
                'modal-input-doy-create' => 'required',
                'modal-input-pcode-create' => 'required|numeric',
                'modal-input-address-create' => 'required',
                'modal-input-city-create' => 'required',
                'modal-input-telno-create' => 'required',
                'modal-input-email-create' => 'required|email|unique:company,email',
                'modal-input-comments-create' => 'nullable',
            ];

            //custom messages for the validation rules above
            $custom_messages = [
                'modal-input-name-create.required' => 'To όνομα απαιτείται',
                'modal-input-afm-create.required' => 'Το ΑΦΜ απαιτείται',
                'modal-input-doy-create.required' => 'Η ΔΟΥ απαιτείται',
                'modal-input-pcode-create.required' => 'Ο T.K. απαιτείται',
                'modal-input-address-create.required' => 'Η διεύθυνση απαιτείται',
                'modal-input-city-create.required' => 'Η πόλη απαιτείται',
                'modal-input-telno-create.required' => 'Ο τηλεφωνικός αριθμός απαιτείται',
                'modal-input-email-create.required' => 'Το ηλ.ταχυδρομείο απαιτείται',
            ];

            //prepare the $validator variable
            $validator = Validator::make($request->all(), $rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);

                } else if($validator->passes()){

                    $company = new Company();

                    $company->name          = $request->input('modal-input-name-create');
                    $company->AFM           = $request->input('modal-input-afm-create');
                    $company->DOY           = $request->input('modal-input-doy-create');
                    $company->postal_code   = $request->input('modal-input-pcode-create');
                    $company->address       = $request->input('modal-input-address-create');
                    $company->city          = $request->input('modal-input-city-create');
                    $company->phone_number  = $request->input('modal-input-telno-create');
                    $company->email         = $request->input('modal-input-email-create');
                    $company->comments      = $request->input('modal-input-comments-create');

                    $company->save();

                    return \Response::json([
                        'success' => true,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 200);
                }

            }

            /*
            if ($request->ajax()){
                return \Response::json();
            }
            */
            //return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function update_company(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            //validation rules
           $rules = [
                'modal-input-name-edit' => ['required'],
                'modal-input-afm-edit' => ['required','numeric'],
                'modal-input-doy-edit' => ['required'],
                'modal-input-pcode-edit' => ['required','numeric'],
                'modal-input-address-edit' => ['required'],
                'modal-input-city-edit' => ['required'],
                'modal-input-telno-edit' => ['required'],
                'modal-input-email-edit' => ['required','email', \Illuminate\Validation\Rule::unique('company', 'email')->ignore($id)],
                'modal-input-comments-edit' => ['nullable'],
            ];

            //validation custom messages for the rules above
            $custom_messages = [
                'modal-input-name-edit.required' => 'To όνομα απαιτείται',
                'modal-input-afm-edit.required' => 'Το ΑΦΜ απαιτείται',
                'modal-input-doy-edit.required' => 'Η ΔΟΥ απαιτείται',
                'modal-input-pcode-edit.required' => 'Ο T.K. απαιτείται',
                'modal-input-address-edit.required' => 'Η διεύθυνση απαιτείται',
                'modal-input-city-edit.required' => 'Η πόλη απαιτείται',
                'modal-input-telno-edit.required' => 'Ο τηλεφωνικός αριθμός απαιτείται',
                'modal-input-email-edit.required' => 'Το ηλ.ταχυδρομείο απαιτείται',
            ];

             //prepare the $validator variable
             $validator = Validator::make($request->all(), $rules, $custom_messages);

             if($request->ajax()){

                 if($validator->fails()){
                     //---failure
                     return \Response::json([
                         'success' => false,
                         'errors' => $validator->getMessageBag()->toArray(),
                     ], 422);

                    } else if($validator->passes()){

                        $company = Company::findOrFail($id);  //get this row with [$company->id == $id]

                        $company->name          = $request->input('modal-input-name-edit');
                        $company->AFM           = $request->input('modal-input-afm-edit');
                        $company->DOY           = $request->input('modal-input-doy-edit');
                        $company->postal_code   = $request->input('modal-input-pcode-edit');
                        $company->city          = $request->input('modal-input-city-edit');
                        $company->phone_number  = $request->input('modal-input-telno-edit');
                        $company->email         = $request->input('modal-input-email-edit');
                        $company->address       = $request->input('modal-input-address-edit');
                        $company->comments      = $request->input('modal-input-comments-edit');

                        $company->update($request->all());

                        //---success
                        return \Response::json([
                            'success' => true,
                            'errors' => $validator->getMessageBag()->toArray(),
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

    public function delete_company(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $company = Company::findOrFail($id);
            //delete a company if it has no employees and no warehouses...
            $company->delete();

            if ($request->ajax()){
                return \Response::json();
            }

             return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
