<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCategoryFromTableProfileParentInformations extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_parent_informations', function (Blueprint $table) {
            $table->string('category')->nullable()->after("profile_id");
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
            $table->dropColumn('category');
        });
    }
}
