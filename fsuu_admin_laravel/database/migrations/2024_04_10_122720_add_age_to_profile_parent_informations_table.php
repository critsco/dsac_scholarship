<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddAgeToProfileParentInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_parent_informations', function (Blueprint $table) {
            $table->date('birthdate')->nullable()->after('name_ext');
            $table->integer('age')->nullable()->after('birthdate');
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
        Schema::table('profile_parent_informations', function (Blueprint $table) {
            $table->dropColumn('birthdate');
            $table->dropColumn('age');
            $table->dropColumn('address');
        });
    }
}
