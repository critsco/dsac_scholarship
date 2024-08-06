<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCrsScaledScoreToStudentExamResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_exam_results', function (Blueprint $table) {
            $table->string('crs_scaled_score')->after('sc_quality_index')->nullable();
            $table->string('crs_percentile_rank')->after('crs_scaled_score')->nullable();
            $table->string('crs_stanine')->after('crs_percentile_rank')->nullable();
            $table->string('crs_quality_index')->after('crs_stanine')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_exam_results', function (Blueprint $table) {
            $table->dropColumn('crs_scaled_score');
            $table->dropColumn('crs_percentile_rank');
            $table->dropColumn('crs_stanine');
            $table->dropColumn('crs_quality_index');
        });
    }
}