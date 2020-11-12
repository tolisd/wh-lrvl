<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use Validator;
use App\MeasureUnit;

class MeasureController extends Controller
{
    //
    public function view_measunits(){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $measunits = DB::table('measunits')->get();

            return view('measunit_view', ['measunits' => $measunits]);

        } else {
            return abort(403, 'Sorry you cannot view this page');
        }
    }

    public function create_measunit(Request $request){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $rules = [
                'modal-input-name-create' => 'required',
                'modal-input-description-create' => 'required'
            ];

            $custom_messages = [
                'modal-input-name-create.required' => 'Το όνομα απαιτείται',
                'modal-input-description-create.required' => 'Η περιγραφή απαιτείται'
            ];

            $validator = Validator::make($request->all(), $rules, $custom_messages);

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
                        $measunit = new MeasureUnit();

                        $measunit->name            = $request->input('modal-input-name-create');
                        $measunit->description     = $request->input('modal-input-description-create');

                        $measunit->save();

                        DB::commit();

                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);

                    } catch(\Exception $e){
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

    public function update_measunit(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $rules = [
                'modal-input-name-edit' => 'required',
                'modal-input-description-edit' => 'required'
            ];

            $custom_messages = [
                'modal-input-name-edit.required' => 'Το όνομα απαιτείται',
                'modal-input-description-edit.required' => 'Η περιγραφή απαιτείται'
            ];

            $validator = Validator::make($request->all(), $rules, $custom_messages);

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
                        $measunit = MeasureUnit::findOrFail($id);

                        $measunit->name            = $request->input('modal-input-name-edit');
                        $measunit->description     = $request->input('modal-input-description-edit');

                        $measunit->update($request->all());

                        DB::commit();

                        return \Response::json([
                            'success' => true,
                            //'errors' => $validator->getMessageBag()->toArray(),
                        ], 200);

                    } catch(\Exception $e){
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

        } else{
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    public function delete_measunit(Request $request, $id){

        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){


            if ($request->ajax()){

                $measunit = MeasureUnit::findOrFail($id);
                $measunit->delete();

                return \Response::json();
            }

            return back();


        } else{
            return abort(403, 'Sorry you cannot view this page');
        }

    }
}
