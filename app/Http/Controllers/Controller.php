<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class Controller extends BaseController
{
  use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

  public function random($list, $probabilityTotal) {
    $pct = 1000;
    $rand = mt_rand(0, $pct);
    $items = array();

    foreach ($list as $key => $value) {
      $items[$key] = $value / $probabilityTotal;
    }

    $i = 0;
    asort($items);

    foreach ($items as $name => $value) {
      if ($rand <= $i+=($value * $pct)) {
        $item = $name;
        break;
      }
    }
    return $item;
  }
}
