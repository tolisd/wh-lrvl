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
            $table->id('import_id');
            $table->string('import_recipient', 255);
            $table->dateTime('import_delivered_on');
            $table->string('import_shipping_company', 255);
            $table->string('import_delivery_address', 255);
            $table->mediumText('import_discrete_description');
            $table->float('import_hours_worked');
            $table->float('import_chargeable_hours_worked');
            $table->string('import_shipment_bulletin', 255);
            $table->string('import_shipment_address', 255);
            $table->string('import_vehicle_plate_reg_no', 20);
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
        Schema::dropIfExists('imports');
    }
}
