<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeeWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employee_warehouse', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('employee_id');
            $table->unsignedBigInteger('warehouse_id');

            $table->foreign('employee_id')->references('id')->on('employees');
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
        Schema::table('employee_warehouse', function (Blueprint $table){
            $table->dropForeign(['warehouse_id']);
            $table->dropColumn('warehouse_id');

            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
        });

        Schema::dropIfExists('employee_warehouse');
    }
}
