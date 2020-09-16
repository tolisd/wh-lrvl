<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exports', function (Blueprint $table) {
            $table->id('id');
            //$table->string('delivery_manager_name', 255);
            //$table->string('delivery_company', 255);
            $table->dateTime('delivered_on');
            $table->string('shipment_address', 255);
            $table->string('destination_address', 255);
            $table->string('item_description', 255);
            $table->float('hours_worked');
            $table->float('chargeable_hours_worked');
            $table->string('shipping_company', 255);
            $table->string('shipment_bulletin', 255);
            $table->string('vehicle_reg_no', 20);
            $table->timestamps();

            /*
            $table->integer('product_id')->unsigned();

            $table->foreign('product_id')
                    ->references('id')
                    ->on('products')
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
        Schema::dropIfExists('exports');
    }
}
