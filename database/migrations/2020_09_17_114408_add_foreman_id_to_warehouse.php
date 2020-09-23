<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddForemanIdToWarehouse extends Migration
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
            /*
            $table->unsignedBigInteger('foreman_id');

            $table->foreign('foreman_id')
                    ->references('id')
                    ->on('employees');
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
        Schema::table('warehouse', function (Blueprint $table) {
            //
            /*
            $table->dropForeign(['foreman_id']);
            $table->dropColumn('foreman_id');
            */
        });
    }
}
