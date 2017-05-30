<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class WikiCategory extends Model
{
  public function articles()
  {
    return $this->hasMany('App\WikiArticle', 'category_id');
  }
}
