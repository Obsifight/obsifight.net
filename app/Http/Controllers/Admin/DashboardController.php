<?php

namespace App\Http\Controllers\Admin;

use App\ShopCreditHistory;
use App\ShopItemsPurchaseHistory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{

    public function index(Request $request)
    {
        // Get global stats
        $countUsers = 0; // use statscontroller
        $countUsersThisVersion = 0; // use statscontroller
        $onlinePlayers = 0; // use statscontroller
        $purchaseCount = ShopItemsPurchaseHistory::count();
        $purchaseCreditCount = ShopCreditHistory::count();

        return view('admin.dashboard.index');
    }

}
