<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

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
            $table->increments('id');
            $table->string('user_id')->unique();
            $table->string('user_name')->unique();
            $table->string('password');
            $table->integer('ps_id')->unique()->nullable(true);
            $table->integer('agency_id')->unique()->nullable(true);
            $table->integer('court_id')->unique()->nullable(true);
            $table->integer('district_id')->unique()->nullable(true);
            $table->string('email')->unique()->nullable(true);
            $table->string('contact_no')->nullable(true);
            $table->timestamp('email_verified_at')->nullable(true);            
            $table->string('user_type');
            $table->rememberToken();
            $table->timestamps();
            $table->timestamp('login_at')->nullable(true);

            $table->foreign('ps_id')->references('ps_id')->on('ps_details');
            $table->foreign('agency_id')->references('agency_id')->on('agency_details');
            $table->foreign('court_id')->references('court_id')->on('court_details');
            $table->foreign('district_id')->references('district_id')->on('districts');
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
