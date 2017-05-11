<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersYoutubeChannel extends Model
{
  public function user()
  {
    return $this->belongsTo('App\User', 'user_id');
  }
}
