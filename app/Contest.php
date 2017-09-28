<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Contest extends Model
{
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
