<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddCityIdToProfileAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_addresses', function (Blueprint $table) {
            $table->string('category')->after('profile_id')->nullable();

            $table->integer('city_id')->after('category')->nullable();

            $table->string('zip_code')->nullable()->after('address');

            $table->boolean('is_current_address')->default(0)->after('address')->nullable();
            $table->boolean('is_home_address')->default(0)->after('is_current_address')->nullable();
            $table->tinyInteger('status')->default(0)->after('is_home_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_addresses', function (Blueprint $table) {
            $table->dropColumn(['city_id', 'status']);
        });
    }
}
