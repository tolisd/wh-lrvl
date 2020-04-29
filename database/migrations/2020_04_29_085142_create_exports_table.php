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
            $table->id('export_id');
            $table->string('export_delivery_manager_name', 255);
            $table->string('export_delivery_company', 255);
            $table->dateTime('export_delivered_on');
            $table->string('export_shipment_address', 255);
            $table->string('export_destination_address', 255);
            $table->string('export_item_description', 255);
            $table->float('export_hours_worked');
            $table->float('export_chargeable_hours_worked');
            $table->string('export_shipping_company', 255);
            $table->string('export_shipment_bulletin', 255);
            $table->string('export_vehicle_plate_reg_no', 20);
            $table->timestamps();
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
