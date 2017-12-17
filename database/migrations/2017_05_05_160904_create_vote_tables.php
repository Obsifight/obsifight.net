<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVoteTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('vote_rewards', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 40);
        $table->integer('probability')->unsigned();
        $table->text('commands');
        $table->timestamps();
      });

      Schema::create('votes', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users');
        $table->integer('out');
        $table->integer('reward_id')->unsigned();
        $table->foreign('reward_id')->references('id')->on('vote_rewards');
        $table->boolean('reward_getted');
        $table->float('money_earned');
        $table->ipAddress('ip');
        $table->timestamps();
      });

      Schema::create('vote_kits', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 40);
        $table->text('content');
        $table->timestamps();
      });

      Schema::create('vote_user_kits', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users');
        $table->integer('kit_id')->unsigned();
        $table->foreign('kit_id')->references('id')->on('vote_kits');
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
      Schema::dropIfExists('vote_rewards');
      Schema::dropIfExists('votes');
      Schema::dropIfExists('vote_kits');
      Schema::dropIfExists('vote_user_kits');
    }
}
