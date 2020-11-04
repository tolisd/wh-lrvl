<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImportassignmentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('importassignments', function (Blueprint $table) {
            $table->id();
            $table->int('import_assignment_code')->unique();
            $table->mediumText('import_assignment_text');
            $table->dateTime('import_deadline');
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
        Schema::dropIfExists('importassignments');
    }
}
