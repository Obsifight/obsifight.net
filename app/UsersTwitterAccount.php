<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersTwitterAccount extends Model
{
  protected $fillable = ['twitter_id', 'link_ip', 'access_token', 'access_secret', 'screen_name', 'user_id'];
  public function user()
  {
    return $this->belongsTo('App\User', 'user_id');
  }
}
