<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacultyLoadMonitoringsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faculty_load_monitorings', function (Blueprint $table) {
            $table->id();

            $table->integer('faculty_load_id')->nullable();
            $table->integer('status_id')->nullable();
            $table->longText('remarks')->nullable();
            $table->integer('update_status_id')->nullable();
            $table->longText('update_remarks')->nullable();
            $table->integer('rate_id')->nullable();
            $table->string('rate', 30)->nullable();

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
        Schema::dropIfExists('faculty_load_monitorings');
    }
}
