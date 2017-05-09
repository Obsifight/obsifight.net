<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersLoginRetry extends Model
{
  protected $fillable = ['ip', 'count'];

  public function user()
  {
    return $this->belongsTo('App\User', 'user_id');
  }
}
