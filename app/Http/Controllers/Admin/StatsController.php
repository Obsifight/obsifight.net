<?php

namespace App\Http\Controllers\Admin;

use App\ShopCreditHistory;
use App\ShopItemsPurchaseHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{

    public function shop(Request $request)
    {
        $purchasesItemsCount = ShopItemsPurchaseHistory::count();
        $purchasesCreditsCount = ShopCreditHistory::count();
        $profitTotal = ShopCreditHistory::getProfitTotal();
        $profitThisMonth = ShopCreditHistory::getProfitBetween(date('Y-m-01 00:00:00'), date('Y-m-d H:i:s'));

        return view('admin.stats.shop', compact('purchasesCreditsCount', 'purchasesItemsCount', 'profitThisMonth', 'profitTotal'));
    }

    public function graphPurchasesCredits(Request $request)
    {
        if (!Cache::has('admin.graph.purchases.credits')) {
            $data = [];
            for ($i = 0; $i < 7; $i++)
                $data[] = \App\ShopCreditHistory::where('created_at', 'LIKE', date('Y-m-d', strtotime('-' . (6 - $i) . ' days')) . '%')->sum('money');
            // Store
            Cache::put('admin.graph.purchases.credits', $data, 30); // 30 minutes
        }
        return response()->json(['status' => true, 'graph' => Cache::get('admin.graph.purchases.credits')]);
    }

    public function graphPurchasesCreditsModes(Request $request)
    {
        $data = [
            'PAYPAL' => [],
            'DEDIPASS' => [],
            'HIPAY' => [],
            'PAYSAFECARD' => []
        ];
        foreach ($data as $mode => $d) {
            for ($i = date('m', strtotime('-5 months')); $i <= date('m'); $i++)
                $data[$mode][intval($i)] = 0;
            $class = '\App\ShopCredit' . ucfirst(strtolower($mode)) . 'History';
            $req = $class::select(DB::raw('SUM(' . $class::payoutColumn() . ') AS sum'), DB::raw('MONTH(created_at) AS month'))->groupBy(DB::raw('MONTH(created_at)'))->where('created_at', '>=', date('Y-m-01 00:00:00', strtotime('-5 months')))->get();
            foreach ($req->toArray() as $res)
                $data[$mode][$res['month']] = $res['sum'];
            $data[$mode] = array_values($data[$mode]);
        }
        return response()->json(['status' => true, 'graph' => $data]);
    }

    public function graphPurchasesItems(Request $request)
    {
        if (!Cache::has('admin.graph.purchases.items')) {
            $data = [];
            for ($i = 0; $i < 7; $i++)
            {
                $data[] = DB::table('shop_items_purchase_histories')
                            ->leftJoin('shop_items', 'item_id', '=', 'shop_items.id')
                            ->where('shop_items_purchase_histories.created_at', 'LIKE', date('Y-m-d', strtotime('-' . (6 - $i) . ' days')) . '%')
                            ->sum('shop_items.price');
            }
            // Store
            Cache::put('admin.graph.purchases.items', $data, 30); // 30 minutes
        }
        return response()->json(['status' => true, 'graph' => Cache::get('admin.graph.purchases.items')]);
    }

    public function graphTransfers(Request $request)
    {
        if (!Cache::has('admin.graph.transfers')) {
            $data = [];
            for ($i = 0; $i < 7; $i++)
                $data[] = \App\UsersTransferMoneyHistory::where('created_at', 'LIKE', date('Y-m-d', strtotime('-' . (6 - $i) . ' days')) . '%')->sum('amount');
            // Store
            Cache::put('admin.graph.transfers', $data, 30); // 30 minutes
        }
        return response()->json(['status' => true, 'graph' => Cache::get('admin.graph.transfers')]);
    }

}
