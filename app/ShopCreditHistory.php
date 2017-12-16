<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class ShopCreditHistory extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function getTransactionAttribute()
    {
        switch ($this->transactionType) {
            case 'PAYPAL';
                $model = 'App\ShopCreditPaypalHistory';
                break;
            case 'DEDIPASS';
                $model = 'App\ShopCreditDedipassHistory';
                break;
            case 'HIPAY';
                $model = 'App\ShopCreditHipayHistory';
                break;
            case 'PAYSAFECARD';
                $model = 'App\ShopCreditPaysafecardHistory';
                break;
            case 'VOUCHER';
                $model = 'App\ShopVouchersHistory';
                break;
            default:
                return NULL;
            break;
        }
        return $this->belongsTo($model, 'transaction_id');
    }

    static public function getProfitTotal()
    {
        return self::getProfitBetween(null, null);
    }

    static public function getProfitBetween($from, $to)
    {
        $where = '';
        if ($from && $to)
            $where = 'WHERE `shop_credit_histories`.`created_at` BETWEEN ? AND ?';
        return DB::select("SELECT DISTINCT SUM(`history`.`payout`) AS `profit` " .
            "FROM `shop_credit_histories` " .
            "JOIN ( " .
                 "(SELECT (`payment_amount` - `payment_tax`) AS `payout`, `id`, 'PAYPAL' AS `type` FROM `shop_credit_paypal_histories` WHERE `status` = 'COMPLETED') " .
              "UNION " .
                 "(SELECT (`payout`) AS `payout`, `id`, 'DEDIPASS' AS `type` FROM `shop_credit_dedipass_histories`) " .
              "UNION " .
                 "(SELECT (`payment_amount`) AS `payout`, `id`, 'HIPAY' AS `type` FROM `shop_credit_hipay_histories`) " .
              "UNION " .
                 "(SELECT (`payment_amount`) AS `payout`, `id`, 'PAYSAFECARD' AS `type` FROM `shop_credit_paysafecard_histories`) " .
            ") `history` ON `shop_credit_histories`.`transaction_id` = `history`.`id` AND `shop_credit_histories`.`transaction_type` = `history`.`type` " .
            $where,
            [$from, $to]
        )[0]->profit;
    }
}
