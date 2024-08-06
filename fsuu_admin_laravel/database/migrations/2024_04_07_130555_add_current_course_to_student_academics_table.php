<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCurrentCourseToStudentAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_academics', function (Blueprint $table) {
            $table->string('current_course')->nullable()->after('student_strand');
            $table->string('year_accepted')->nullable()->after('accepted_to_fsuu');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_academics', function (Blueprint $table) {
            $table->dropColumn("year_accepted");
            $table->dropColumn("current_course");
        });
    }
}
