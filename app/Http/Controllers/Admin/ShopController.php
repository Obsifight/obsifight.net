<?php

namespace App\Http\Controllers\Admin;

use App\ShopCreditDedipassHistory;
use App\ShopCreditHipayHistory;
use App\ShopCreditHistory;
use App\ShopCreditPaypalHistory;
use App\ShopCreditPaysafecardHistory;
use App\ShopItemsPurchaseHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\Datatables\Facades\Datatables;

class ShopController extends Controller
{

    public function history(Request $request)
    {
        return view('admin.shop.history');
    }

    public function historyDataItems(Request $request)
    {
        return Datatables::eloquent(ShopItemsPurchaseHistory::with('user')->with('item')->orderBy('shop_items_purchase_histories.id', 'DESC'))->make(true);
    }

    public function historyDataCredits(Request $request)
    {
        return Datatables::eloquent(ShopCreditHistory::with('user')->orderBy('shop_credit_histories.id', 'DESC'))->make(true);
    }

    public function historyDataPaypal(Request $request)
    {
        return Datatables::eloquent(ShopCreditPaypalHistory::with('history')->with('history.user')->orderBy('shop_credit_paypal_histories.id', 'DESC'))->make(true);
    }

    public function historyDataDedipass(Request $request)
    {
        return Datatables::eloquent(ShopCreditDedipassHistory::with('history')->with('history.user')->orderBy('shop_credit_dedipass_histories.id', 'DESC'))->make(true);
    }

    public function historyDataHipay(Request $request)
    {
        return Datatables::eloquent(ShopCreditHipayHistory::with('history')->with('history.user')->orderBy('shop_credit_hipay_histories.id', 'DESC'))->make(true);
    }

    public function historyDataPaysafecard(Request $request)
    {
        return Datatables::eloquent(ShopCreditPaysafecardHistory::with('history')->with('history.user')->orderBy('shop_credit_paysafecard_histories.id', 'DESC'))->make(true);
    }

}
