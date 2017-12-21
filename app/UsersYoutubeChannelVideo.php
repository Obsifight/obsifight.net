<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UsersYoutubeChannelVideo extends Model
{
    protected $fillable = ['channel_id', 'video_id', 'title', 'description', 'views_count', 'likes_count', 'thumbnail_link', 'publication_date', 'eligible'];
    protected $appends = array('remuneration');
    protected $dates = ['publication_date'];

    public function channel()
    {
        return $this->belongsTo('App\UsersYoutubeChannel', 'channel_id', 'channel_id');
    }

    public function remunerationHistory()
    {
        return $this->hasOne('App\UsersYoutubeChannelVideoRemunerationHistory', 'video_id');
    }

    public function getRemunerationAttribute()
    {
        if ($this->eligible && !$this->payed)
            return 0.3 * $this->views_count + 0.5 * $this->likes_count;
        else
            return 0;
    }
}
