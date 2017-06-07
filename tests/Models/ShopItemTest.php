<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\ShopItem;

class ShopItemTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingShopTablesSeeder']);
  }

  public function testGetItemWithNoReduction()
  {
    $item = ShopItem::find(1);
    $this->assertEquals([], $item->sales->toArray());
    $this->assertEquals(0, $item->reduction);
    $this->assertEquals(100, $item->priceWithReduction);
  }

  public function testGetItemWithItemReduction()
  {
    \DB::table('shop_sales')->truncate();
    $sale = [
      'id' => 1,
      'product_id' => 1,
      'product_type' => 'ITEM',
      'reduction' => 10,
      'deleted_at' => NULL,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ];
    \DB::table('shop_sales')->insert($sale);

    $item = ShopItem::find(1);
    $this->assertEquals([$sale], $item->sales->toArray());
    $this->assertEquals(10, $item->reduction);
    $this->assertEquals(90, $item->priceWithReduction);
  }

  public function testGetItemWithCategoryReduction()
  {
    \DB::table('shop_sales')->truncate();
    $sale = [
      'id' => 1,
      'product_id' => 1,
      'product_type' => 'CATEGORY',
      'reduction' => 10,
      'deleted_at' => NULL,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ];
    \DB::table('shop_sales')->insert($sale);

    $item = ShopItem::find(1);
    $this->assertEquals([$sale], $item->sales->toArray());
    $this->assertEquals(10, $item->reduction);
    $this->assertEquals(90, $item->priceWithReduction);
  }

  public function testGetItemWithAllReduction()
  {
    \DB::table('shop_sales')->truncate();
    $sale = [
      'id' => 1,
      'product_id' => NULL,
      'product_type' => 'ALL',
      'reduction' => 10,
      'deleted_at' => NULL,
      'created_at' => date('Y-m-d H:i:s'),
      'updated_at' => date('Y-m-d H:i:s')
    ];
    \DB::table('shop_sales')->insert($sale);

    $item = ShopItem::find(1);
    $this->assertEquals([$sale], $item->sales->toArray());
    $this->assertEquals(10, $item->reduction);
    $this->assertEquals(90, $item->priceWithReduction);
  }
}
