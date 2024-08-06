<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileBenificiariesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_benificiaries', function (Blueprint $table) {
            $table->id();

            $table->integer('profile_id')->nullable();
            $table->string('fullname')->nullable();
            $table->date('birthdate')->nullable();
            $table->string('contact_number', 30)->nullable();
            $table->string('relationship')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();

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
        Schema::dropIfExists('profile_benificiaries');
    }
}
