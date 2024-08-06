<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeInToRefExamSchedulesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_exam_schedules', function (Blueprint $table) {

            $table->string('sy_from')->nullable()->after('id');
            $table->string('sy_to')->nullable()->after('sy_from');
            $table->string('time_in')->nullable()->after('exam_date');
            $table->string('time_in_meridiem')->nullable()->after('time_in');
            $table->string('time_out')->nullable()->after('time_in_meridiem');
            $table->string('time_out_meridiem')->nullable()->after('time_out');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_exam_schedules', function (Blueprint $table) {
            $table->dropColumn("sy_from");
            $table->dropColumn("sy_to");
            $table->dropColumn("time_in");
            $table->dropColumn("time_in_meridiem");
            $table->dropColumn("time_out");
            $table->dropColumn("time_out_meridiem");
        });
    }
}
