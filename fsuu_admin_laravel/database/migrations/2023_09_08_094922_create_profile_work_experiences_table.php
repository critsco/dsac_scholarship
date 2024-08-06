<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileWorkExperiencesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_work_experiences', function (Blueprint $table) {
            $table->id();

            $table->integer('profile_id')->nullable();
            $table->string('employer_name')->nullable();
            $table->string('govt_service')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('position_id')->nullable();
            $table->longText('description')->nullable();
            $table->string('industry')->nullable();
            $table->longText('address')->nullable();
            $table->string('monthly_salary', 50)->nullable();
            $table->string('salary', 50)->nullable();

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
        Schema::dropIfExists('profile_work_experiences');
    }
}
