<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileSchoolAttendedsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_school_attendeds', function (Blueprint $table) {
            $table->id();

            $table->integer('profile_id')->nullable();
            $table->string('school_name')->nullable();
            $table->longText('school_address')->nullable();
            $table->string('school_type', 150)->nullable();
            $table->string('year_graduated', 50)->nullable();
            $table->integer('school_level_id')->nullable();

            $table->tinyInteger('status')->default(1)->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->timestamps();
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
        Schema::dropIfExists('profile_school_attendeds');
    }
}
