<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddMaxSlotsToRefRoomsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_rooms', function (Blueprint $table) {
            $table->integer('max_slot')->nullable()->after('room_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_rooms', function (Blueprint $table) {
            $table->dropColumn('max_slot');
        });
    }
}
