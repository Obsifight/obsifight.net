<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('shop_categories', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 50);
        $table->integer('order')->unsigned();
        $table->boolean('displayed')->default(0);
        $table->timestamps();
      });
      Schema::create('shop_items', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 150);
        $table->text('content');
        $table->integer('category_id')->unsigned();
        $table->foreign('category_id')->references('id')->on('shop_categories');
        $table->float('price');
        $table->boolean('displayed')->default(0);
        $table->string('image_path')->nullable()->default(null);
        $table->timestamps();
      });
      Schema::create('shop_ranks', function (Blueprint $table) {
        $table->increments('id');
        $table->text('advantages');
        $table->integer('item_id')->unsigned();
        $table->foreign('item_id')->references('id')->on('shop_items');
        $table->timestamps();
      });
      Schema::create('shop_items_purchase_histories', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users');
        $table->integer('item_id')->unsigned();
        $table->foreign('item_id')->references('id')->on('shop_items');
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
      Schema::dropIfExists('shop_categories');
      Schema::dropIfExists('shop_items');
      Schema::dropIfExists('shop_ranks');
      Schema::dropIfExists('shop_items_purchase_histories');
    }
}
