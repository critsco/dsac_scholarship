<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ChangeCategoryFromRefScholarshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_scholarships', function (Blueprint $table) {
            // change column type from string to integer
            $table->integer('category_id')->change();
            $table->integer('benefits_id')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ref_scholarship', function (Blueprint $table) {
            //
        });
    }
}
