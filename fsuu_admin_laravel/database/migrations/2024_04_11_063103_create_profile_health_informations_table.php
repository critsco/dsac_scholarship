<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileHealthInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_health_informations', function (Blueprint $table) {
            $table->id();

            $table->integer('profile_id');

            $table->string('have_disability')->nullable();
            $table->string('disability_type')->nullable();
            $table->string('other_disability')->nullable();
            $table->string('have_difficulty')->nullable();
            $table->string('difficulty_type')->nullable();
            $table->string('other_difficulty')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('profile_health_informations');
    }
}
