<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCompanyIdToExports extends Migration
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
            $table->unsignedBigInteger('company_id');

            $table->foreign('company_id')
                    ->references('id')
                    ->on('company')
                    ->onDelete('cascade')
                    ->onUpdate('cascade');
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
            $table->dropForeign(['company_id']);
            $table->dropColumn('company_id');
        });
    }
}
