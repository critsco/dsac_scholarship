<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileChildrensTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_childrens', function (Blueprint $table) {
            $table->id();

            $table->integer('profile_id')->nullable();
            $table->integer('spouse_id')->nullable();

            $table->string('fullname')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('gender')->nullable();
            $table->longText('education_attainment')->nullable();

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
        Schema::dropIfExists('profile_childrens');
    }
}
