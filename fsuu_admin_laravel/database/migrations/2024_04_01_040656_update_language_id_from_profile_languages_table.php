<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateLanguageIdFromProfileLanguagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_languages', function (Blueprint $table) {
            $table->renameColumn('language_id', 'language');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_languages', function (Blueprint $table) {
            $table->renameColumn('language', 'language_id');
        });
    }
}
