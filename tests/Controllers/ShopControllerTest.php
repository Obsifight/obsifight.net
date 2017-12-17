<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class ShopControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        \Artisan::call('db:seed', ['--class' => 'PermissionsTablesSeeder']);
        \Artisan::call('db:seed', ['--class' => 'TestingShopTablesSeeder']);
    }

    public function testBuyNotLogged()
    {
        $response = $this->call('POST', '/shop/buy', []);
        $response->assertStatus(302);
    }
    public function testBuyWithoutPermission()
    {
        $user = \App\User::find(3);
        $this->be($user);

        $response = $this->call('POST', '/shop/buy', []);
        $response->assertStatus(403);
    }

    public function testBuyNoItem()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->call('POST', '/shop/buy');
        $response->assertStatus(400);
    }

    public function testBuyItemWithoutId()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->call('POST', '/shop/buy', ['item' => []]);
        $response->assertStatus(400);
    }

    public function testBuyItemNotFound()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->call('POST', '/shop/buy', ['item' => ['id' => 99]]);
        $response->assertStatus(404);
    }

    public function testBuyWithoutEnoughMoney()
    {
        $user = \App\User::find(1);
        $user->money = 0;
        $user->save();
        $this->be($user);

        $response = $this->call('POST', '/shop/buy', ['item' => ['id' => 1]]);
        $response->assertStatus(200);
        $response->assertJson(['status' => false, 'error' => __('shop.buy.error.price')]);
    }

    public function testBuyWithoutBeOnline()
    {
        if (!class_exists('Server'))
            require base_path('vendor/eywek/obsifight/Server/MineWebServer.class.php');
        $server = $this->getMockBuilder(\Methods::class)
            ->setMethods(['isConnected', 'get'])
            ->disableOriginalConstructor()
            ->getMock();
        $server->expects($this->once())
            ->method('get')
            ->willReturn(['isConnected' => false]);
        $server->expects($this->once())
            ->method('isConnected')
            ->willReturn($server);
        $this->app->instance('\Server', $server);

        $user = \App\User::find(1);
        $user->money = 200;
        $user->save();
        $this->be($user);

        $item = \App\ShopItem::find(1);
        $item->need_connected = true;
        $item->commands = ['say coucou'];
        $item->save();

        $response = $this->call('POST', '/shop/buy', ['item' => ['id' => 1]]);
        $response->assertStatus(200);
        $response->assertJson(['status' => false, 'error' => __('shop.buy.error.server.connected')]);
    }

    public function testBuy()
    {
        if (!class_exists('Server'))
            require base_path('vendor/eywek/obsifight/Server/MineWebServer.class.php');
        $server = $this->getMockBuilder(\Methods::class)
            ->setMethods(['isConnected', 'sendCommand', 'get'])
            ->disableOriginalConstructor()
            ->getMock();
        $server->method('get')
            ->willReturn(['isConnected' => true]);
        $server->expects($this->once())
            ->method('isConnected')
            ->willReturn($server);
        $server->expects($this->exactly(2))
            ->method('sendCommand')
            ->willReturn($server);
        $this->app->instance('\Server', $server);

        $user = \App\User::find(1);
        $user->money = 200;
        $user->save();
        $this->be($user);

        $item = \App\ShopItem::find(1);
        $item->need_connected = true;
        $item->commands = ['say coucou'];
        $item->save();

        $item = \App\ShopItem::find(1);
        $response = $this->call('POST', '/shop/buy', ['item' => ['id' => 1]]);
        $response->assertStatus(200);
        $response->assertJson(['status' => true, 'success' => __('shop.buy.success', ['item_name' => $item->name, 'price' => floatval($item->price)])]);
        $this->assertEquals($user->money - floatval($item->price), \App\User::find(1)->money);
    }

    public function testBuyMultipleQuantity()
    {
        if (!class_exists('Server'))
            require base_path('vendor/eywek/obsifight/Server/MineWebServer.class.php');
        $server = $this->getMockBuilder(\Methods::class)
            ->setMethods(['isConnected', 'sendCommand', 'get'])
            ->disableOriginalConstructor()
            ->getMock();
        $server->method('get')
            ->willReturn(['isConnected' => true]);
        $server->expects($this->once())
            ->method('isConnected')
            ->willReturn($server);
        $server->expects($this->exactly(3))
            ->method('sendCommand')
            ->willReturn($server);
        $this->app->instance('\Server', $server);

        $user = \App\User::find(1);
        $user->money = 200;
        $user->save();
        $this->be($user);

        $item = \App\ShopItem::find(1);
        $item->need_connected = true;
        $item->commands = ['say coucou'];
        $item->save();

        $item = \App\ShopItem::find(1);
        $response = $this->call('POST', '/shop/buy', ['item' => ['id' => 1, 'quantity' => 2]]);
        $response->assertStatus(200);
        $response->assertJson(['status' => true, 'success' => __('shop.buy.success', ['item_name' => $item->name, 'price' => floatval($item->price) * 2])]);
        $this->assertEquals($user->money - floatval($item->price) * 2, \App\User::find(1)->money);
    }
}