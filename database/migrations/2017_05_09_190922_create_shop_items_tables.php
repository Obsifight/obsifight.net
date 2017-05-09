<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateShopItemsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('shop_items', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 30);
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
      Schema::dropIfExists('shop_items');
      Schema::dropIfExists('shop_items_purchase_histories');
    }
}
