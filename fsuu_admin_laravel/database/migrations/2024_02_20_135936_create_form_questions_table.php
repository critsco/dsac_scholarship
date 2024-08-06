<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFormQuestionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('form_questions', function (Blueprint $table) {
            $table->id();

            $table->unsignedInteger('form_question_category_id')->nullable();
            $table->integer('question_code')->nullable();
            $table->string('option_label')->nullable();
            $table->longText('question');
            $table->longText('question_tips')->nullable();
            $table->longText('description')->nullable();
            $table->string('question_type');
            $table->tinyInteger('with_attachment')->default(0)->nullable();
            $table->tinyInteger('required')->default(0)->nullable();
            $table->tinyInteger('max_checkbox')->default(0)->nullable();
            $table->integer('order_no')->nullable();
            $table->boolean('status')->default(0)->nullable();

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
        Schema::dropIfExists('form_questions');
    }
}
