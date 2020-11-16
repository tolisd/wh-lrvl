<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('imports', function (Blueprint $table) {
            $table->id('id');
            //$table->string('recipient', 255);
            $table->dateTime('delivered_on');
            //$table->string('shipping_company', 255);
            $table->string('delivery_address', 255);
            $table->mediumText('discrete_description');
            $table->float('hours_worked');
            $table->float('chargeable_hours_worked');
            $table->string('shipment_bulletin', 255)->nullable();
            //$table->string('shipment_address', 255);
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
        Schema::dropIfExists('imports');
    }
}
