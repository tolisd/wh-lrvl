<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
//use Illuminate\Support\Facades\Input;
use Auth; //added for Auth
use Validator;
use App\Product;
use App\Category;
use App\Type;
use App\MeasureUnit;
use App\Warehouse; //for the N-to-M relationship

class ProductController extends Controller
{
    //VIEW ALL Products
    public function view_products(Request $request){
         //4 user types -> Admin, CEO, Foreman, Worker
         //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            //$products = DB::table('products')->get(); //Get ALL product(s) row(s) from products db table. ---> Product::all();, in Eloquent ORM
            $products = Product::with('category')->get(); //because I want to display Categories also

            $categories = Category::has('type')->get(); //::all();

            //get the types, aka the subcategories
            $category_id = \Request::get('category_id');
            $types = Type::where('category_id', '=', $category_id)->get();

            //$types = Type::with('category')->get(); //::all();
            $measunits = MeasureUnit::all();
            $warehouses = Warehouse::all(); //has('products')->get();

            return view('products_view', ['products' => $products,
                                          'categories' => $categories,
                                          'types' => $types,
                                          'measunits' => $measunits,
                                          'warehouses' => $warehouses]); //also, send the $products & $categories variable to the 'products_view' Blade View.

         } else {
             return abort(403, 'Sorry you cannot view this page');
         }
    }

    //CREATE a new product
    public function create_product(Request $request){
        //4 user types -> Admin, CEO, Foreman, Worker
        //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $validation_rules = [
                'modal-input-code-create' => 'required|unique:products,code',
                'modal-input-name-create' => 'required',
                'modal-input-type-create' => 'required|exists:types,id',
                'modal-input-category-create' => 'required|exists:category,id',
                'modal-input-description-create' => 'required',
                'modal-input-quantity-create' => 'required',
                'modal-input-measureunit-create' => 'required|exists:measunits,id',
                'modal-input-comments-create' => 'required',
                'modal-input-warehouses-create' => 'required',
            ];

            $custom_messages = [
                'modal-input-code-create.required' => 'Ο κωδικός απαιτείται',
                'modal-input-name-create.required' => 'Το όνομα απαιτείται',
                'modal-input-type-create.required' => 'Το είδος απαιτείται',
                'modal-input-category-create.required' => 'Η κατηγορία απαιτείται',
                'modal-input-description-create.required' => 'Η περιγραφή απαιτείται',
                'modal-input-quantity-create.required' => 'Η ποσότητα απαιτείται',
                'modal-input-measureunit-create.required' => 'Η μονάδα μέτρησης απαιτείται',
                'modal-input-comments-create.required' => 'Τα σχόλια απαιτούνται',
                'modal-input-warehouses-create.required' => 'Η/Οι αποθήκη/-ες απαιτούνται',
            ];

            //prepare the $validator variable
            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){
                    //failure, 422
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){

                    //you want to wrap this WHOLE block into a DB transaction.

                    $product = new Product();

                    $product->code            = $request->input('modal-input-code-create');
                    $product->name            = $request->input('modal-input-name-create');
                    $product->type_id         = $request->input('modal-input-type-create');             //references 'id' in types table
                    $product->category_id     = $request->input('modal-input-category-create');         //references 'id' in category table
                    $product->description     = $request->input('modal-input-description-create');
                    $product->quantity        = $request->input('modal-input-quantity-create');
                    $product->measunit_id     = $request->input('modal-input-measureunit-create');
                    $product->comments        = $request->input('modal-input-comments-create');
                    //$product->assignment_id   = $request->input('modal-input-assignment-create');        //references 'id' in assignments table
                    // Set other fields (if applicable)...

                    $product->save(); //Save the new user into the database

                    //also, state here TO WHICH warehouse they belong, via N-to-M relationship between them!
                    //also save the relation in the pivot table!
                    $product->warehouses()->sync($request->input('modal-input-warehouses-create'));




                    //success, 200
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
            //return view('create_product');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //EDIT/UPDATE an existing product
    public function update_product(Request $request, $id){
         //4 user types -> Admin, CEO, Foreman, Worker
         //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $validation_rules = [
                'modal-input-code-edit' => ['required', \Illuminate\Validation\Rule::unique('products', 'code')->ignore($id)],
                'modal-input-name-edit' => ['required'],
                'modal-input-type-edit' => ['required', 'exists:types,id'],
                'modal-input-category-edit' => ['required', 'exists:category,id'],
                'modal-input-description-edit' => ['required'],
                'modal-input-quantity-edit' => ['required'],
                'modal-input-measureunit-edit' => ['required', 'exists:measunits,id'],
                'modal-input-comments-edit' => ['required'],
                'modal-input-warehouses-edit' => ['required'],
            ];

            $custom_messages = [
                'modal-input-code-edit.required' => 'Ο κωδικός απαιτείται',
                'modal-input-name-edit.required' => 'Το όνομα απαιτείται',
                'modal-input-type-edit.required' => 'Το είδος απαιτείται',
                'modal-input-category-edit.required' => 'Η κατηγορία απαιτείται',
                'modal-input-description-edit.required' => 'Η περιγραφή απαιτείται',
                'modal-input-quantity-edit.required' => 'Η ποσότητα απαιτείται',
                'modal-input-measureunit-edit.required' => 'Η μονάδα μέτρησης απαιτείται',
                'modal-input-comments-edit.required' => 'Τα σχόλια απαιτούνται',
                'modal-input-warehouses-edit' => 'Η/Οι αποθήκη/-ες απαιτούνται',
            ];

            //prepare the $validator variable
            $validator = Validator::make($request->all(), $validation_rules, $custom_messages);

            if($request->ajax()){

                if($validator->fails()){
                    //failure, 422
                    return \Response::json([
                        'success' => false,
                        'errors' => $validator->getMessageBag()->toArray(),
                    ], 422);
                }

                if($validator->passes()){

                    $product = Product::findOrFail($id); //was findOrFail($u_id);

                    $product->code            = $request->input('modal-input-code-edit');
                    $product->name            = $request->input('modal-input-name-edit');
                    $product->type_id         = $request->input('modal-input-type-edit');             //references 'id' in types table
                    $product->category_id     = $request->input('modal-input-category-edit');         //references 'id' in category table
                    $product->description     = $request->input('modal-input-description-edit');
                    $product->quantity        = $request->input('modal-input-quantity-edit');
                    $product->measunit_id     = $request->input('modal-input-measureunit-edit');
                    $product->comments        = $request->input('modal-input-comments-edit');
                    //$product->assignment_id   = $request->input('modal-input-assgncode-edit');        //references 'id' in assignments table

                    $product->update($request->all());  //configure the $fillable & $guarded properties/columns in this Model!

                    //also save the relation in the pivot table!
                    $product->warehouses()->sync($request->input('modal-input-warehouses-edit'));

                    //success, 200
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
            //return view('update_product');
         } else {
            return abort(403, 'Sorry you cannot view this page');
         }

    }

    //DELETE an existing product
    public function delete_product(Request $request, $id){
         //4 user types -> Admin, CEO, Foreman, Worker
         //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            //Wrap this in a DB transaction, as shown below? No it is not possible, eloquent does not have transactions..
            $product = Product::findOrFail($id);
            $product->warehouses()->detach();
            $product->delete();
            /*
            DB::transaction(function(){});
            */


            if ($request->ajax()){
                return \Response::json();
            }

            return back();
            //return view('delete_product');
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }

    }


}
