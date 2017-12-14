<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ShopCategory extends Model
{
  use SoftDeletes;

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
  protected $dates = ['deleted_at'];

  public function items()
  {
    return $this->hasMany('App\ShopItem', 'category_id');
  }
}
