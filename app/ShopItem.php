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
}
