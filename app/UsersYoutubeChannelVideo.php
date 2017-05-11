<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersYoutubeChannelVideo extends Model
{
  protected $fillable = ['channel_id', 'video_id', 'title', 'description', 'views_count', 'likes_count', 'thumbnail_link', 'publication_date', 'eligible'];

  public function channel()
  {
    return $this->belongsTo('App\UsersYoutubeChannel', 'channel_id');
  }
}
