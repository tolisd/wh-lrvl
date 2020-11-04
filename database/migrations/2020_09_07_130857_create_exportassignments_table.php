<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateExportassignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('exportassignments', function (Blueprint $table) {
            $table->id();
            $table->int('export_assignment_code')->unique();
            $table->mediumText('export_assignment_text');
            $table->dateTime('export_deadline');
            $table->mediumText('comments');
            $table->json('uploaded_files')->nullable();
            $table->boolean('is_open');
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
        Schema::dropIfExists('exportassignments');
    }
}
