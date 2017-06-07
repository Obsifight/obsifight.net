<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\ShopSale;

class ShopSaleTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingShopTablesSeeder']);
  }

  public function testGetMessageWithoutSale()
  {
    $messages = ShopSale::getMessage();
    $this->assertEquals([], $messages);
  }

  public function testGetMessageWithItemSale()
  {
    \DB::table('shop_sales')->truncate();
    \DB::table('shop_sales')->insert([
      'product_id' => 1,
      'product_type' => 'ITEM',
      'reduction' => 10,
      'deleted_at' => NULL,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    $messages = ShopSale::getMessage();
    $this->assertEquals([
      __('shop.voucher.item', ['reduction' => '10', 'item_name' => 'TestItem'])
    ], $messages);
  }

  public function testGetMessageWithCategorySale()
  {
    \DB::table('shop_sales')->truncate();
    \DB::table('shop_sales')->insert([
      'product_id' => 1,
      'product_type' => 'CATEGORY',
      'reduction' => 10,
      'deleted_at' => NULL,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    $messages = ShopSale::getMessage();
    $this->assertEquals([
      __('shop.voucher.category', ['reduction' => '10', 'category_name' => 'TestCategory'])
    ], $messages);
  }

  public function testGetMessageWithAllSale()
  {
    \DB::table('shop_sales')->truncate();
    \DB::table('shop_sales')->insert([
      'product_id' => NULL,
      'product_type' => 'ALL',
      'reduction' => 10,
      'deleted_at' => NULL,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    $messages = ShopSale::getMessage();
    $this->assertEquals([
      __('shop.voucher.all', ['reduction' => '10'])
    ], $messages);
  }

  public function testGetMessageWithMultipleSales()
  {
    \DB::table('shop_sales')->truncate();
    \DB::table('shop_sales')->insert([
      'product_id' => NULL,
      'product_type' => 'ALL',
      'reduction' => 10,
      'deleted_at' => NULL,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);
    \DB::table('shop_sales')->insert([
      'product_id' => 1,
      'product_type' => 'ITEM',
      'reduction' => 10,
      'deleted_at' => NULL,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);
    \DB::table('shop_sales')->insert([
      'product_id' => 1,
      'product_type' => 'CATEGORY',
      'reduction' => 10,
      'deleted_at' => NULL,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ]);

    $messages = ShopSale::getMessage();
    $this->assertEquals([
      __('shop.voucher.all', ['reduction' => '10']),
      __('shop.voucher.item', ['reduction' => '10', 'item_name' => 'TestItem']),
      __('shop.voucher.category', ['reduction' => '10', 'category_name' => 'TestCategory'])
    ], $messages);
  }
}
