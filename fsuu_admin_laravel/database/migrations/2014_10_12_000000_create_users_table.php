<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username')->unique();
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password')->nullable();
            $table->integer('user_role_id')->nullable();
            $table->string('status')->nullable();

            $table->tinyInteger('one_time_update_info')->default(0)->nullable();
            $table->tinyInteger('google2fa_enable')->default(0)->nullable();
            $table->string('google2fa_secret')->nullable();

            $table->rememberToken();

            $table->integer('created_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('deactivated_by')->nullable();
            $table->timestamps();
            $table->dateTime('deactivated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
}
