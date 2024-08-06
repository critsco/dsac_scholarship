<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateGradeFilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('grade_files', function (Blueprint $table) {
            $table->id();

            $table->integer("faculty_load_id")->nullable();
            $table->longText("description")->nullable();
            $table->string("status")->default("Pending");
            $table->longText("remarks")->nullable();

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
        Schema::dropIfExists('grade_files');
    }
}
