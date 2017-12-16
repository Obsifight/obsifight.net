<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopVouchersHistory extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function voucher()
    {
        return $this->belongsTo('App\ShopVoucher', 'voucher_id');
    }
}
