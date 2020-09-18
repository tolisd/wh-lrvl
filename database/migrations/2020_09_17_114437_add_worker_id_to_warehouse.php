<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkerIdToWarehouse extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('warehouse', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('worker_id');

            $table->foreign('worker_id')
                    ->references('id')
                    ->on('employees');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('warehouse', function (Blueprint $table) {
            //
            $table->dropForeign(['worker_id']);
            $table->dropColumn('worker_id');
        });
    }
}
