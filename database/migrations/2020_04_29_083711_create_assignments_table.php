<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assignments', function (Blueprint $table) {
            $table->id('id');
            $table->string('assignment_code', 255);
            $table->enum('assignment_type', ['import_assignment', 'export_assignment']);  //Was: $table->string('type', 6); //import or export
            $table->string('assigner', 255);  //eg Lidl.
            $table->dateTime('time_date_assigned');
            $table->timestamps();

            /*
            $table->integer('import_id')->unsigned();

            $table->foreign('import_id')
                    ->references('id')
                    ->on('imports')
                    ->onDelete('cascade');


            $table->integer('export_id')->unsigned();

            $table->foreign('export_id')
                    ->references('id')
                    ->on('exports')
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
        Schema::dropIfExists('assignments');
    }
}
