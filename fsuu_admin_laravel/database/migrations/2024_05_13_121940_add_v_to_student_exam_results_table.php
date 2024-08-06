<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddVToStudentExamResultsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_exam_results', function (Blueprint $table) {
            // Graduate of Studies Exam Result
            $table->string('v')->nullable()->after('vd');
            $table->string('q')->nullable()->after('v');
            $table->string('ir')->nullable()->after('q');
            $table->string('ss')->nullable()->after('ir');
            $table->string('pr')->nullable()->after('ss');

            // College of Law
            $table->string('ct')->nullable()->after('pr');
            $table->string('va')->nullable()->after('ct');
            $table->string('qa')->nullable()->after('va');
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
            $table->dropColumn("v");
            $table->dropColumn("q");
            $table->dropColumn("ir");
            $table->dropColumn("ss");
            $table->dropColumn("pr");

            $table->dropColumn("ct");
            $table->dropColumn("va");
            $table->dropColumn("qa");
        });
    }
}
