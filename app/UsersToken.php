<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersToken extends Model
{
    private static $token;
    protected $fillable = ['used_ip'];

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    static public function generate($type, $userId, $data = null)
    {
        $token = new UsersToken();
        $token->user_id = $userId;
        $token->token = \Uuid::generate();
        $token->type = $type;
        $token->data = $data;
        self::$token = $token;
        if ($token->save())
            return $token->token;
        else
            return false;
  }

    static public function getToken()
    {
        return self::$token;
    }
}
