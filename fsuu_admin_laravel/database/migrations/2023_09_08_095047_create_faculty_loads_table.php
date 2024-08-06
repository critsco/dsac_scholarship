<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyLoadsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_loads', function (Blueprint $table) {
            $table->id();

            $table->integer('profile_id')->nullable();
            $table->integer('subject_id')->nullable();
            $table->integer('section_id')->nullable();
            $table->integer('room_id')->nullable();
            $table->integer('semester_id')->nullable();
            $table->integer('school_year_id')->nullable();
            $table->string('time_in', 20)->nullable();
            $table->string('time_out', 20)->nullable();
            $table->integer('day_schedule_id')->nullable();
            $table->string('meridian', 10)->nullable();
            $table->integer('department_id')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faculty_loads');
    }
}
