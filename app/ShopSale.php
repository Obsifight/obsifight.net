<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopSale extends Model
{
  use SoftDeletes;

  /**
   * The attributes that should be mutated to dates.
   *
   * @var array
   */
  protected $dates = ['deleted_at'];

  static public function getMessage()
  {
    $sales = self::get();
    $salesMessages = [];
    foreach ($sales as $sale) {
      $sale->reduction = round($sale->reduction);
      if ($sale->product_type === 'ALL')
        $salesMessages[] = __('shop.voucher.all', ['reduction' => $sale->reduction]);
      elseif ($sale->product_type === 'ITEM')
        $salesMessages[] = __('shop.voucher.item', ['reduction' => $sale->reduction, 'item_name' => $sale->item->name]);
      elseif ($sale->product_type === 'CATEGORY')
        $salesMessages[] = __('shop.voucher.category', ['reduction' => $sale->reduction, 'category_name' => $sale->category->name]);
    }

    return $salesMessages;
  }

  public function category()
  {
    return $this->belongsTo('App\ShopCategory', 'product_id');
  }
  public function item()
  {
    return $this->belongsTo('App\ShopItem', 'product_id');
  }
}
