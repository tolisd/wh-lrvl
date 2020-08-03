<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;  //added for DB retrieval
use Auth; //added for Auth
use App\Product;

class ProductController extends Controller
{
    //VIEW ALL Products
    public function view_products(Request $request){
         //4 user types -> Admin, CEO, Foreman, Worker
         $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         if ($authenticatedUser){

            $products = DB::table('products')->get(); //Get ALL product(s) row(s) from products db table.

            return view('products_view', ['products' => $products]); //also, send the $products variable to the 'products_view' Blade View.

         } else {
             return abort(403, 'Sorry you cannot view this page');
         }
    }

    //CREATE a new product
    public function create_product(Request $request){
        //4 user types -> Admin, CEO, Foreman, Worker
        $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

        if ($authenticatedUser){

            $product = new Product();

            $product->name            = $request->input('modal-input-name-create');
            $product->description     = $request->input('modal-input-description-create');
            $product->type            = $request->input('modal-input-type-create');         //references 'id' in category table...
            $product->quantity        = $request->input('modal-input-quantity-create');
            $product->comments        = $request->input('modal-input-comments-create');
            // Set other fields (if applicable)...

            $product->save(); //Save the new user into the database

            return back();
            //return view('create_product');
        } else {
            return abort(403, 'Sorry you cannot view this page');
        }

    }

    //EDIT/UPDATE an existing product
    public function update_product(Request $request, $id){
         //4 user types -> Admin, CEO, Foreman, Worker
         $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         if ($authenticatedUser){

            $product = Product::findOrFail($id); //was findOrFail($u_id);

            $product->name            = $request->input('modal-input-name-edit');
            $product->description     = $request->input('modal-input-description-edit');
            $product->type            = $request->input('modal-input-type-edit');
            $product->quantity        = $request->input('modal-input-quantity-edit');
            $product->comments        = $request->input('modal-input-comments-edit');

            $product->update($request->all());


            return back();
            //return view('update_product');
         } else {
            return abort(403, 'Sorry you cannot view this page');
         }

    }

    //DELETE an existing product
    public function delete_product(Request $request, $id){
         //4 user types -> Admin, CEO, Foreman, Worker
         $authenticatedUser = Auth::check() && Auth::user()->user_type(['super_admin', 'company_ceo', 'warehouse_foreman', 'warehouse_worker']);

         if ($authenticatedUser){

            $product = User::findOrFail($id);
            $product->delete();

            return back();
            //return view('delete_product');
         } else {
             return abort(403, 'Sorry you cannot view this page');
         }

    }
}
