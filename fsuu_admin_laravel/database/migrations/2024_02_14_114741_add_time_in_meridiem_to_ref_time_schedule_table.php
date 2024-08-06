<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTimeInMeridiemToRefTimeScheduleTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_time_schedules', function (Blueprint $table) {
            $table->dropColumn("meridiem");
            $table->string("time_in_meridiem")->after("time_in")->nullable();
            $table->string("time_out_meridiem")->after("time_out")->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_time_schedules', function (Blueprint $table) {
            $table->dropColumn("time_in_meridiem");
            $table->dropColumn("time_out_meridiem");
        });
    }
}
