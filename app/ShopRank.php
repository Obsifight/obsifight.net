<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopRank extends Model
{
  protected $casts = ['advantages' => 'array'];

  public function item()
  {
    return $this->belongsTo('App\ShopItem', 'item_id');
  }
}
