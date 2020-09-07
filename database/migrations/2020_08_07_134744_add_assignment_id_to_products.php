<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAssignmentIdToProducts extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        /*
        Schema::table('products', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('assignment_id');

            $table->foreign('assignment_id')
                    ->references('id')
                    ->on('assignments')
                    ->onDelete('cascade');
        });
        */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        /*
        Schema::table('products', function (Blueprint $table) {
            //
            $table->dropForeign(['assignment_id']);
            $table->dropColumn('assignment_id');
        });
        */
    }
}
