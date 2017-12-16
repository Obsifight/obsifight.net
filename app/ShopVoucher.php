<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ShopVoucher extends Model
{
    public function history()
    {
        return $this->hasOne('App\ShopVouchersHistory', 'voucher_id');
    }
}
