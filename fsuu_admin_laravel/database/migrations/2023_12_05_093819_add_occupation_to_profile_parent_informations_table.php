<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddOccupationToProfileParentInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_parent_informations', function (Blueprint $table) {
            $table->string('occupation')->nullable()->after('contact_number');
            $table->string('relation')->nullable()->after('occupation');
            $table->string('status')->nullable()->after('relation');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_parent_informations', function (Blueprint $table) {
            $table->dropColumn('occupation');
            $table->dropColumn('relation');
            $table->dropColumn('status');
        });
    }
}
