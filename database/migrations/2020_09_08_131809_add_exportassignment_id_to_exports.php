<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExportassignmentIdToExports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('exports', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('exportassignment_id')->unique();

            $table->foreign('exportassignment_id')
                    ->references('id')
                    ->on('exportassignments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('exports', function (Blueprint $table) {
            //
            $table->dropForeign(['exportassignment_id']);
            $table->dropColumn('exportassignment_id');
        });
    }
}
