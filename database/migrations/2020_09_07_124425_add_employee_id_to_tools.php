<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddEmployeeIdToTools extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('tools', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('employee_id');

            $table->foreign('employee_id')
                    ->nullable()             //Can be NULL, because not all employees will have tool(s)
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
        Schema::table('tools', function (Blueprint $table) {
            //
            $table->dropForeign(['employee_id']);
            $table->dropColumn('employee_id');
        });
    }
}
