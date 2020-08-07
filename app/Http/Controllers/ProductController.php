<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Product;
use App\Category;

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

            return view('products_view', ['products' => $products, 'categories' => $categories]); //also, send the $products & $categories variable to the 'products_view' Blade View.

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

            $product->name            = $request->input('modal-input-name-create');
            $product->description     = $request->input('modal-input-description-create');
            $product->category_id     = $request->input('modal-input-type-create');         //references 'id' in category table...
            $product->quantity        = $request->input('modal-input-quantity-create');
            $product->comments        = $request->input('modal-input-comments-create');
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

            $product->name            = $request->input('modal-input-name-edit');
            $product->description     = $request->input('modal-input-description-edit');
            $product->category_id     = $request->input('modal-input-type-edit');
            $product->quantity        = $request->input('modal-input-quantity-edit');
            $product->comments        = $request->input('modal-input-comments-edit');


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



    //==================================================================================================
    //CATEGORIES::


    //View all product categories
    public function view_categories(Request $request){

        //4 user types -> Admin, CEO, Foreman, Worker
        //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $categories = DB::table('category')->get();

            return view('category_view', ['categories' => $categories]);

        } else {
             return abort(403, 'Sorry you cannot view this page');
        }
    }


    //Create a new product category
    public function create_category(Request $request){

         //4 user types -> Admin, CEO, Foreman, Worker
         //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $category = new Category();

            $category->name            = $request->input('modal-input-name-create');
            $category->description     = $request->input('modal-input-description-create');

            $category->save();

            if ($request->ajax()){
                return \Response::json();
            }

             return back();

         } else {
              return abort(403, 'Sorry you cannot view this page');
         }

    }


    //Update an existing product category
    public function update_category(Request $request, $id){

         //4 user types -> Admin, CEO, Foreman, Worker
         //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $category = Category::findOrFail($id); //was findOrFail($u_id);

            $category->name            = $request->input('modal-input-name-edit');
            $category->description     = $request->input('modal-input-description-edit');

            $category->update($request->all());


            if ($request->ajax()){
                return \Response::json();
            }

             return back();

         } else {
              return abort(403, 'Sorry you cannot view this page');
         }

    }


    //Delete an existing product category
    public function delete_category(Request $request, $id){

         //4 user types -> Admin, CEO, Foreman, Worker

         //$authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         //if ($authenticatedUser){
        if(\Gate::any(['isSuperAdmin', 'isCompanyCEO', 'isWarehouseForeman', 'isWarehouseWorker'])){

            $category = Category::findOrFail($id);
            $category->delete();

            if ($request->ajax()){
                return \Response::json();
            }

             return back();

         } else {
              return abort(403, 'Sorry you cannot view this page');
         }

    }

}
