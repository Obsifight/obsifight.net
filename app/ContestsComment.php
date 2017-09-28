<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ContestsComment extends Model
{
    public function contest()
    {
        return $this->belongsTo('App\Contest', 'contest_id');
    }
    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }
}
