<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
  protected $fillable = ['username', 'email', 'password', 'ip'];

  static public function hash($password, $username)
  {
    return sha1($username . 'PApVSuS8hDUEsOEP0fWZESmODaHkXVst27CTnYMM' . $password);
  }
}
