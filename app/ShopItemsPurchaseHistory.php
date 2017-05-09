<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopItemsPurchaseHistory extends Model
{
  public function user()
  {
    return $this->belongsTo('App\User', 'user_id');
  }
  public function item()
  {
    return $this->belongsTo('App\ShopItem', 'item_id');
  }
}
