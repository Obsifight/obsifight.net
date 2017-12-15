<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopCreditDedipassHistory extends Model
{

    static public function payoutColumn()
    {
        return 'payout';
    }

    public function history()
    {
        return $this->belongsTo('App\ShopCreditHistory', 'history_id');
    }
}
