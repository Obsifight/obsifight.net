<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;

class StatsController extends Controller
{

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
