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
      $table->uuid('uuid')->nullable()->default(NULL);
      $table->string('username', 16)->unique();
      $table->string('email', 50)->unique();
      $table->string('password', 50);
      $table->float('money')->default(0);
      $table->ipAddress('ip');
      $table->boolean('obsiguard_dynamic')->default(0);
      $table->string('access_token')->nullable()->default(NULL);
      $table->string('client_token')->nullable()->default(NULL);
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
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->string('secret', 20);
      $table->boolean('enabled');
      $table->timestamps();
    });

    // Log
    Schema::create('users_connection_logs', function (Blueprint $table) {
      $table->increments('id');
      $table->string('type', 8)->default('WEB');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->ipAddress('ip');
      $table->timestamps();
    });

    // Tokens (lost password / confirmation mail)
    Schema::create('users_tokens', function (Blueprint $table) {
      $table->increments('id');
      $table->string('type', 10);
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->uuid('token');
      $table->string('data', 10)->nullable()->default(null);
      $table->ipAddress('used_ip')->nullable()->default(null);
      $table->timestamps();
    });

    // ObsiGuard
    Schema::create('users_obsiguard_ips', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->ipAddress('ip');
      $table->timestamps();
    });
    Schema::create('users_obsiguard_logs', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->string('type', 10); // ADD / REMOVE / DISABLE / ENABLE
      $table->ipAddress('data')->nullable()->default(null);
      $table->ipAddress('ip');
      $table->timestamps();
    });

    // Email ask
    Schema::create('users_email_edit_requests', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->string('email', 50);
      $table->text('reason');
      $table->ipAddress('ip');
      $table->timestamps();
    });

    // username edit history
    Schema::create('users_edit_username_histories', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->string('old_username', 16);
      $table->string('new_username', 16);
      $table->ipAddress('ip');
      $table->timestamps();
    });

    // username edit ability
    Schema::create('users_edit_username_abilities', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->integer('history_id')->unsigned()->nullable();
      $table->foreign('history_id')->references('id')->on('users_edit_username_histories');
      $table->timestamps();
    });

    // cape ability
    Schema::create('users_edit_cape_abilities', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->timestamps();
    });

    // transfers
    Schema::create('users_transfer_money_histories', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->integer('amount');
      $table->integer('to')->unsigned();
      $table->foreign('to')->references('id')->on('users');
      $table->ipAddress('ip');
      $table->timestamps();
    });

    // youtube
    Schema::create('users_youtube_channels', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->string('channel_id');
      $table->ipAddress('link_ip');
      $table->timestamps();
    });
    Schema::create('users_youtube_channel_videos', function (Blueprint $table) {
      $table->increments('id');
      $table->string('channel_id');
      $table->foreign('channel_id', 'ytchanvid_id_foreign')->references('id')->on('users_youtube_channels');
      $table->string('video_id');
      $table->string('title');
      $table->text('description');
      $table->integer('views_count')->unsigned();
      $table->integer('likes_count')->unsigned();
      $table->string('thumbnail_link');
      $table->datetime('publication_date');
      $table->boolean('eligible')->default(false);
      $table->boolean('payed')->default(false);
      $table->timestamps();
    });
    Schema::create('users_youtube_channel_video_remuneration_histories', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id', 'ytchanvid_remhistory_id_foreign')->references('id')->on('users');
      $table->string('video_id');
      $table->foreign('video_id', 'ytchanvid_remuhistory_id_foreign')->references('id')->on('users_youtube_channel_videos');
      $table->float('remuneration');
      $table->ipAddress('ip');
      $table->timestamps();
    });

    // twitter
    Schema::create('users_twitter_accounts', function (Blueprint $table) {
      $table->increments('id');
      $table->integer('user_id')->unsigned();
      $table->foreign('user_id')->references('id')->on('users');
      $table->string('twitter_id', 50);
      $table->string('screen_name', 50);
      $table->string('access_token');
      $table->string('access_secret');
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
    Schema::dropIfExists('users_tokens');
    Schema::dropIfExists('users_obsiguard_ips');
    Schema::dropIfExists('users_obsiguard_logs');
    Schema::dropIfExists('users_email_edit_requests');
    Schema::dropIfExists('users_edit_username_histories');
    Schema::dropIfExists('users_edit_username_abilities');
    Schema::dropIfExists('users_edit_cape_abilities');
    Schema::dropIfExists('users_transfer_money_histories');
    Schema::dropIfExists('users_youtube_channels');
    Schema::dropIfExists('users_youtube_channel_videos');
    Schema::dropIfExists('users_youtube_channel_video_remuneration_histories');
    Schema::dropIfExists('users_twitter_accounts');
  }
}
