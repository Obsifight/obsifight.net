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
    $sales = \App\ShopSale::getMessage(); // TODO: Display sales on item price

    // Auto select with route
    $rankSelected = (isset($request->rankslug)) ? $request->rankslug : false;
    $itemSelected = (isset($request->itemid)) ? $request->itemid : false;
    $categorySelected = (isset($request->categoryid)) ? $request->categoryid : false;

    return view('shop.index', compact('categories', 'mostPurchasedItems', 'ranks', 'itemSelected', 'categorySelected', 'rankSelected', 'sales'));
  }

  public function buy(Request $request)
  {
    if (!$request->has('item') || !is_array($request->input('item')))
      return abort(400, 'Missing item.');

    /* ====
      Handle item & total price
    ==== */
    $totalPrice = 0;
    $requestedItem = $request->input('item');
    // Check request
    if (!isset($requestedItem['id'])) return abort(400, 'Missing id.'); // Missing id
    if (!isset($requestedItem['quantity']))
      $requestedItem['quantity'] = 1;
    // Find item
    $item = ShopItem::find($requestedItem['id']);
    if (!$item || empty($item))
      return abort(404, 'Item not found.'); // Item not found
    // Add to total
    $totalPrice += $item->priceWithReduction * $requestedItem['quantity'];
    // Set quantity
    $item->quantity = $requestedItem['quantity'];
    /* ====
      Handle price
    ==== */
    if (Auth::user()->money < $totalPrice)
      return response()->json([
        'status' => false,
        'error' => __('shop.buy.error.price')
      ]);
    /* ====
      Handle server (connected)
    ==== */
    $server = resolve('\Server');
    if ($item->need_connected) {
      $command = $server->isConnected(Auth::user()->username)->get();
      if (!$command['isConnected'])
        return response()->json([
          'status' => false,
          'error' => __('shop.buy.error.server.connected')
        ]);
    } else if (!empty($item->commands)) {
      if (!$server->isOnline())
        return response()->json([
          'status' => false,
          'error' => __('shop.buy.error.server.online')
        ]);
    }
    /* ====
      Handle buy (history)
    ==== */
    list($buyStatus, $buyError) = $item->buy();
    if (!$buyStatus)
      return response()->json([
        'status' => false,
        'error' => __('shop.buy.error.' . $buyError)
      ]);
    /* ====
      Handle user debit
    ==== */
    $currentUser = User::find(Auth::user()->id);
    $currentUser->money = ($currentUser->money - floatval($totalPrice));
    $currentUser->save();
    /* ====
      Handle server commands
    ==== */
    if (!empty($item->commands)) {
      foreach ($item->commands as $command) {
        $command = $server->sendCommand(str_replace('{PLAYER}', Auth::user()->username, $command))->get();
      }
    }
    /* ====
      Handle success message
    ==== */
    return response()->json([
      'status' => true,
      'success' => __('shop.buy.success', ['item_name' => $item->name, 'price' => $totalPrice])
    ]);
  }
}
