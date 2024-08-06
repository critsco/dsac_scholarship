<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameCategoryFromRefScholarshipTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ref_scholarships', function (Blueprint $table) {
            /// rename column
            $table->renameColumn('category', 'category_id');
            $table->renameColumn('benefits', 'benefits_id');
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
