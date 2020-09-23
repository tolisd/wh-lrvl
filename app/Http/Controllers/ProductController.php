<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Product;
use App\Category;
use App\Type;
use App\MeasureUnit;

class ProductController extends Controller
{
    //VIEW ALL Products
    public function view_products(Request $request){
         //4 user types -> Admin, CEO, Foreman, Worker
         //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            //$products = DB::table('products')->get(); //Get ALL product(s) row(s) from products db table. ---> Product::all();
            $products = Product::with('category')->get(); //because I want to display Categories also
            $categories = Category::all();
            $measunits = MeasureUnit::all();
            $types = Type::all();

            return view('products_view', ['products' => $products,
                                          'categories' => $categories,
                                          'types' => $types,
                                          'measunits' => $measunits]); //also, send the $products & $categories variable to the 'products_view' Blade View.

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

            if ($request->ajax()){
                return \Response::json();
            }

            return back();
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

            if ($request->ajax()){
                return \Response::json();
            }

            return back();
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

            $product = Product::findOrFail($id);
            $product->delete();

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
