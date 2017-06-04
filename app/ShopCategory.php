<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCategory extends Model
{
  public function items()
  {
    return $this->hasMany('App\ShopItem', 'category_id');
  }
}
