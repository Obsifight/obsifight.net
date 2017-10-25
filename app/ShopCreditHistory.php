<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

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
            default:
                return NULL;
            break;
        }
        return $this->belongsTo($model, 'transaction_id');
    }
}
