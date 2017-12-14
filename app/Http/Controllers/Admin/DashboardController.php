<?php

namespace App\Http\Controllers\Admin;

use App\ShopCreditHistory;
use App\ShopItemsPurchaseHistory;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // Get global stats
        $purchaseCountThisWeek = ShopItemsPurchaseHistory::where('created_at', '>', Carbon::now()->startOfWeek()->toDateString())
                                                        ->count();
        $purchaseCountPreviousWeek = ShopItemsPurchaseHistory::where('created_at', '>', Carbon::now()->previous()->startOfWeek()->toDateString())
                                                        ->where('created_at', '<', Carbon::now()->startOfWeek()->toDateString())
                                                        ->count();
        $purchaseCount = $purchaseCountThisWeek * ($purchaseCountPreviousWeek > $purchaseCountThisWeek ? -1 : 1);

        $usersCountThisWeek = User::where('created_at', '>', Carbon::now()->startOfWeek()->toDateString())
            ->count();
        $usersCountPreviousWeek = User::where('created_at', '>', Carbon::now()->previous()->startOfWeek()->toDateString())
            ->where('created_at', '<', Carbon::now()->startOfWeek()->toDateString())
            ->count();
        $usersCount = $usersCountThisWeek * ($usersCountPreviousWeek > $usersCountThisWeek ? -1 : 1);

        return view('admin.dashboard.index', compact('purchaseCount', 'usersCount'));
    }

}
