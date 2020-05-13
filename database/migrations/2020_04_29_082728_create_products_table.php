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
            $table->string('name', 255);
            $table->mediumText('description');
            $table->string('type', 255);            
            $table->float('quantity');
            $table->mediumText('comments');
            $table->timestamps();

            /*
            $table->integer('category_id')->unsigned();

            $table->foreign('category_id')
                    ->references('id')
                    ->on('category')
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
