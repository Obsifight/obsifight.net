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
        $table->dateTime('deleted_at')->nullable(); // expire date
        $table->timestamps();
      });
      Schema::create('shop_items', function (Blueprint $table) {
        $table->increments('id');
        $table->string('name', 150);
        $table->text('description');
        $table->integer('category_id')->unsigned();
        $table->foreign('category_id')->references('id')->on('shop_categories');
        $table->float('price');
        $table->boolean('displayed')->default(0);
        $table->text('commands')->nullable()->default(null);
        $table->string('image_path')->nullable()->default(null);
        $table->boolean('need_connected')->default(true);
        $table->dateTime('deleted_at')->nullable(); // expire date
        $table->timestamps();
      });
      Schema::create('shop_items_abilities', function (Blueprint $table) {
        $table->increments('id');
        $table->string('model'); // model name
        $table->integer('item_id')->unsigned();
        $table->foreign('item_id')->references('id')->on('shop_items');
        $table->integer('condition_max')->unsigned()->nullable()->default(null);
        $table->timestamps();
      });
      Schema::create('shop_ranks', function (Blueprint $table) {
        $table->increments('id');
        $table->text('advantages');
        $table->string('slug', 30);
        $table->integer('item_id')->unsigned();
        $table->foreign('item_id')->references('id')->on('shop_items');
        $table->dateTime('deleted_at')->nullable(); // expire date
        $table->timestamps();
      });
      Schema::create('shop_items_purchase_histories', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users');
        $table->integer('item_id')->unsigned();
        $table->foreign('item_id')->references('id')->on('shop_items');
        $table->integer('quantity')->unsigned()->default(1);
        $table->ipAddress('ip');
        $table->timestamps();
      });
      Schema::create('shop_sales', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('product_id')->unsigned()->nullable()->default(null);
        $table->string('product_type', 8); // ITEM or CATEGORY
        $table->float('reduction'); // percentage
        $table->dateTime('deleted_at')->nullable(); // expire date
        $table->timestamps();
      });
      Schema::create('shop_sale_histories', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users');
        $table->integer('item_id')->unsigned();
        $table->foreign('item_id')->references('id')->on('shop_items');
        $table->integer('sale_id')->unsigned();
        $table->foreign('sale_id')->references('id')->on('shop_sales');
        $table->integer('history_id')->unsigned();
        $table->foreign('history_id')->references('id')->on('shop_items_purchase_histories');
        $table->float('reduction');
        $table->timestamps();
      });
      Schema::create('shop_credit_histories', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users');
        $table->float('money');
        $table->float('amount');
        $table->string('transaction_type', 11);
        $table->integer('transaction_id')->unsigned();
        $table->timestamps();
      });
      Schema::create('shop_credit_dedipass_histories', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('history_id')->unsigned()->nullable()->default(NULL);
        $table->foreign('history_id')->references('id')->on('shop_credit_histories');
        $table->float('payout');
        $table->string('code');
        $table->string('rate');
        $table->timestamps();
      });
      Schema::create('shop_credit_paypal_histories', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('history_id')->unsigned()->nullable()->default(NULL);
        $table->foreign('history_id')->references('id')->on('shop_credit_histories');
        $table->float('payment_amount');
        $table->float('payment_tax');
        $table->string('payment_id');
        $table->string('buyer_email');
        $table->string('status', 10);
        $table->dateTime('payment_date');
        $table->dateTime('case_date')->nullable()->default(NULL);
        $table->timestamps();
      });
      Schema::create('shop_credit_hipay_histories', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('history_id')->unsigned()->nullable()->default(NULL);
        $table->foreign('history_id')->references('id')->on('shop_credit_histories');
        $table->float('payment_amount');
        $table->string('payment_id');
        $table->timestamps();
      });
      Schema::create('shop_credit_paysafecard_histories', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('history_id')->unsigned()->nullable()->default(NULL);
        $table->foreign('history_id')->references('id')->on('shop_credit_histories');
        $table->float('payment_amount');
        $table->string('payment_id');
        $table->timestamps();
      });
      Schema::create('shop_vouchers', function (Blueprint $table) {
        $table->increments('id');
        $table->string('code', 50);
        $table->float('money');
        $table->timestamps();
      });

      Schema::create('shop_vouchers_histories', function (Blueprint $table) {
        $table->increments('id');
        $table->integer('user_id')->unsigned();
        $table->foreign('user_id')->references('id')->on('users');
        $table->integer('voucher_id')->unsigned();
        $table->foreign('voucher_id')->references('id')->on('shop_vouchers');
        $table->integer('history_id')->unsigned()->nullable()->default(null);
        $table->foreign('history_id')->references('id')->on('shop_credit_histories');
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
      Schema::dropIfExists('shop_items_abilities');
      Schema::dropIfExists('shop_ranks');
      Schema::dropIfExists('shop_items_purchase_histories');
      Schema::dropIfExists('shop_sales');
      Schema::dropIfExists('shop_sale_histories');
      Schema::dropIfExists('shop_credit_histories');
      Schema::dropIfExists('shop_credit_dedipass_histories');
      Schema::dropIfExists('shop_credit_paypal_histories');
      Schema::dropIfExists('shop_credit_hipay_histories');
      Schema::dropIfExists('shop_credit_paysafecard_histories');
      Schema::dropIfExists('shop_vouchers');
      Schema::dropIfExists('shop_vouchers_histories');
    }
}
