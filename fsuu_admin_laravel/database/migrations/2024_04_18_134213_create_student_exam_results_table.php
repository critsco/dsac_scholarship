<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStudentExamResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('student_exam_results', function (Blueprint $table) {
            $table->id();

            $table->integer('profile_id')->nullable();
            $table->string('exam_sheet_number')->nullable();
            $table->string('fullname')->nullable();

            $table->string('en_scaled_scrore')->nullable();
            $table->string('en_percentile_rank')->nullable();
            $table->string('en_stanine')->nullable();
            $table->string('en_quality_index')->nullable();

            $table->string('mt_scaled_scrore')->nullable();
            $table->string('mt_percentile_rank')->nullable();
            $table->string('mt_stanine')->nullable();
            $table->string('mt_quality_index')->nullable();

            $table->string('sc_scaled_scrore')->nullable();
            $table->string('sc_percentile_rank')->nullable();
            $table->string('sc_stanine')->nullable();
            $table->string('sc_quality_index')->nullable();

            $table->string('raw')->nullable();
            $table->string('sai')->nullable();
            $table->string('vd')->nullable();

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
        Schema::dropIfExists('student_exam_results');
    }
}
