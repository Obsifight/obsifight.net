<?php

namespace App\Http\Controllers\Admin;

use App\ShopCategory;
use App\ShopCreditDedipassHistory;
use App\ShopCreditHipayHistory;
use App\ShopCreditHistory;
use App\ShopCreditPaypalHistory;
use App\ShopCreditPaysafecardHistory;
use App\ShopItem;
use App\ShopItemsPurchaseHistory;
use App\ShopRank;
use App\ShopSale;
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

    public function items(Request $request)
    {
        $items = ShopItem::get();
        $categories = ShopCategory::orderBy('order')->get();
        $sales = ShopSale::with('item')->with('category')->get();
        $ranks = ShopRank::with('item')->get();

        return view('admin.shop.items', compact('items', 'categories', 'sales', 'ranks'));
    }

    public function deleteItem(Request $request)
    {
        $item = ShopItem::where('id', $request->id)->with('rank')->firstOrFail();
        if ($item->rank)
            $item->rank->delete();
        $item->delete();
        return response()->redirectTo('/admin/shop/items');
    }

    public function deleteCategory(Request $request)
    {
        ShopCategory::where('id', $request->id)->firstOrFail()->delete();
        return response()->redirectTo('/admin/shop/items');
    }

    public function deleteSale(Request $request)
    {
        ShopSale::where('id', $request->id)->firstOrFail()->delete();
        return response()->redirectTo('/admin/shop/items');
    }

    public function editItem(Request $request)
    {
        if (isset($request->id)) {
            $item = ShopItem::where('id', $request->id)->first();
            $title = __('admin.shop.item.edit');
        }
        else {
            $title = __('admin.shop.item.add');
            $item = new ShopItem();
        }
        $categories = ShopCategory::get();
        return view('admin.shop.item_edit', compact('item', 'title', 'categories'));
    }

    public function editItemData(Request $request)
    {
        if (isset($request->id))
            $item = ShopItem::where('id', $request->id)->first();
        else
            $item = new ShopItem();
        foreach (['name', 'price', 'description', 'category_id', 'displayed', 'commands', 'image_path', 'need_connected'] as $name)
        {
            if (!$request->has($name))
                return response()->json([
                    'status' => false,
                    'error' => __('form.error.fields')
                ]);
            $item->{$name} = $request->input($name);
        }
        if ($item->save())
            return response()->json([
                'status' => true,
                'success' => __('admin.shop.item.edit.success'),
                'redirect' => url('/admin/shop/items')
            ]);
        else
            return response()->json([
                'status' => false,
                'error' => __('form.error.internal')
            ]);
    }

    public function editCategory(Request $request)
    {
        if (isset($request->id)) {
            $category = ShopCategory::where('id', $request->id)->first();
            $title = __('admin.shop.category.edit');
        }
        else {
            $title = __('admin.shop.category.add');
            $category = new ShopCategory();
        }
        return view('admin.shop.category_edit', compact('category', 'title'));
    }

    public function editCategoryData(Request $request)
    {
        if (isset($request->id))
            $category = ShopCategory::where('id', $request->id)->first();
        else
            $category = new ShopCategory();
        foreach (['name', 'displayed'] as $name)
        {
            if (!$request->has($name))
                return response()->json([
                    'status' => false,
                    'error' => __('form.error.fields')
                ]);
            $category->{$name} = $request->input($name);
        }
        if ($category->save())
            return response()->json([
                'status' => true,
                'success' => __('admin.shop.category.edit.success'),
                'redirect' => url('/admin/shop/items')
            ]);
        else
            return response()->json([
                'status' => false,
                'error' => __('form.error.internal')
            ]);
    }

    public function editSale(Request $request)
    {
        if (isset($request->id)) {
            $sale = ShopSale::where('id', $request->id)->first();
            $title = __('admin.shop.sale.edit');
        }
        else {
            $title = __('admin.shop.sale.add');
            $sale = new ShopSale();
        }
        $items = ShopItem::get();
        $categories = ShopCategory::get();
        return view('admin.shop.sale_edit', compact('sale', 'title', 'items', 'categories'));
    }

    public function editSaleData(Request $request)
    {
        if (isset($request->id))
            $sale = ShopSale::where('id', $request->id)->first();
        else
            $sale = new ShopSale();
        foreach (['product_id', 'product_type', 'reduction'] as $name)
        {
            if (!$request->has($name) && $name !== 'product_id')
                return response()->json([
                    'status' => false,
                    'error' => __('form.error.fields'),
                ]);
            $sale->{$name} = $request->input($name);
        }
        if ($sale->save())
            return response()->json([
                'status' => true,
                'success' => __('admin.shop.sale.edit.success'),
                'redirect' => url('/admin/shop/items')
            ]);
        else
            return response()->json([
                'status' => false,
                'error' => __('form.error.internal')
            ]);
    }

}
