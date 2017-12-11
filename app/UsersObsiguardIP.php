<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersObsiguardIP extends Model
{
  protected $table = 'users_obsiguard_ips';

  public function user()
  {
    return $this->belongsTo('App\User', 'user_id');
  }
}
