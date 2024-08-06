<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyLoadMonitoringJustificationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_load_monitoring_justifications', function (Blueprint $table) {
            $table->id();

            $table->integer('faculty_load_monitoring_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->longText('remarks')->nullable();
            $table->longText('remarks2')->nullable();
            $table->integer('approved_by')->nullable();
            $table->dateTime('date_approved')->nullable();

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
        Schema::dropIfExists('faculty_load_monitoring_justifications');
    }
}
