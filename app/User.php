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

  static public function getStatsFromUsername($username)
  {
      $body = @file_get_contents(env('DATA_SERVER_ENDPOINT') . '/users/' . $username);
      if (!$body) return false;
      $data = @json_decode($body);
      if (!$data) return false;
      if (!$data->status) return false;
      $user = $data->data;
      if (!($user->faction = \App\Faction::getFactionFromUsername($username)))
          return false;
      return ($user);
  }

    static public function getSuccessList($username)
    {
        $body = @file_get_contents(env('DATA_SERVER_ENDPOINT') . '/users/' . $username . '/success');
        if (!$body) return false;
        $data = @json_decode($body);
        if (!$data) return false;
        if (!$data->status) return false;
        $rawSuccessList = $data->data;
        $successList = [];

        foreach ($rawSuccessList as $successName => $successValue) {
            if (is_bool($successValue)) {
                $successList[] = [
                    __('stats.success.' . $successName) => $successValue,
                ];
            } else {
                $array = [];
                foreach ($successValue as $value => $percentage) {
                    $array[__('stats.success.' . $successName, ['number' => $value])] = round($percentage, 0.1);
                }
                $successList[] = $array;
            }
        }

        return $successList;
    }
}
