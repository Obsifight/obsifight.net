<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use GrahamCampbell\Markdown\Facades\Markdown;

class WikiArticle extends Model
{
  public function category()
  {
    return $this->belongsTo('App\WikiCategory', 'category_id');
  }
  public function getRouteKeyName()
  {
    return 'slug';
  }

  public function getContentAttribute($value)
  {
    return Markdown::convertToHtml($value);
  }
}
