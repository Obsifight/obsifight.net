<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
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
    $this->assertEmpty($item->sales->toArray());
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

  public function testBuyWithAbility()
  {
      $user = \App\User::find(1);
      $this->be($user);

      \DB::table('shop_items_abilities')->truncate();
      $ability = [
          'id' => 1,
          'item_id' => 1,
          'model' => 'UsersEditCapeAbility',
          'condition_max' => 0,
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s')
      ];
      \DB::table('shop_items_abilities')->insert($ability);

      $item = ShopItem::find(1);
      $this->assertEquals([true, null], $item->buy());
      $history = \App\ShopItemsPurchaseHistory::find(1);
      $this->assertEquals(1, $history->user_id);
      $this->assertEquals(1, $history->item_id);
      $this->assertEquals(1, $history->quantity);
      $this->assertEquals('127.0.0.1', $history->ip);
      $this->assertEquals(1, \App\UsersEditCapeAbility::where('user_id', 1)->count());
  }

  public function testBuyWithAbilityMaxLimitReached()
  {
      $user = \App\User::find(1);
      $this->be($user);

      \DB::table('shop_items_abilities')->truncate();
      $ability = [
          'id' => 1,
          'item_id' => 1,
          'model' => 'UsersEditCapeAbility',
          'condition_max' => 1,
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s')
      ];
      \DB::table('shop_items_abilities')->insert($ability);
      \DB::table('users_edit_cape_abilities')->truncate();
      $ability = [
          'id' => 1,
          'user_id' => 1,
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s')
      ];
      \DB::table('users_edit_cape_abilities')->insert($ability);

      $item = ShopItem::find(1);
      $this->assertEquals([false, 'ability.max'], $item->buy());
      $history = \App\ShopItemsPurchaseHistory::find(1);
      $this->assertEquals(1, \App\UsersEditCapeAbility::where('user_id', 1)->count());
  }

  public function testBuyWithSale()
  {
      $user = \App\User::find(1);
      $this->be($user);

      \DB::table('shop_sales')->truncate();
      $sale = [
          'id' => 1,
          'product_id' => null,
          'product_type' => 'ALL',
          'reduction' => 10,
          'deleted_at' => null,
          'created_at' => date('Y-m-d H:i:s'),
          'updated_at' => date('Y-m-d H:i:s')
      ];
      \DB::table('shop_sales')->insert($sale);

      $item = ShopItem::find(1);
      $this->assertEquals([true, null], $item->buy());
      $history = \App\ShopItemsPurchaseHistory::find(1);
      $this->assertEquals(1, $history->user_id);
      $this->assertEquals(1, $history->item_id);
      $this->assertEquals(1, $history->quantity);
      $this->assertEquals('127.0.0.1', $history->ip);
      $history = \App\ShopSaleHistory::find(1);
      $this->assertEquals(1, $history->user_id);
      $this->assertEquals(1, $history->item_id);
      $this->assertEquals(1, $history->sale_id);
      $this->assertEquals(1, $history->history_id);
      $this->assertEquals(10, $history->reduction);
  }

  public function testBuy()
  {
      $user = \App\User::find(1);
      $this->be($user);

      $item = ShopItem::find(1);
      $this->assertEquals([true, null], $item->buy());
      $history = \App\ShopItemsPurchaseHistory::find(1);
      $this->assertEquals(1, $history->user_id);
      $this->assertEquals(1, $history->item_id);
      $this->assertEquals(1, $history->quantity);
      $this->assertEquals('127.0.0.1', $history->ip);
  }

  public function testBuyWithQuantity()
  {
      $user = \App\User::find(1);
      $this->be($user);

      $item = ShopItem::find(1);
      $item->quantity = 5;
      $this->assertEquals([true, null], $item->buy());
      $history = \App\ShopItemsPurchaseHistory::find(1);
      $this->assertEquals(1, $history->user_id);
      $this->assertEquals(1, $history->item_id);
      $this->assertEquals(5, $history->quantity);
      $this->assertEquals('127.0.0.1', $history->ip);
  }
}
