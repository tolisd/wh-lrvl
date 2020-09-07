<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAccountantIdToEmployees extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('employees', function (Blueprint $table) {
            /*
            $table->unsignedBigInteger('accountant_id');

            $table->foreign('accountant_id')
                    ->references('id')
                    ->on('accountant')
                    ->onDelete('cascade');
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
        Schema::table('employees', function (Blueprint $table) {
            /*
            $table->dropForeign(['accountant_id']);
            $table->dropColumn('accountant_id');
            */
        });
    }
}
