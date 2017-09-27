<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersConnectionLog extends Model
{
  protected $fillable = ['ip', 'user_id'];

  public function user()
  {
    return $this->belongsTo('App\User', 'user_id');
  }

  public static function getWebsiteLogs($user, $limit = 8)
  {
    return self::where('user_id', $user->id)->limit($limit)->orderBy('id', 'desc')->get();
  }

  public static function getLauncherLogs($user, $limit = 8)
  {
    $model = new UsersConnectionLog;
    $model->setConnection('authentification');
    $model->setTable('loginlogs');
    return $model->where('username', $user->username)->limit($limit)->orderBy('id', 'desc')->get();
  }
}
