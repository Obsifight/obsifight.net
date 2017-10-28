<?php
namespace Tests\Feature;

use App\Notification;
use App\Role;
use App\ShopCreditDedipassHistory;
use App\ShopCreditHipayHistory;
use App\ShopCreditHistory;
use App\ShopCreditPaypalHistory;
use Illuminate\Support\Facades\App;
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

    private function generateHipayXML(
        $amount = '10.00',
        $currency = 'EUR',
        $userId = 1,
        $key = 'random_key',
        $operation = 'capture',
        $status = 'ok',
        $id = '58DFDA4488963162'
    )
    {
        return "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
        <mapi>
            <mapiversion>1.0</mapiversion>
            <md5content>32b95f8dfd3b4ee5a5b10859209b50c2</md5content>
            <result>
                <operation>$operation</operation>
                <status>$status</status>
                <date>2017-04-01</date>
                <time>16:50:39 UTC+0000</time>
                <origAmount>$amount</origAmount>
                <origCurrency>$currency</origCurrency>
                <idForMerchant>REF1</idForMerchant>
                <emailClient>alexis@marquis.fr.cr</emailClient>
                <idClient>237548</idClient>
                <cardCountry>FR</cardCountry>
                <ipCountry>FR</ipCountry>
                <merchantDatas>
                    <_aKey_user>$userId</_aKey_user>
                    <_aKey_key>$key</_aKey_key>
                </merchantDatas>
                <transid>$id</transid>
                <is3ds>No</is3ds>
                <paymentMethod>CB</paymentMethod>
                <refProduct0>REF1</refProduct0>
                <customerCountry>FR</customerCountry>
                <returnCode/>
                <returnDescriptionShort/>
                <returnDescriptionLong/>
            </result>
        </mapi>";
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

    public function testPaypalNotificationWithInvalidRequest()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('UNVERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', []);
        $response->assertStatus(403);
    }

    public function testPaypalNotificationWithUnknownUser()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', ['custom' => 10]);
        $response->assertStatus(404);
    }

    public function testPaypalNotificationWithoutPermission()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', ['custom' => 2]);
        $response->assertStatus(403);
    }

    public function testPaypalNotificationWithInvalidCurrency()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', ['custom' => 1, 'mc_currency' => 'USD']);
        $response->assertStatus(403);
    }

    public function testPaypalNotificationWithInvalidReceiver()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', ['custom' => 1, 'mc_currency' => 'EUR', 'receiver_email' => 'paypal@fake.de']);
        $response->assertStatus(403);
    }

    public function testPaypalNotificationAlreadyCompleted()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', [
            'custom' => 1,
            'mc_currency' => 'EUR',
            'receiver_email' => 'paypal@obsifight.net',
            'txn_id' => '47374DHD',
            'payment_status' => 'COMPLETED'
        ]);
        $response->assertStatus(403);
    }

    public function testPaypalNotificationWithInvalidAmount()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', [
            'custom' => 1,
            'mc_currency' => 'EUR',
            'receiver_email' => 'paypal@obsifight.net',
            'txn_id' => '47374DHE',
            'mc_gross' => 11.00,
            'payment_status' => 'COMPLETED'
        ]);
        $response->assertStatus(404);
    }

    public function testPaypalNotificationCompleted()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        $date = date('Y-m-d H:i:s');
        $user = \App\User::find(1);
        $response = $this->post('/shop/credit/add/paypal/notification', [
            'custom' => 1,
            'mc_currency' => 'EUR',
            'receiver_email' => 'paypal@obsifight.net',
            'txn_id' => '47374DHE',
            'mc_gross' => 10.00,
            'mc_fee' => 0.8,
            'payer_email' => 'paypal@buyer.net',
            'payment_date' => $date,
            'payment_status' => 'COMPLETED'
        ]);
        $response->assertStatus(200);

        // Check history
        $transaction = ShopCreditPaypalHistory::where('payment_amount', 10.0)->where('payment_tax', 0.8)->where('payment_id', '47374DHE')->where('buyer_email', 'paypal@buyer.net')->where('payment_date', $date)->where('status', 'COMPLETED');
        $history = ShopCreditHistory::where('transaction_type', 'PAYPAL')->where('user_id', 1)->where('money', 950.0)->where('amount', 10.0)->where('transaction_id', $transaction->first()->id);
        $this->assertEquals(1, $transaction->count());
        $this->assertEquals(1, $history->count());
        $this->assertEquals($history->first()->id, $transaction->first()->history_id);

        // Check user money
        $this->assertEquals($user->money + 950, \App\User::find(1)->money);

        // Check notification
        $this->assertEquals(1, Notification::where('user_id', 1)->where('type', 'success')->where('key', 'shop.credit.add.success')->where('vars', json_encode(['money' => 950]))->where('auto_seen', 1)->count());
    }

    public function testPaypalNotificationReversedWithUnknownTransaction()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        $date = date('Y-m-d H:i:s');
        $user = \App\User::find(1);
        $response = $this->post('/shop/credit/add/paypal/notification', [
            'custom' => 1,
            'mc_currency' => 'EUR',
            'receiver_email' => 'paypal@obsifight.net',
            'txn_id' => '47374DHE',
            'mc_gross' => 10.00,
            'mc_fee' => 0.8,
            'payer_email' => 'paypal@buyer.net',
            'payment_date' => $date,
            'payment_status' => 'REVERSED'
        ]);
        $response->assertStatus(404);
    }

    public function testPaypalNotificationReversed()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        if (!class_exists('ApiObsifight'))
            require base_path('vendor/eywek/obsifight/API/ApiObsifight.class.php');
        $api = $this->getMockBuilder(\ApiObsifight::class)
            ->setMethods(['get'])
            ->setConstructorArgs([env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS')])
            ->getMock();
        $api->expects($this->once())
            ->method('get')
            ->willReturn((object)['status' => true, 'success' => true]);
        $this->app->instance('\ApiObsifight', $api);

        $date = date('Y-m-d H:i:s');
        $response = $this->post('/shop/credit/add/paypal/notification', [
            'custom' => 1,
            'mc_currency' => 'EUR',
            'receiver_email' => 'paypal@obsifight.net',
            'txn_id' => '47374DHD',
            'mc_gross' => 10.00,
            'mc_fee' => 0.8,
            'payer_email' => 'paypal@buyer.net',
            'payment_date' => $date,
            'payment_status' => 'REVERSED'
        ]);
        $response->assertStatus(200);

        // check roles
        $user = \App\User::find(1);
        $this->assertEquals(true, $user->hasRole('restricted'));
        $this->assertEquals(false, $user->hasRole('user'));

        // check transaction
        $this->assertEquals(1,ShopCreditPaypalHistory::where('payment_id', '47374DHD')->where('status', 'REVERSED')->count());
    }

    public function testPaypalNotificationCanceledReversalWithUnknownTransaction()
    {
        $paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
            ->setMethods(['verifyIPN'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        $date = date('Y-m-d H:i:s');
        $user = \App\User::find(1);
        $response = $this->post('/shop/credit/add/paypal/notification', [
            'custom' => 1,
            'mc_currency' => 'EUR',
            'receiver_email' => 'paypal@obsifight.net',
            'txn_id' => '47374DHE',
            'mc_gross' => 10.00,
            'mc_fee' => 0.8,
            'payer_email' => 'paypal@buyer.net',
            'payment_date' => $date,
            'payment_status' => 'CANCELED_REVERSAL'
        ]);
        $response->assertStatus(404);
    }

    public function testPaypalNotificationCanceledReversal()
    {$paypalClient = $this->getMockBuilder(\Srmklive\PayPal\Services\ExpressCheckout::class)
        ->setMethods(['verifyIPN'])
        ->getMock();
        $paypalClient->expects($this->once())
            ->method('verifyIPN')
            ->willReturn('VERIFIED');
        $this->app->instance('\Srmklive\PayPal\Services\ExpressCheckout', $paypalClient);

        if (!class_exists('ApiObsifight'))
            require base_path('vendor/eywek/obsifight/API/ApiObsifight.class.php');
        $api = $this->getMockBuilder(\ApiObsifight::class)
            ->setMethods(['get'])
            ->setConstructorArgs([env('API_OBSIFIGHT_USER'), env('API_OBSIFIGHT_PASS')])
            ->getMock();
        $api->expects($this->exactly(2))
            ->method('get')
            ->willReturn((object)['status' => true, 'success' => true, 'body' => ['bans' => [['id' => 1]]]]);
        $this->app->instance('\ApiObsifight', $api);

        $user = \App\User::find(1);
        $user->detachRole(Role::where('name', 'user')->first());
        $user->attachRole(Role::where('name', 'restricted')->first());
        $date = date('Y-m-d H:i:s');
        $response = $this->post('/shop/credit/add/paypal/notification', [
            'custom' => 1,
            'mc_currency' => 'EUR',
            'receiver_email' => 'paypal@obsifight.net',
            'txn_id' => '47374DHD',
            'mc_gross' => 10.00,
            'mc_fee' => 0.8,
            'payer_email' => 'paypal@buyer.net',
            'payment_date' => $date,
            'payment_status' => 'CANCELED_REVERSAL'
        ]);
        $response->assertStatus(200);

        // check roles
        $user = \App\User::find(1);
        $this->assertEquals(false, $user->hasRole('restricted'));
        $this->assertEquals(true, $user->hasRole('user'));

        // check transaction
        $this->assertEquals(1,ShopCreditPaypalHistory::where('payment_id', '47374DHD')->where('status', 'CANCELED_REVERSAL')->count());
    }

    public function testHipayNotificationInvalidRequest()
    {
        $response = $this->post('/shop/credit/add/hipay/notification', []);
        $response->assertStatus(400);

        $response = $this->post('/shop/credit/add/hipay/notification', ['xml' => 'invalid']);
        $response->assertStatus(500);

        $response = $this->post('/shop/credit/add/hipay/notification', [
            'xml' => '<?xml version="1.0" encoding="UTF-8"?><mapi><mapiversion>1.0</mapiversion><md5content></md5content></mapi>']);
        $response->assertStatus(400);

        $response = $this->post('/shop/credit/add/hipay/notification', [
            'xml' => $this->generateHipayXML(
                10,
                'EUR',
                1,
                'invalid_key'
            )
        ]);
        $response->assertStatus(403);

        $response = $this->post('/shop/credit/add/hipay/notification', [
            'xml' => $this->generateHipayXML(
                10,
                'EUR',
                1,
                'random_key',
                'invalid_operation'
            )
        ]);
        $response->assertStatus(403);

        $response = $this->post('/shop/credit/add/hipay/notification', [
            'xml' => $this->generateHipayXML(
                10,
                'EUR',
                1,
                'random_key',
                'capture',
                'ko'
            )
        ]);
        $response->assertStatus(403);

        $response = $this->post('/shop/credit/add/hipay/notification', [
            'xml' => $this->generateHipayXML(
                10,
                'USD'
            )
        ]);
        $response->assertStatus(403);
    }

    public function testHipayNotificationWithUnknownAmount()
    {
        $response = $this->post('/shop/credit/add/hipay/notification', [
            'xml' => $this->generateHipayXML(
                '12.00'
            )
        ]);
        $response->assertStatus(404);
    }

    public function testHipayNotificationWithUnknownUser()
    {
        $response = $this->post('/shop/credit/add/hipay/notification', [
            'xml' => $this->generateHipayXML(
                '10.00',
                'EUR',
                10
            )
        ]);
        $response->assertStatus(404);
    }

    public function testHipayNotificationWithAlreadyHandledPayment()
    {
        $response = $this->post('/shop/credit/add/hipay/notification', [
            'xml' => $this->generateHipayXML(
                '10.00',
                'EUR',
                1,
                'random_key',
                'capture',
                'ok',
                '58DFDA4488963163'
            )
        ]);
        $response->assertStatus(403);
    }

    public function testHipayNotification()
    {
        $user = \App\User::find(1);
        $response = $this->post('/shop/credit/add/hipay/notification', [
            'xml' => $this->generateHipayXML()
        ]);
        $response->assertStatus(200);

        // Check history
        $transaction = ShopCreditHipayHistory::where('payment_amount', 10.0)->where('payment_id', '58DFDA4488963162');
        $history = ShopCreditHistory::where('transaction_type', 'HIPAY')->where('user_id', 1)->where('money', 950.0)->where('amount', 10.0)->where('transaction_id', $transaction->first()->id);
        $this->assertEquals(1, $transaction->count());
        $this->assertEquals(1, $history->count());
        $this->assertEquals($history->first()->id, $transaction->first()->history_id);

        // Check user money
        $this->assertEquals($user->money + 950, \App\User::find(1)->money);

        // Check notification
        $this->assertEquals(1, Notification::where('user_id', 1)->where('type', 'success')->where('key', 'shop.credit.add.success')->where('vars', json_encode(['money' => 950]))->where('auto_seen', 1)->count());
    }
}