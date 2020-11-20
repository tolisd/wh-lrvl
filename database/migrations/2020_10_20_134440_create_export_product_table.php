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

            $table->float('quantity'); // extra/custom field in this pivot table (Export Assignments Info Screen)

            $table->foreign('export_id')->references('id')->on('exports')->onDelete('cascade')->onUpdate('cascade');
            $table->foreign('product_id')->references('id')->on('products')->onDelete('cascade')->onUpdate('cascade');

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
         Schema::table('export_product', function (Blueprint $table){
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            $table->dropForeign(['export_id']);
            $table->dropColumn('export_id');
        });

        Schema::dropIfExists('export_product');
    }
}
