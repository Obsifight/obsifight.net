<?php
namespace Tests\Feature;

use App\Notification;
use App\ShopCreditDedipassHistory;
use App\ShopCreditHistory;
use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;

class CreditControllerTest extends TestCase
{
    use DatabaseMigrations;

    public function setUp()
    {
        parent::setUp();
        \Artisan::call('db:seed', ['--class' => 'PermissionsTablesSeeder']);
        \Artisan::call('db:seed', ['--class' => 'TestingShopCreditTablesSeeder']);
    }

    public function testDedipassNotificationNotLogged()
    {
        $response = $this->post('/shop/credit/add/dedipass/notification');
        $response->assertStatus(302);
    }

    public function testDedipassNotificationWithoutPermission()
    {
        $user = \App\User::find(2);
        $this->be($user);

        $response = $this->post('/shop/credit/add/dedipass/notification');
        $response->assertStatus(403);
    }

    public function testDedipassNotificationWithoutPublicKey()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/dedipass/notification');
        $response->assertStatus(400);
    }

    public function testDedipassNotificationWithoutCode()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/dedipass/notification', [
            'public_key' => 'invalid',
            'rate' => 'FR-SMS-10'
        ]);
        $response->assertStatus(400);
    }

    public function testDedipassNotificationWithoutRate()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/dedipass/notification', [
            'public_key' => 'invalid',
            'code' => '1808ABAB'
        ]);
        $response->assertStatus(400);
    }

    public function testDedipassNotificationWithInvalidPublicKey()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/dedipass/notification', [
            'public_key' => 'invalid',
            'code' => '1808ABAB',
            'rate' => 'FR-SMS-10'
        ]);
        $response->assertStatus(403);
    }

    public function testDedipassNotificationWithInvalidRequest()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $guzzleClient = $this->getMockBuilder(\GuzzleHttp\Client::class)
            ->setMethods(['request', 'getBody'])
            ->getMock();
        $guzzleClient->expects($this->once())
            ->method('request')
            ->willReturn($guzzleClient);
        $guzzleClient->expects($this->once())
            ->method('getBody')
            ->willReturn(false);
        $this->app->instance('\GuzzleHttp\Client', $guzzleClient);

        $response = $this->post('/shop/credit/add/dedipass/notification', [
            'public_key' => 'fake',
            'code' => '1808ABAB',
            'rate' => 'FR-SMS-10'
        ]);
        $response->assertStatus(403);
    }

    public function testDedipassNotificationWithInvalidCode()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $guzzleClient = $this->getMockBuilder(\GuzzleHttp\Client::class)
            ->setMethods(['request', 'getBody'])
            ->getMock();
        $guzzleClient->expects($this->once())
            ->method('request')
            ->willReturn($guzzleClient);
        $guzzleClient->expects($this->once())
            ->method('getBody')
            ->willReturn(json_encode(['status' => 'error']));
        $this->app->instance('\GuzzleHttp\Client', $guzzleClient);

        $response = $this->post('/shop/credit/add/dedipass/notification', [
            'public_key' => 'fake',
            'code' => '1808ABAB',
            'rate' => 'FR-SMS-10'
        ]);
        $response->assertStatus(403);
    }

    public function testDedipassNotificationWithCodeAlreadyUsed()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $guzzleClient = $this->getMockBuilder(\GuzzleHttp\Client::class)
            ->setMethods(['request', 'getBody'])
            ->getMock();
        $guzzleClient->expects($this->once())
            ->method('request')
            ->willReturn($guzzleClient);
        $guzzleClient->expects($this->once())
            ->method('getBody')
            ->willReturn(json_encode(['status' => 'success']));
        $this->app->instance('\GuzzleHttp\Client', $guzzleClient);

        $response = $this->post('/shop/credit/add/dedipass/notification', [
            'public_key' => 'fake',
            'code' => '1808USED',
            'rate' => 'FR-SMS-10'
        ]);
        $response->assertStatus(403);
    }

    public function testDedipassNotification()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $guzzleClient = $this->getMockBuilder(\GuzzleHttp\Client::class)
            ->setMethods(['request', 'getBody'])
            ->getMock();
        $guzzleClient->expects($this->once())
            ->method('request')
            ->willReturn($guzzleClient);
        $guzzleClient->expects($this->once())
            ->method('getBody')
            ->willReturn(json_encode(['status' => 'success', 'virtual_currency' => 200, 'payout' => 2.10]));
        $this->app->instance('\GuzzleHttp\Client', $guzzleClient);

        $response = $this->post('/shop/credit/add/dedipass/notification', [
            'public_key' => 'fake',
            'code' => '1808ABAB',
            'rate' => 'FR-SMS-10'
        ]);
        $response->assertStatus(302);
        $this->assertContains('/user', $response->headers->get('location'));

        // Check history
        $transaction = ShopCreditDedipassHistory::where('code', '1808ABAB')->where('rate', 'FR-SMS-10')->where('payout', 2.10);
        $history = ShopCreditHistory::where('transaction_type', 'DEDIPASS')->where('user_id', 1)->where('money', 200.0)->where('amount', 2.5)->where('transaction_id', $transaction->first()->id);
        $this->assertEquals(1, $transaction->count());
        $this->assertEquals(1, $history->count());
        $this->assertEquals($history->first()->id, $transaction->first()->history_id);

        // Check user money
        $this->assertEquals($user->money + 200, \App\User::find(1)->money);

        // Check notification
        $this->assertEquals(1, Notification::where('user_id', 1)->where('type', 'success')->where('key', 'shop.credit.add.success')->where('vars', json_encode(['money' => 200]))->where('auto_seen', 1)->count());
    }
}