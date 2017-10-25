<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCreditPaypalHistory extends Model
{
    public function history()
    {
        return $this->belongsTo('App\ShopCreditHistory', 'history_id');
    }
}
