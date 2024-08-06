<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProfileOthersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('profile_others', function (Blueprint $table) {
            $table->id();

            $table->string('category')->nullable();
            $table->string('type')->nullable();
            $table->integer('profile_id')->nullable();

            $table->string('title')->nullable();
            $table->date('year')->nullable();

            $table->string('school_attended')->nullable();
            $table->string('exam_rating')->nullable();
            $table->string('source_fund')->nullable();
            $table->string('status')->nullable();
            $table->string('publication')->nullable();
            $table->string('position')->nullable();
            $table->longText('address')->nullable();
            $table->string('purpose')->nullable();
            $table->string('sponsor')->nullable();

            $table->string('designation')->nullable();
            $table->string('contact_number')->nullable();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();

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
        Schema::dropIfExists('profile_others');
    }
}
