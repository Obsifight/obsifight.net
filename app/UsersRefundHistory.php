<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersRefundHistory extends Model
{
    public $timestamps = false;

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
