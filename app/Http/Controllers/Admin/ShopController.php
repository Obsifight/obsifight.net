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
use Yajra\Datatables\Datatables;

class ShopController extends Controller
{

    public function history(Request $request)
    {
        return view('admin.shop.history');
    }

    public function historyDataItems(Request $request)
    {
        return Datatables::of(ShopItemsPurchaseHistory::with('user')->with('item')->orderBy('id', 'DESC')->get())->make(true);
    }

    public function historyDataCredits(Request $request)
    {
        return Datatables::of(ShopCreditHistory::with('user')->orderBy('id', 'DESC')->get())->make(true);
    }

    public function historyDataPaypal(Request $request)
    {
        return Datatables::of(ShopCreditPaypalHistory::with('history')->with('history.user')->orderBy('id', 'DESC')->get())->make(true);
    }

    public function historyDataDedipass(Request $request)
    {
        return Datatables::of(ShopCreditDedipassHistory::with('history')->with('history.user')->orderBy('id', 'DESC')->get())->make(true);
    }

    public function historyDataHipay(Request $request)
    {
        return Datatables::of(ShopCreditHipayHistory::with('history')->with('history.user')->orderBy('id', 'DESC')->get())->make(true);
    }

    public function historyDataPaysafecard(Request $request)
    {
        return Datatables::of(ShopCreditPaysafecardHistory::with('history')->with('history.user')->orderBy('id', 'DESC')->get())->make(true);
    }

}
