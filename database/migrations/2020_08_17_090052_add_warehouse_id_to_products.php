<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWarehouseIdToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            /*
            $table->unsignedBigInteger('warehouse_id');

            $table->foreign('warehouse_id')
                    ->references('id')
                    ->on('warehouse');
            */
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('products', function (Blueprint $table) {
            //
            /*
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');
            */
        });
    }
}
