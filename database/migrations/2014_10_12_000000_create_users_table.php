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
      $table->string('username', 16)->unique();
      $table->string('email', 50)->unique();
      $table->string('password', 50);
      $table->integer('role');
      $table->integer('vote');
      $table->float('money');
      $table->ipAddress('ip');
      $table->boolean('skin');
      $table->boolean('cape');
      $table->rememberToken();
      $table->timestamps();
    });

    // Login security
    Schema::create('users_login_retries', function (Blueprint $table) {
      $table->increments('id');
      $table->ipAddress('ip');
      $table->integer('count');
      $table->timestamps();
    });

    // TwoFactorAuth
    Schema::create('users_two_factor_auth_secrets', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id');
      //$table->foreign('user_id')->references('id')->on('users');
      $table->string('secret', 20);
      $table->boolean('enabled');
    });

    // Log
    Schema::create('users_connection_logs', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id');
      //$table->foreign('user_id')->references('id')->on('users');
      $table->ipAddress('ip');
      $table->timestamps();
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
    Schema::dropIfExists('users_login_retries');
    Schema::dropIfExists('users_two_factor_auth_secrets');
    Schema::dropIfExists('users_connection_logs');
  }
}
