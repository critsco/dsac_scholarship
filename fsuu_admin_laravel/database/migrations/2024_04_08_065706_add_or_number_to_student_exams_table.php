<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOrNumberToStudentExamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_exams', function (Blueprint $table) {
            $table->string('or_number')->nullable()->after('schedule_status');
            $table->string('exam_category')->nullable()->after('or_number');
            $table->string('exam_fee')->nullable()->after('exam_category');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_exams', function (Blueprint $table) {
            $table->dropColumn("or_number");
            $table->dropColumn("exam_category");
            $table->dropColumn("exam_fee");
        });
    }
}
