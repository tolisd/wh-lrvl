<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddImportassignmentIdToImports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('imports', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('importassignment_id'); //->unique();

            $table->foreign('importassignment_id')
                    ->references('id')
                    ->on('importassignments');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('imports', function (Blueprint $table) {
            //
            $table->dropForeign(['importassignment_id']);
            $table->dropColumn('importassignment_id');
        });
    }
}
