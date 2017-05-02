<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
  protected $casts = [
    'vars' => 'array',
  ];

  static public function getUnseen($userId)
  {
    $query = self::where('user_id', $userId)->where('seen', false);
    $notifications = $query->get();
    $query->update(['seen' => 1]); // set as seen
    return array_map('self::translate', $notifications->toArray());
  }

  static public function translate($notification)
  {
    $message = __($notification['key'], $notification['vars']);
    return ['type' => $notification['type'], 'message' => $message];
  }
}