<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Transport;
use Validator;

class TransportController extends Controller
{
    //
    public function view_transport_companies(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            $transport_companies = Transport::all();

            return view('transport_view', ['transport_companies' => $transport_companies]);
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }


    public function create_transport_company(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            //dd($request->all());

            //validation rules
            $rules = [
                'modal-input-name-create' => 'required',
                'modal-input-afm-create' => 'required|numeric',
                'modal-input-doy-create' => 'required',
                'modal-input-pcode-create' => 'required|numeric',
                'modal-input-address-create' => 'required',
                'modal-input-city-create' => 'required',
                'modal-input-telno-create' => 'required',
                'modal-input-email-create' => 'required|email|unique:transports,email',
                'modal-input-comments-create' => 'nullable',
            ];

            //validation custom messages for the rules above
            $custom_messages = [
                'modal-input-name-create.required' => 'To όνομα απαιτείται',
                'modal-input-afm-create.required' => 'Το ΑΦΜ απαιτείται',
                'modal-input-doy-create.required' => 'Η ΔΟΥ απαιτείται',
                'modal-input-pcode-create.required' => 'Ο T.K. απαιτείται',
                'modal-input-address-create.required' => 'Η διεύθυνση απαιτείται',
                'modal-input-city-create.required' => 'Η πόλη απαιτείται',
                'modal-input-telno-create.required' => 'Ο τηλεφωνικός αριθμός απαιτείται',
                'modal-input-email-create.required' => 'Το ηλ.ταχυδρομείο απαιτείται',
                'modal-input-email-create.email' => 'Το ηλ.ταχυδρομείο δεν είναι έγκυρο',
            ];

            //$validator = $this->validate($request, [
            //$validator = \Validator::make($request->all(), [  //cannot do ($request, cos is an object, but rather CAN do $request->all() as it is an array (as expected)

            $validator = Validator::make($request->all(), $rules, $custom_messages); //->validate(); //If ->validate() is omitted then no errors are shown in blade
            //$validator = Validator::make($request->all(), $rules);
            //$request->validate($rules, $custom_messages);
            //$validation = $this->validate($request, $rules, $custom_messages);
            //$validator->validate();
            //$validation = $validator->validate(); //->validate($request->all());
            //$validated = $request->validated(); // validate

            //---from Laravel 7.x Docs---
            //If the validation rules pass, your code will keep executing normally;
            //however, if validation fails, an exception will be thrown and the proper error response will automatically be sent back to the user.
            //If validation fails, a redirect response will be generated to send the user back to their previous location.
            //The errors will also be flashed to the session so they are available for display.
            //If the request was an AJAX request, a HTTP response with a 422 status code will be returned
            //to the user including a JSON representation of the validation errors.

            // Validate the input and return correct response
            if ($request->ajax()){

                //---Failure
                if($validator->fails()){ //it means, there ARE errors in the form!

                    return \Response::json([
                        'success' => false,
                        //'message' => 'There were problems...!',
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                    //return \Response::json(['success' => 'successfully added data!'], 200); // 200 status code, success

                } else if($validator->passes()){

                    DB::beginTransaction();

                    try{
                        //---Success, there ARE NO errors in the Form..proceed....
                        //proceed with saving object to database
                        $tcompany = new Transport();

                        $tcompany->name          = $request->input('modal-input-name-create');
                        $tcompany->AFM           = $request->input('modal-input-afm-create');
                        $tcompany->DOY           = $request->input('modal-input-doy-create');
                        $tcompany->postal_code   = $request->input('modal-input-pcode-create');
                        $tcompany->city          = $request->input('modal-input-city-create');
                        $tcompany->phone_number  = $request->input('modal-input-telno-create');
                        $tcompany->email         = $request->input('modal-input-email-create');
                        $tcompany->address       = $request->input('modal-input-address-create');
                        $tcompany->comments      = $request->input('modal-input-comments-create');

                        $tcompany->save();

                        DB::commit();

                        //return a json response (success)
                        return \Response::json([
                            'success' => true,
                            'message' => 'Data added successfully!',
                        ], 200);

                    } catch(\Exception $e){

                        DB::rollBack();

                        //return a json response (some exception happened)
                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                        ], 500);

                    }



                    // //return a json response (success)
                    // return \Response::json([
                    //     'success' => true,
                    //     'message' => 'Data added successfully!',
                    //     'errors' => $validator->getMessageBag()->toArray(),
                    // ], 200);
                }

            } //End: if($request->ajax())


            /*
            $data = $request->all();
            $check = Transport::insert($data);
            $arr = ['msg' => 'Something went wrong...', 'status' => false];
            if($check){
                $arr = ['msg' => 'Successful submit form using ajax', 'status' => true];
            }
            return \Response::json($arr);
            */

            //return true;
            //return back()->withErrors($validator); //->with('errors');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }


    public function update_transport_company(Request $request, $id){

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
                'modal-input-email-edit' => ['required','email', \Illuminate\Validation\Rule::unique('transports', 'email')->ignore($id)],
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
                    //--failure
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);

                } else if($validator->passes()){

                    DB::beginTransaction();

                    try{

                        $tcompany = Transport::findOrFail($id);  //get this row with [$company->id == $id]

                        $tcompany->name          = $request->input('modal-input-name-edit');
                        $tcompany->AFM           = $request->input('modal-input-afm-edit');
                        $tcompany->DOY           = $request->input('modal-input-doy-edit');
                        $tcompany->postal_code   = $request->input('modal-input-pcode-edit');
                        $tcompany->city          = $request->input('modal-input-city-edit');
                        $tcompany->phone_number  = $request->input('modal-input-telno-edit');
                        $tcompany->email         = $request->input('modal-input-email-edit');
                        $tcompany->address       = $request->input('modal-input-address-edit');
                        $tcompany->comments      = $request->input('modal-input-comments-edit');

                        $tcompany->update($request->all());

                        DB::commit();

                        //---success
                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);


                    } catch(\Exception $e) {

                        DB::rollBack();

                        return \Response::json([
                            'success' => false,
                            'message' => $e->getMessage(),
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 500);
                    }


                    // //---success
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
            */
            //return back();

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_transport_company(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isAccountant'])){

            if ($request->ajax()){
                $company = Transport::findOrFail($id);
                //delete a company if it has no employees and no warehouses...
                $company->delete();

                return \Response::json();
            }

             return back();
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
