<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  protected $casts = [
    'vars' => 'array',
  ];

  public function user()
  {
    return $this->belongsTo('App\User', 'user_id');
  }

  static public function getUnseen($userId)
  {
    $query = self::where('user_id', $userId)->where('seen', false);
    $notifications = $query->get();
    $query->where('auto_seen', 1)->update(['seen' => 1]); // set as seen
    return array_map('self::translate', $notifications->toArray());
  }

  static public function translate($notification)
  {
    $message = __($notification['key'], $notification['vars']);
    return ['type' => $notification['type'], 'message' => $message, 'auto_seen' => $notification['auto_seen'], 'id' => $notification['id']];
  }
}
