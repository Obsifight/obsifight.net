<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Carbon\Carbon;
use \Cache;

class StatsController extends Controller
{
  public function __construct()
  {
    Carbon::setLocale(\Config::get('app.locale'));
    Carbon::setToStringFormat('d/m/Y à H:i:s');
  }

  public function index(Request $request)
  {
      // TODO: get staff from API, generate graphs from api / database
    return view('stats.index');
  }

  public function user(Request $request)
  {
    $user = \App\User::getStatsFromUsername($request->username);
    $userDB = \App\User::where('username', $request->username)->firstOrFail();
    if (!$user)
        return abort(404);
    $user->username = $request->username;
    $user->register_date = $userDB->created_at;
    if ($user->online->last_connection > 0)
        $user->online->last_connection = Carbon::parse($user->online->last_connection);
    $user->successList = \App\User::getSuccessList($userDB->uuid);
    $user->country = 'france'; // TODO: package torann/geoip
    return view('stats.user', ['user' => $user]);
  }

  public function faction(Request $request)
  {
    $faction = \App\Faction::getFromName($request->name);
    if (!$faction)
      return abort(404);
    $faction->successList = \App\Faction::getSuccessList($faction->id);
    $faction->stats = \App\Faction::getStats($faction->id);
    return view('stats.faction', ['faction' => $faction]);
  }

  public function factionRanking(Request $request)
  {
    return view('stats.faction_ranking');
  }

  public function serverCount(Request $request)
  {
    if (!Cache::has('server.count')) {
      // Ping
      require base_path('vendor/xpaw/ping/src/MinecraftPing.php');
      require base_path('vendor/xpaw/ping/src/MinecraftPingException.php');
      try {
        $query = new \xPaw\MinecraftPing(env('MINECRAFT_PROXY_PING_IP'), env('MINECRAFT_PROXY_PING_PORT'), 1);
        $info = $query->query();
      } catch(\xPaw\MinecraftPingException $e) {
        return response()->json(['status' => false, 'count' => 0]);
      }
      $query->close();
      $count = $info['players']['online'];

      // Store
      Cache::put('server.count', $count, 1); // 1 minute
    }

    return response()->json(['status' => true, 'count' => Cache::get('server.count')]);
  }

  public function serverMax(Request $request)
  {
    if (!Cache::has('server.max')) {
      // Ping
      $file = file_get_contents('http://players.api.obsifight.net/max');
      $content = @json_decode($file, true);
      $max = @$content['max'];
      if (!$max)
        return response()->json(['status' => false, 'count' => 0]);

      // Store
      Cache::put('server.max', $max, 15); // 15 minutes
    }

    return response()->json(['status' => true, 'count' => Cache::get('server.max')]);
  }

  public function visitsCount(Request $request)
  {
    if (!Cache::has('visits.count')) {
      // Ping
      require base_path('vendor/eywek/obsifight/Google/GoogleAnalytics.php');
      $count = (new \GoogleAnalytics)->getVisitsFromTo('2015-10-05', 'today');

      // Store
      Cache::put('visits.count', $count, 120); // 2 hours
    }

    return response()->json(['status' => true, 'count' => Cache::get('visits.count')]);
  }
}
