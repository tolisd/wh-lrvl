<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_warehouse', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('product_id');
            $table->unsignedBigInteger('warehouse_id');

            $table->float('quantity'); //inserted here, after it was removed from the 'products' table
                                       //should be nullable(?) for the case when i dont have a product in that warehouse..
                                       // NO! not nullable, its ok like this!

            $table->foreign('product_id')->references('id')->on('products');
            $table->foreign('warehouse_id')->references('id')->on('warehouse');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //added this for dropping the 2 FKs
        Schema::table('product_warehouse', function (Blueprint $table){
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
        });


        Schema::dropIfExists('product_warehouse');
    }
}
