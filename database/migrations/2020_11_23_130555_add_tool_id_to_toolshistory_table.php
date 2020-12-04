<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddToolIdToToolshistoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('toolshistory', function (Blueprint $table) {
            //
            $table->unsignedBigInteger('tool_id');

            $table->foreign('tool_id')
                  ->references('id')
                  ->on('tools')
                  ->onUpdate('cascade')
                  ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('toolshistory', function (Blueprint $table) {
            //
            $table->dropForeign(['tool_id']);
            $table->dropColumn('tool_id');
        });
    }
}
