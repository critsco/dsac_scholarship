<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileContactInformationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_contact_informations', function (Blueprint $table) {
            $table->id();

            $table->integer('profile_id')->nullable();
            $table->string('category')->nullable();
            $table->string('contact_number', 50)->nullable();
            $table->string('relation')->nullable();
            $table->string('fullname', 150)->nullable();
            $table->string('email', 150)->nullable();
            $table->longText('address')->nullable();
            $table->longText('occupation')->nullable();
            $table->tinyInteger('status')->default(0)->nullable();

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
        Schema::dropIfExists('profile_contact_informations');
    }
}
