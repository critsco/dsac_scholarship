<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddContactNumberToProfileSpousesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_spouses', function (Blueprint $table) {
            $table->string('contact_number')->nullable()->after('fullname');
            $table->string('address')->nullable()->after('contact_number');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_spouses', function (Blueprint $table) {
            $table->dropColumn("contact_number");
            $table->dropColumn("address");
        });
    }
}
