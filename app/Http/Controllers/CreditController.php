<?php
namespace App\Http\Controllers;

use App\ShopCreditHistory;
use App\User;
use App\ShopCreditDedipassHistory;
use App\ShopCreditHipayHistory;
use App\ShopCreditPaypalHistory;
use App\ShopCreditPaysafecardHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CreditController extends Controller
{
    private $request;

    private $offers = [
      'PAYPAL' => [
          950 => 10.00,
          2300 => 25.00,
          4800 => 50.00,
          9600 => 100.00
      ]
    ];

    public function add(Request $request)
    {

    }

    public function dedipassNotification(Request $request)
    {
        $this->request = $request;
        if (!$request->has('public_key') || !$request->has('code') || !$request->has('rate'))
            abort(400);
        // Check if public key match with config
        if ($request->input('public_key') !== env('DEDIPASS_PUBLIC_KEY'))
            abort(403);

        // Check if payment is valid
        $endpoint = "http://api.dedipass.com/v1/pay/?key=" . env('DEDIPASS_PUBLIC_KEY') . "&rate={$request->input('rate')}&code={$request->input('code')}";
        $dedipassResult = @file_get_contents($endpoint);
        $dedipassResult = @json_decode($dedipassResult);
        if (!$dedipassResult)
            abort(403);
        if ($dedipassResult->status !== 'success')
            abort(403);

        // Check if not already in history
        if (ShopCreditDedipassHistory::where('code', $request->input('code'))->where('rate', $request->input('rate'))->count() > 0)
            abort(403);

        $money = $dedipassResult->virtual_currency;

        // Add transaction
        $transaction = new ShopCreditDedipassHistory();
        $transaction->code = $request->input('code');
        $transaction->rate = $request->input('rate');
        $transaction->payout = $dedipassResult->payout;
        $transaction->save();

        $this->save(Auth::user(), $money, $money / 80, 'DEDIPASS', $transaction);
        redirect('/user');
    }

    public function paypalNotification(Request $request)
    {
        $this->request = $request;
        // Check request
        $fields = $request->all();
        array_walk($fields, function ($value, $key) { return "$key=$value"; });
        $curl = curl_init();
        curl_setopt_array($curl, [
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_SSL_VERIFYHOST => false,
            CURLOPT_URL => 'https://www.paypal.com/cgi-bin/webscr',
            CURLOPT_ENCODING => 'gzip',
            CURLOPT_BINARYTRANSFER => true,
            CURLOPT_POST => true,
            CURLOPT_POSTFIELDS => 'cmd=_notify-validate&' . implode('&', $fields),
            CURLOPT_HEADER => false,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_0,
            CURLOPT_FORBID_REUSE => true,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_CONNECTTIMEOUT => 30,
            CURLOPT_TIMEOUT => 60,
            CURLINFO_HEADER_OUT => true,
            CURLOPT_HTTPHEADER => [
                'Connection: close',
                'Expect: ',
            ]
        ]);
        $result = curl_exec($curl);
        $httpCode = (int)curl_getinfo($curl, CURLINFO_HTTP_CODE);
        curl_close($curl);
        if ($httpCode !== 200)
            abort(403);
        if (!preg_match('~^(VERIFIED)$~i', trim($result)))
            abort(403);

        // Find user
        $user = User::where('id', $request->input('custom'))->findOrFail();
        // Check currency
        if ($request->input('mc_currency') !== 'EUR')
            abort(403);
        // Check receiver
        if ($request->input('receiver_email') !== env('PAYPAL_EMAIL'))
            abort(403);

        // Try to find transaction
        $transaction = ShopCreditPaypalHistory::where('payment_id', $request->input('txn_id'))->first();

        // Handle types
        switch (strtoupper($request->input('payment_status'))) {
            case 'COMPLETED':
                // Already handled
                if (is_object($transaction) && $transaction->status === 'COMPLETED')
                    abort(403);
                // Find offer
                if (!in_array($request->input('mc_gross'), $this->offers['PAYPAL']))
                    abort(404);

                // Add transaction
                $transaction = new ShopCreditDedipassHistory();
                $transaction->payment_amount = $request->input('mc_gross');
                $transaction->payment_tax = $request->input('mc_fee');
                $transaction->payment_id = $request->input('txn_id');
                $transaction->buyer_email = $request->input('payer_email');
                $transaction->payment_date = $request->input('payment_date');
                $transaction->status = 'COMPLETED';
                $transaction->save();

                $this->save(
                    $user,
                    $this->offers['PAYPAL'][$request->input('mc_gross')],
                    $request->input('mc_gross'),
                    'PAYPAL',
                    $transaction
                );
                break;
            case 'REVERSED':
            case 'REFUNDED':
                if (!is_object($transaction))
                    abort(404);

                // Ban user with API
                resolve('\ApiObsifight')->get('/sanction/bans', 'POST', [
                    'reason' => 'Accès au compte restreint : litige en cours',
                    'server' => '(global)',
                    'type' => 'user',
                    'user' => array(
                        'username' => $user->username
                    )
                ]);
                // Edit rank on website
                $user->roles()->attach(2); // restricted
                $user->roles()->detach(1);

                // Edit transaction
                $transaction->status = strtoupper($request->input('payment_status'));
                $transaction->case_date = date('Y-m-d H:i:s');
                $transaction->save();

                break;
            case 'CANCELED_REVERSAL':
                if (!is_object($transaction))
                    abort(404);

                // Unban user with API
                $api = resolve('\ApiObsifight');
                $result = $api->get("/user/{$user->username}/sanctions?limit=3");
                if ($result->status && $result->success) {
                    $banId = $result->body['bans'][0]['id'];
                    $api->get("/sanction/bans/{$banId}", 'PUT', array(
                        'remove_reason' => 'Litige clos'
                    ));
                }
                // Edit rank on website
                $user->roles()->attach(1);
                $user->roles()->detach(2);

                // Edit transaction
                $transaction->status = 'CANCELED_REVERSAL';
                $transaction->save();
                break;
            default:
                abort(404);
                break;
        }
    }

    public function hipayNotification(Request $request)
    {
        $this->request = $request;
    }

    private function save($user, $money, $amount, $type, $transaction)
    {
        // Save into history
        $history = new ShopCreditHistory();
        $history->transaction_type = $type;
        $history->user = $user->id;
        $history->money = $money;
        $history->amount = $amount;
        $history->transaction_id = $transaction->id;
        $history->save();

        // Add history to transaction
        $transaction->history_id = $history->id;
        $transaction->save();

        // Add money to user
        $currentUser = User::find(Auth::user()->id);
        $currentUser->money = ($currentUser->money + floatval($money));
        $currentUser->save();

        // Notify
        $this->request->session()->flash('flash.success', __('shop.credit.add.success', ['money' => $money]));
    }

}