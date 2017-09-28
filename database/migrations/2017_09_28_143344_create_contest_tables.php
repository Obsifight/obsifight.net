<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContestTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contests', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('sanction_id')->unsigned();
            $table->string('sanction_type', 5);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('status', 10);
            $table->text('reason');
            $table->timestamps();
        });
        Schema::create('contests_comments', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contest_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->foreign('contest_id')->references('id')->on('contests');
            $table->foreign('user_id')->references('id')->on('users');
            $table->text('content');
            $table->timestamps();
        });
        Schema::create('contests_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('contest_id')->unsigned();
            $table->foreign('contest_id')->references('id')->on('contests');
            $table->string('action', 6);
            $table->integer('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
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
        Schema::dropIfExists('contests');
        Schema::dropIfExists('contests_comments');
        Schema::dropIfExists('contests_histories');
    }
}
