<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveCurrentCourseToStudentAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_academics', function (Blueprint $table) {
            $table->dropColumn('current_course');
            $table->dropColumn('first_course');
            $table->dropColumn('second_course');
            $table->dropColumn('third_course');
            $table->integer('current_course_id')->after('student_strand')->nullable();
            $table->integer('first_course_id')->after('current_course_id')->nullable();
            $table->integer('second_course_id')->after('first_course_id')->nullable();
            $table->integer('third_course_id')->after('second_course_id')->nullable();
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
            $table->dropColumn('current_course_id');
            $table->dropColumn('first_course_id');
            $table->dropColumn('second_course_id');
            $table->dropColumn('third_course_id');
            $table->string('current_course')->nullable();
            $table->string('first_course')->nullable();
            $table->string('second_course')->nullable();
            $table->string('third_course')->nullable();
        });
    }
}
