<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;

class StatsController extends Controller
{
  public function __construct()
  {
    Carbon::setLocale(\Config::get('app.locale'));
    Carbon::setToStringFormat('d/m/Y à H:i:s');
  }

  public function index(Request $request)
  {
    return view('stats.index');
  }

  public function user(Request $request)
  {
    return view('stats.user');
  }

  public function faction(Request $request)
  {
    return view('stats.faction');
  }
}
