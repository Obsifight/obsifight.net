<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopSaleHistory extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
    public function item()
    {
        return $this->belongsTo('App\ShopItem', 'product_id');
    }
    public function sale()
    {
        return $this->belongsTo('App\ShopSale', 'sale_id');
    }
    public function history()
    {
        return $this->belongsTo('App\ShopItemsPurchaseHistory', 'history_id');
    }
}
