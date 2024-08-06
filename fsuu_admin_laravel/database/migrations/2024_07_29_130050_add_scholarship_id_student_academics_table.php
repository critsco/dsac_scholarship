<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddScholarshipIdStudentAcademicsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('student_academics', function (Blueprint $table) {
            $table->string('apply_scholarship')->nullable()->after('category');
            $table->string('scholarship_id')->nullable()->after('apply_scholarship');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('student_academics', function (Blueprint $table) {
            $table->dropColumn('apply_scholarship');
            $table->dropColumn('scholarship_id');
        });
    }
}
