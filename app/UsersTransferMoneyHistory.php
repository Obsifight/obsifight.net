<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersTransferMoneyHistory extends Model
{
  public function user()
  {
    return $this->belongsTo('App\User', 'user_id');
  }
  public function receiver()
  {
    return $this->belongsTo('App\User', 'to');
  }
}
