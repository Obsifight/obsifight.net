<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopItemsAbility extends Model
{
  public function item()
  {
    return $this->belongsTo('App\ShopItem', 'item_id');
  }
}
