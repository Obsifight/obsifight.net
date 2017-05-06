<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersToken extends Model
{
  protected $fillable = ['used_ip'];

  public function user()
  {
    return $this->belongsTo('App\User');
  }

  static public function generate($type, $userId) {
    $token = new UsersToken();
    $token->user_id = $userId;
    $token->token = \Uuid::generate();
    $token->type = $type;
    if ($token->save())
      return $token->token;
    else
      return false;
  }
}
