<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Vote extends Model
{
  protected $fillable = ['user_id', 'out', 'reward_id', 'reward_getted', 'money_earned', 'ip'];
  public function user()
  {
    return $this->belongsTo('App\User', 'user_id');
  }
  public function reward()
  {
    return $this->belongsTo('App\VoteReward', 'reward_id');
  }
}
