<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWarehouseTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('warehouse', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('address');
            $table->string('city');
            $table->string('phone_number');
            $table->string('email');
            $table->json('workers')->nullable();
            $table->timestamps();
            /*
            $table->integer('company_id')->unsigned();

            $table->foreign('company_id')
                    ->references('id')
                    ->on('company')
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
        Schema::dropIfExists('warehouse');
    }
}
