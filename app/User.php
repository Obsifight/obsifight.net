<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Zizaco\Entrust\Traits\EntrustUserTrait;

class User extends Authenticatable
{
  use EntrustUserTrait;

  protected $fillable = ['username', 'email', 'password', 'ip'];

  static public function hash($password, $username)
  {
    return sha1($username . 'PApVSuS8hDUEsOEP0fWZESmODaHkXVst27CTnYMM' . $password);
  }
}
