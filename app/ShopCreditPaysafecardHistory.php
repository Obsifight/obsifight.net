<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCreditPaysafecardHistory extends Model
{
    static public function payoutColumn()
    {
        return 'payment_amount';
    }

    public function history()
    {
        return $this->belongsTo('App\ShopCreditHistory', 'history_id');
    }
}
