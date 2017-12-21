<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteUserKit extends Model
{
    public function kit()
    {
        return $this->belongsTo('App\VoteKit', 'kit_id');
    }
}
