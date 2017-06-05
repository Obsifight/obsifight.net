<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopItem extends Model
{
  public function category()
  {
    return $this->belongsTo('App\ShopCategory', 'category_id');
  }
  public function rank()
  {
    return $this->hasOne('App\ShopRank', 'item_id');
  }
  public function getSalesAttribute()
  {
    return \App\ShopVoucher::where(function ($query) {
      $query->where('product_type', 'ITEM');
      $query->where('product_id', $this->id);
    })->orWhere(function ($query) {
      $query->where('product_type', 'CATEGORY');
      $query->where('product_id', $this->category->id);
    })->orWhere('product_type', 'ALL')->get();
  }
  public function getReductionAttribute()
  {
    $reduction = 0;
    foreach ($this->sales as $sale) {
      $reduction += $sale->reduction;
    }
    return $reduction;
  }
  public function getPriceWithReductionAttribute()
  {
    return $this->price - ($this->price * ($this->reduction/100));
  }
}
