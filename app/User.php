<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class User extends Model
{
  protected $fillable = ['username', 'email', 'password', 'ip'];

  static public function hash($password, $username)
  {
    return sha1($username . 'PApVSuS8hDUEsOEP0fWZESmODaHkXVst27CTnYMM' . $password);
  }
}
