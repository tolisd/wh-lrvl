<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('export_product', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('export_id');
            $table->unsignedBigInteger('product_id');

            $table->foreign('export_id')->references('id')->on('exports');
            $table->foreign('product_id')->references('id')->on('products');

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

            $table->dropForeign(['export_id']);
            $table->dropColumn('export_id');
        });

        Schema::dropIfExists('export_product');
    }
}