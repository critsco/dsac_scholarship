<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRefScholarshipsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_scholarships', function (Blueprint $table) {
            $table->id();

            $table->longText('name')->nullable();
            $table->longText('description')->nullable();
            $table->longText('provider')->nullable();
            $table->longText('category')->nullable(); // Civic/Religious Funded Scholarhip
            $table->integer('school_level_id')->nullable(); // Grade 1-12, College, Graduate School
            $table->longText('benefits')->nullable(); // Full Tuition, Partial Tuition, Stipend
            $table->string('start_date')->nullable();
            $table->string('end_date')->nullable();
            $table->integer('status')->nullable(); // Active 0, Inactive 1

            $table->integer('slots')->nullable();
            $table->integer('slots_available')->nullable();

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
        Schema::dropIfExists('ref_scholarships');
    }
}