<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeProfileTrainingCertificatesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('profile_training_certificates', function (Blueprint $table) {
            $table->string('type_of_certificate')->after('date_completion')->nullable();
            $table->string('level_of_certification')->after('type_of_certificate')->nullable();
            $table->date('date_start_covered')->after('level_of_certification')->nullable();
            $table->date('date_end_covered')->after('date_start_covered')->nullable();
            $table->string('files')->after('date_end_covered')->nullable();
            $table->string('type')->after('date_completion')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('profile_training_certificates', function (Blueprint $table) {
            $table->dropColumn('type');
        });
    }
}
