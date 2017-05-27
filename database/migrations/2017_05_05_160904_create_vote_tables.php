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
    }
}
