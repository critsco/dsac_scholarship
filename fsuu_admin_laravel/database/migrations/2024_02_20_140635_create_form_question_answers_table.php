<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormQuestionAnswersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_question_answers', function (Blueprint $table) {
            $table->id();

            $table->integer('user_id')->nullable();
            $table->integer('form_id')->nullable();
            $table->integer('form_question_category_id')->nullable();
            $table->integer('form_question_id')->nullable();
            $table->string('answer')->nullable();
            $table->dateTime('date_start_answer')->nullable();
            $table->dateTime('date_end_answer')->nullable();

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
        Schema::dropIfExists('form_question_answers');
    }
}
