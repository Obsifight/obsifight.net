<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCreditPaypalHistory extends Model
{
    static public function payoutColumn()
    {
        return 'payment_amount - payment_tax';
    }

    public function history()
    {
        return $this->belongsTo('App\ShopCreditHistory', 'history_id');
    }
}
