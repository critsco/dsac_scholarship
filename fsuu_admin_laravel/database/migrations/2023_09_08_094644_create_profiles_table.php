<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfilesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profiles', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->nullable();
            $table->string('school_id')->nullable();
            $table->string('firstname', 100)->nullable();
            $table->string('middlename', 100)->nullable();
            $table->string('lastname', 100)->nullable();
            $table->string('name_ext', 50)->nullable();
            $table->string('gender')->nullable();
            $table->date('birthdate')->nullable();
            $table->longText('birthplace')->nullable();
            $table->integer('age')->nullable();
            $table->integer('civil_status_id')->nullable();
            $table->integer('nationality_id')->nullable();
            $table->integer('religion_id')->nullable();
            $table->string('weight', 20)->nullable();
            $table->string('height', 20)->nullable();
            $table->string('height_unit', 10)->nullable();
            $table->string('blood_type')->nullable();

            $table->integer('number_of_brothers')->nullable();
            $table->integer('number_of_sisters')->nullable();

            $table->string('folder_name')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->integer('deactivated_by')->nullable();
            $table->timestamps();
            $table->dateTime("deactivated_at")->nullable();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profiles');
    }
}