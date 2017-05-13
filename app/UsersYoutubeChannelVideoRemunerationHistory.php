<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersYoutubeChannelVideoRemunerationHistory extends Model
{
  public function user()
  {
    return $this->belongsTo('App\User', 'user_id');
  }
  public function video()
  {
    return $this->belongsTo('App\UsersYoutubeChannelVideo', 'video_id');
  }
}
