<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Request;

class ShopItem extends Model
{
  use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
  protected $dates = ['deleted_at'];
  protected $casts = [
    'commands' => 'array',
  ];
  protected $hidden = ['displayed', 'commands', 'custom_ability', 'need_connected', 'created_at', 'updated_at'];
  public $quantity = 1;

  public function category()
  {
    return $this->belongsTo('App\ShopCategory', 'category_id');
  }

  public function rank()
  {
    return $this->hasOne('App\ShopRank', 'item_id');
  }

  public function abilities()
  {
    return $this->hasMany('App\ShopItemsAbility', 'item_id');
  }

  public function getSalesAttribute()
  {
    return \App\ShopSale::where(function ($query) {
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

  public function buy()
  {
    // Handle abilities conditions
    foreach ($this->abilities as $ability) {
      $model = '\App\\' . $ability->model;
      // Check max condition
      if ($ability->condition_max && $ability->condition_max <= $model::where('user_id', Auth::user()->id)->count())
        return [false, 'ability.max'];
    }

    // Store into history
    $history = new \App\ShopItemsPurchaseHistory();
    $history->user_id = Auth::user()->id;
    $history->item_id = $this->id;
    $history->quantity = $this->quantity;
    $history->ip = Request::ip();
    if (!$history->save()) return [false, 'save'];

    // Store sale(s) into history if used
    foreach ($this->sales as $sale) {
      $saleHistory = new \App\ShopSaleHistory();
      $saleHistory->user_id = Auth::user()->id;
      $saleHistory->item_id = $this->id;
      $saleHistory->sale_id = $sale->id;
      $saleHistory->history_id = $history->id;
      $saleHistory->reduction = ($this->price * $this->quantity * ($sale->reduction/100));
      $saleHistory->save();
    }

    // Handle abilities add
    foreach ($this->abilities as $ability) {
      $model = '\App\\' . $ability->model;
      $model = new $model();
      $model->user_id = Auth::user()->id;
      $model->save();
    }

    return [true, null];
  }
}
