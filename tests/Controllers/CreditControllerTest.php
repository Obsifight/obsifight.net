<?php
namespace Tests\Feature;

use App\Notification;
use App\Role;
use App\ShopCreditDedipassHistory;
use App\ShopCreditHipayHistory;
use App\ShopCreditHistory;
use App\ShopCreditPaypalHistory;
use App\ShopCreditPaysafecardHistory;
use App\ShopVouchersHistory;
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
            'key' => 'invalid',
            'rate' => 'FR-SMS-10'
        ]);
        $response->assertStatus(400);
    }

    public function testDedipassNotificationWithoutRate()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/dedipass/notification', [
            'key' => 'invalid',
            'code' => '1808ABAB'
        ]);
        $response->assertStatus(400);
    }

    public function testDedipassNotificationWithInvalidPublicKey()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/dedipass/notification', [
            'key' => 'invalid',
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
            'key' => 'fake',
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
            'key' => 'fake',
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
            'key' => 'fake',
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
            'key' => 'fake',
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
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(0);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', []);
        $response->assertStatus(403);
    }

    public function testPaypalNotificationWithUnknownUser()
    {
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', ['custom' => 10]);
        $response->assertStatus(404);
    }

    public function testPaypalNotificationWithoutPermission()
    {
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', ['custom' => 2]);
        $response->assertStatus(403);
    }

    public function testPaypalNotificationWithInvalidCurrency()
    {
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', ['custom' => 1, 'mc_currency' => 'USD']);
        $response->assertStatus(403);
    }

    public function testPaypalNotificationWithInvalidReceiver()
    {
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

        $response = $this->post('/shop/credit/add/paypal/notification', ['custom' => 1, 'mc_currency' => 'EUR', 'receiver_email' => 'paypal@fake.de']);
        $response->assertStatus(403);
    }

    public function testPaypalNotificationAlreadyCompleted()
    {
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

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
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

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
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

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
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

        $date = date('Y-m-d H:i:s');
        $user = \App\User::find(1);
        $response = $this->post('/shop/credit/add/paypal/notification', [
            'custom' => 1,
            'mc_currency' => 'EUR',
            'receiver_email' => 'paypal@obsifight.net',
            'parent_txn_id' => '47374DHE',
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
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

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
            'parent_txn_id' => '47374DHD',
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
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

        $date = date('Y-m-d H:i:s');
        $user = \App\User::find(1);
        $response = $this->post('/shop/credit/add/paypal/notification', [
            'custom' => 1,
            'mc_currency' => 'EUR',
            'receiver_email' => 'paypal@obsifight.net',
            'parent_txn_id' => '47374DHE',
            'mc_gross' => 10.00,
            'mc_fee' => 0.8,
            'payer_email' => 'paypal@buyer.net',
            'payment_date' => $date,
            'payment_status' => 'CANCELED_REVERSAL'
        ]);
        $response->assertStatus(404);
    }

    public function testPaypalNotificationCanceledReversal()
    {
        $paypalClient = $this->getMockBuilder(\Fahim\PaypalIPN\PaypalIPNListener::class)
            ->setMethods(['processIpn'])
            ->getMock();
        $paypalClient->expects($this->once())
            ->method('processIpn')
            ->willReturn(1);
        $this->app->instance('\Fahim\PaypalIPN\PaypalIPNListener', $paypalClient);

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
            'parent_txn_id' => '47374DHD',
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

    public function testPaysafecardInitNotLoggedAndWithoutPermission()
    {
        $response = $this->post('/shop/credit/add/paysafecard/init');
        $response->assertStatus(302);

        $user = \App\User::find(2);
        $this->be($user);

        $response = $this->post('/shop/credit/add/paysafecard/init');
        $response->assertStatus(403);
    }

    public function testPaysafecardInitWithoutAmount()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/paysafecard/init');
        $response->assertStatus(400);
    }

    public function testPaysafecardInit()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $urlObject = $this->getMockBuilder(\SebastianWalker\Paysafecard\Urls::class)
            ->getMock();
        $urlObject->expects($this->once())
            ->method('setSuccessUrl')
            ->with(url("/shop/credit/add/paysafecard/success"))
            ->willReturn($urlObject);
        $urlObject->expects($this->once())
            ->method('setFailureUrl')
            ->with(url("/shop/credit/add/error"))
            ->willReturn($urlObject);
        $urlObject->expects($this->once())
            ->method('setNotificationUrl')
            ->with(url("/shop/credit/add/paysafecard/notification"))
            ->willReturn($urlObject);
        $this->app->instance('\SebastianWalker\Paysafecard\Urls', $urlObject);

        $amountObject = $this->getMockBuilder(\SebastianWalker\Paysafecard\Amount::class)
            ->getMock();
        $amountObject->expects($this->once())
            ->method('setAmount')
            ->with(10.0)
            ->willReturn($amountObject);
        $amountObject->expects($this->once())
            ->method('setCurrency')
            ->with('EUR')
            ->willReturn($amountObject);
        $this->app->instance('\SebastianWalker\Paysafecard\Amount', $amountObject);

        $paysafecardClient = $this->getMockBuilder(\SebastianWalker\Paysafecard\Client::class)
            ->getMock();
        $paysafecardClient->expects($this->once())
            ->method('setApiKey')
            ->with('random_key')
            ->willReturn($paysafecardClient);
        $paysafecardClient->expects($this->once())
            ->method('setUrls')
            ->with($urlObject)
            ->willReturn($paysafecardClient);
        $paysafecardClient->expects($this->once())
            ->method('setTestingMode')
            ->with(true)
            ->willReturn($paysafecardClient);
        $this->app->instance('\SebastianWalker\Paysafecard\Client', $paysafecardClient);

        $paymentObject = $this->getMockBuilder(\SebastianWalker\Paysafecard\Payment::class)
            ->getMock();
        $paymentObject->expects($this->once())
            ->method('setAmount')
            ->with($amountObject)
            ->willReturn($paymentObject);
        $paymentObject->expects($this->once())
            ->method('setCustomerId')
            ->with(1)
            ->willReturn($paymentObject);
        $paymentObject->expects($this->once())
            ->method('create')
            ->with($paysafecardClient)
            ->willReturn($paymentObject);
        $paymentObject->expects($this->once())
            ->method('getAuthUrl')
            ->willReturn('/redirect/to/paysafecard');
        $this->app->instance('\SebastianWalker\Paysafecard\Payment', $paymentObject);

        $response = $this->post('/shop/credit/add/paysafecard/init', ['amount' => '10.00']);
        $response->assertStatus(302);
    }

    public function testPaysafecardNotificationInvalidRequest()
    {
        $amountObject = $this->getMockBuilder(\SebastianWalker\Paysafecard\Amount::class)
            ->getMock();
        $amountObject->expects($this->at(0))
            ->method('getCurrency')
            ->willReturn('USD');
        $amountObject->expects($this->at(1))
            ->method('getCurrency')
            ->willReturn('EUR');
        $amountObject->expects($this->at(2))
            ->method('getCurrency')
            ->willReturn('EUR');

        $paysafecardClient = $this->getMockBuilder(\SebastianWalker\Paysafecard\Client::class)
            ->getMock();
        $paysafecardClient->expects($this->exactly(5))
            ->method('setApiKey')
            ->with('random_key')
            ->willReturn($paysafecardClient);
        $paysafecardClient->expects($this->exactly(5))
            ->method('setTestingMode')
            ->with(true)
            ->willReturn($paysafecardClient);
        $this->app->instance('\SebastianWalker\Paysafecard\Client', $paysafecardClient);

        $paymentObject = $this->getMockBuilder(\SebastianWalker\Paysafecard\Payment::class)
            ->getMock();
        $paymentObject->expects($this->at(0))
            ->method('isAuthorized')
            ->willReturn(false);
        $paymentObject->expects($this->at(1))
            ->method('isAuthorized')
            ->willReturn(true);
        $paymentObject->expects($this->at(4))
            ->method('isAuthorized')
            ->willReturn(true);
        $paymentObject->expects($this->at(8))
            ->method('isAuthorized')
            ->willReturn(true);
        $paymentObject->expects($this->exactly(3))
            ->method('getAmount')
            ->willReturn($amountObject);
        $paymentObject->expects($this->at(7))
            ->method('getCustomerId')
            ->willReturn(10);
        $paymentObject->expects($this->at(11))
            ->method('getCustomerId')
            ->willReturn(2);

        $paymentStatic = \Mockery::mock(\SebastianWalker\Paysafecard\Payment::class);
        $paymentStatic->shouldReceive('find')->with(
            '58DFDA4488963164',
            $paysafecardClient
        )->andReturn($paymentObject);
        $this->app->instance('\SebastianWalker\Paysafecard\Payment', $paymentStatic);

        // no id
        $response = $this->post('/shop/credit/add/paysafecard/notification');
        $response->assertStatus(400);

        // already handled
        $response = $this->post('/shop/credit/add/paysafecard/notification', ['mtid' => '58DFDA4488963163']);
        $response->assertStatus(403);

        // not authorized
        $response = $this->post('/shop/credit/add/paysafecard/notification', ['mtid' => '58DFDA4488963164']);
        $response->assertStatus(403);

        // invalid currency
        $response = $this->post('/shop/credit/add/paysafecard/notification', ['mtid' => '58DFDA4488963164']);
        $response->assertStatus(403);

        // user not found
        $response = $this->post('/shop/credit/add/paysafecard/notification', ['mtid' => '58DFDA4488963164']);
        $response->assertStatus(404);

        // user doesn't have permission
        $response = $this->post('/shop/credit/add/paysafecard/notification', ['mtid' => '58DFDA4488963164']);
        $response->assertStatus(403);
    }

    public function testPaysafecardNotification()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $amountObject = $this->getMockBuilder(\SebastianWalker\Paysafecard\Amount::class)
            ->getMock();
        $amountObject->expects($this->exactly(3))
            ->method('getAmount')
            ->willReturn(10.0);
        $amountObject->expects($this->once())
            ->method('getCurrency')
            ->willReturn('EUR');

        $paysafecardClient = $this->getMockBuilder(\SebastianWalker\Paysafecard\Client::class)
            ->getMock();
        $paysafecardClient->expects($this->once())
            ->method('setApiKey')
            ->with('random_key')
            ->willReturn($paysafecardClient);
        $paysafecardClient->expects($this->once())
            ->method('setTestingMode')
            ->with(true)
            ->willReturn($paysafecardClient);
        $this->app->instance('\SebastianWalker\Paysafecard\Client', $paysafecardClient);

        $paymentObject = $this->getMockBuilder(\SebastianWalker\Paysafecard\Payment::class)
            ->getMock();
        $paymentObject->expects($this->once())
            ->method('isAuthorized')
            ->willReturn(true);
        $paymentObject->expects($this->once())
            ->method('getAmount')
            ->willReturn($amountObject);
        $paymentObject->expects($this->once())
            ->method('getId')
            ->willReturn('58DFDA4488963164');
        $paymentObject->expects($this->once())
            ->method('getCustomerId')
            ->willReturn(1);

        $paymentStatic = \Mockery::mock(\SebastianWalker\Paysafecard\Payment::class);
        $paymentStatic->shouldReceive('find')->with(
            '58DFDA4488963164',
            $paysafecardClient
        )->andReturn($paymentObject);
        $this->app->instance('\SebastianWalker\Paysafecard\Payment', $paymentStatic);

        $response = $this->post('/shop/credit/add/paysafecard/notification', ['mtid' => '58DFDA4488963164']);
        $response->assertStatus(200);

        // Check history
        $transaction = ShopCreditPaysafecardHistory::where('payment_amount', 10.0)->where('payment_id', '58DFDA4488963164');
        $history = ShopCreditHistory::where('transaction_type', 'PAYSAFECARD')->where('user_id', 1)->where('money', 10.0 * 80)->where('amount', 10.0)->where('transaction_id', $transaction->first()->id);
        $this->assertEquals(1, $transaction->count());
        $this->assertEquals(1, $history->count());
        $this->assertEquals($history->first()->id, $transaction->first()->history_id);

        // Check user money
        $this->assertEquals($user->money + (10.0 * 80), \App\User::find(1)->money);

        // Check notification
        $this->assertEquals(1, Notification::where('user_id', 1)->where('type', 'success')->where('key', 'shop.credit.add.success')->where('vars', json_encode(['money' => 10.0 * 80]))->where('auto_seen', 1)->count());
    }

    public function testPaysafecardSuccessNotLoggedAndWithoutPermission()
    {
        $response = $this->get('/shop/credit/add/paysafecard/success');
        $response->assertStatus(302);

        $user = \App\User::find(2);
        $this->be($user);

        $response = $this->get('/shop/credit/add/paysafecard/success');
        $response->assertStatus(403);
    }

    public function testPaysafecardSuccess()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $guzzleClient = $this->getMockBuilder(\GuzzleHttp\Client::class)
            ->setMethods(['post'])
            ->getMock();
        $guzzleClient->expects($this->once())
            ->method('post')
            ->with(url('/shop/credit/add/paysafecard/notification'), [
                'form_params' => ['mtid' => '382YDBS']
            ])
            ->willReturn($guzzleClient);
        $this->app->instance('\GuzzleHttp\Client', $guzzleClient);

        $response = $this->get('/shop/credit/add/paysafecard/success?payment_id=382YDBS');
        $response->assertStatus(302);
        $response->assertRedirect(url('/shop/credit/add/success'));
    }

    public function testVoucherNotLogged()
    {
        $response = $this->post('/shop/credit/add/voucher');
        $response->assertStatus(302);
    }

    public function testVoucherWithoutPermission()
    {
        $user = \App\User::find(2);
        $this->be($user);

        $response = $this->post('/shop/credit/add/voucher');
        $response->assertStatus(403);
    }

    public function testVoucherWithEmptyCode()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/voucher', []);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('form.error.fields')
        ]);
    }

    public function testVoucherWithInvalidCode()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/voucher', ['code' => 'invalid']);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('shop.credit.add.error.voucher')
        ]);
    }

    public function testVoucherWithCodeAlreadyUsed()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/voucher', ['code' => 'already_used']);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => false,
            'error' => __('shop.credit.add.error.voucher')
        ]);
    }

    public function testVoucher()
    {
        $user = \App\User::find(1);
        $this->be($user);

        $response = $this->post('/shop/credit/add/voucher', ['code' => 'valid_code']);
        $response->assertStatus(200);
        $response->assertJson([
            'status' => true,
            'success' => __('shop.credit.add.success.voucher', ['money' => "10.0"])
        ]);

        // Check history
        $transaction = ShopVouchersHistory::where('voucher_id', 1)->where('user_id', 1);
        $history = ShopCreditHistory::where('transaction_type', 'VOUCHER')->where('user_id', 1)->where('money', 10)->where('amount', 0)->where('transaction_id', $transaction->first()->id);
        $this->assertEquals(1, $transaction->count());
        $this->assertEquals(1, $history->count());
        $this->assertEquals($history->first()->id, $transaction->first()->history_id);

        // Check user money
        $this->assertEquals($user->money + (10), \App\User::find(1)->money);

        // Check notification
        $this->assertEquals(1, Notification::where('user_id', 1)->where('type', 'success')->where('key', 'shop.credit.add.success')->where('vars', json_encode(['money' => "10.0"]))->where('auto_seen', 1)->count());
    }
}