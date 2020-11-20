<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportProductTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('import_product', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('import_id');
            $table->unsignedBigInteger('product_id');

            $table->float('quantity'); // extra/custom field in this pivot table (Import Assignments Info Screen)

            $table->foreign('import_id')->references('id')->on('imports')->onDelete('cascade')->onUpdate('cascade');
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
        Schema::table('import_product', function (Blueprint $table){
            $table->dropForeign(['product_id']);
            $table->dropColumn('product_id');

            $table->dropForeign(['import_id']);
            $table->dropColumn('import_id');
        });

        // Schema::dropIfExists('import_product');
    }
}
