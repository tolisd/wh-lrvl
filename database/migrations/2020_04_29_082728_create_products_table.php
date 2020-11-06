<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id('id');
            $table->string('code', 255)->unique();
            $table->string('name', 255);
            $table->mediumText('description');
            // $table->float('quantity'); //removed from this table, as it will better go into table product_warehouse, as extra pivot table field.
            //$table->string('meas_unit', 20);
            //$table->enum('measure_unit', ['τμχ', 'm', 'm2', 'm3', 'kg'])->default('τμχ');
            $table->mediumText('comments');
            //$table->boolean('is_charged')->nullable();
            $table->timestamps();

            /*
            $table->integer('category_id')->unsigned();

            $table->foreign('category_id')
                    ->references('id')
                    ->on('category')
                    ->onDelete('cascade');
            */

            /*
            $table->unsignedBigInteger('type_id');

            $table->foreign('type_id')
                    ->references('id')
                    ->on('types')
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
        Schema::dropIfExists('products');
    }
}
