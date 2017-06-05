<?php
namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\User;
use App\ShopCategory;
use App\ShopItem;
use App\ShopRank;
use App\ShopItemsPurchaseHistory;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

class ShopController extends Controller
{
  public function index(Request $request)
  {
    $categories = ShopCategory::with(['items' => function ($query) {
      $query->where('displayed', 1);
      $query->doesntHave('rank');
    }])->where('displayed', 1)->orderBy('order')->get();
    $mostPurchasedItems = ShopItemsPurchaseHistory::selectRaw('COUNT(*) as count, item_id')->groupBy('item_id')->orderBy('count', 'DESC')->with('item')->limit(3)->get();
    $ranks = ShopRank::whereHas('item', function ($query) {
      $query->where('displayed', 1);
    })->get();

    // Auto select with route
    $rankSelected = (isset($request->rankslug)) ? $request->rankslug : false;
    $itemSelected = (isset($request->itemid)) ? $request->itemid : false;
    $categorySelected = (isset($request->categoryid)) ? $request->categoryid : false;

    return view('shop.index', compact('categories', 'mostPurchasedItems', 'ranks', 'itemSelected', 'categorySelected', 'rankSelected'));
  }
}
