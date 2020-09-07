<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateEmployeesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('employees', function (Blueprint $table) {
            $table->id('id');
            $table->string('name');
            $table->string('type');
            $table->string('address');
            $table->string('phone_number');
            $table->string('email')->unique();
            $table->string('photo_url')->nullable();
            $table->timestamps();
        });

        /*
        Schema::table('employees', function(Blueprint $table){
            $table->integer('accountant_id')->unsigned();
            $table->foreign('accountant_id')
                    ->references('id')
                    ->on('accountant')
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
        Schema::dropIfExists('employees');
    }
}
