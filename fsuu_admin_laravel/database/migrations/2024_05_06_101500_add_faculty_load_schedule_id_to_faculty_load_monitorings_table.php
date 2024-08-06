<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFacultyLoadScheduleIdToFacultyLoadMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('faculty_load_monitorings', function (Blueprint $table) {
            $table->integer('faculty_load_schedule_id')->after('faculty_load_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('faculty_load_monitorings', function (Blueprint $table) {
            $table->dropColumn('faculty_load_schedule_id');
        });
    }
}