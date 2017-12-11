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
        $staff = \App\User::getStaff();
        return view('stats.index', compact('staff'));
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
            } catch (\xPaw\MinecraftPingException $e) {
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

    public function usersCount(Request $request)
    {
        if (!Cache::has('users.count')) {
            $count = \App\User::count();
            // Store
            Cache::put('users.count', $count, 15); // 2 hours
        }
        return response()->json(['status' => true, 'count' => Cache::get('users.count')]);
    }

    public function usersCountThisVersion(Request $request)
    {
        if (!Cache::has('users.count.version')) {
            $count = \App\UsersVersion::where('version', env('APP_VERSION_COUNT'))->count();
            // Store
            Cache::put('users.count.version', $count, 15); //  15 minutes
        }
        return response()->json(['status' => true, 'count' => Cache::get('users.count.version')]);
    }

    public function factionsCount(Request $request)
    {
        if (!Cache::has('factions.count')) {
            $count = json_decode(file_get_contents(env('DATA_SERVER_ENDPOINT') . '/factions/count'), true)['data']['count'];
            // Store
            Cache::put('factions.count', $count, 15); // 15 minute
        }
        return response()->json(['status' => true, 'count' => Cache::get('factions.count')]);
    }

    public function fightsCount(Request $request)
    {
        if (!Cache::has('fights.count')) {
            $count = json_decode(file_get_contents(env('DATA_SERVER_ENDPOINT') . '/users/count/fights'), true)['data']['count'];
            // Store
            Cache::put('fights.count', $count, 15); // 15 minute
        }
        return response()->json(['status' => true, 'count' => Cache::get('fights.count')]);
    }

    public function usersGraph(Request $request)
    {
        if (!Cache::has('users.graph')) {
            $url = 'http://players.api.obsifight.net/data?superiorDate='.date('Y-m-d%20H:i:s', strtotime('-8 days'));
            $findOnlinePlayers = @json_decode(@file_get_contents($url), true);
            $onlinePlayers = [];
            if ($findOnlinePlayers && !empty($findOnlinePlayers)) {
                foreach ($findOnlinePlayers as $key => $value) {
                    $onlinePlayers[] = [
                        (intval($value['time'])),
                        intval($value['count'])
                    ];
                }
            }
            // Store
            Cache::put('users.graph', $onlinePlayers, 120); // 2 hours
        }
        return response()->json(['status' => true, 'graph' => Cache::get('users.graph')]);
    }

    public function usersPeakGraph(Request $request)
    {
        if (!Cache::has('users.graph.peak')) {
            $peakTimes = array(
                'hours' => @json_decode(@file_get_contents('http://players.api.obsifight.net/stats/peak-times/hours'), true),
                'days' => @json_decode(@file_get_contents('http://players.api.obsifight.net/stats/peak-times/days'), true)
            );
            // Store
            Cache::put('users.graph.peak', $peakTimes, 120); // 2 hours
        }
        return response()->json(['status' => true, 'graph' => Cache::get('users.graph.peak')]);
    }

    public function usersRegisterGraph(Request $request)
    {
        if (!Cache::has('users.graph.register')) {
            $users = [];
            for ($i = 0; $i < 7; $i++)
                $users[] = \App\User::where('created_at', 'LIKE', date('Y-m-d', strtotime('-' . (6 - $i) . ' days')) . '%')->count();
            // Store
            Cache::put('users.graph.register', $users, 120); // 2 hours
        }
        return response()->json(['status' => true, 'graph' => Cache::get('users.graph.register')]);
    }

    public function usersVisitsGraph(Request $request)
    {
        if (!Cache::has('users.graph.visits')) {
            $visits = [];
            require base_path('vendor/eywek/obsifight/Google/GoogleAnalytics.php');
            $ga = new \GoogleAnalytics;
            for ($i = 0; $i < 7; $i++)
                $visits[] = intval($ga->getVisitsOf(date('Y-m-d', strtotime('-' . (6 - $i) . ' days'))));
            // Store
            Cache::put('users.graph.visits', $visits, 120); // 2 hours
        }
        return response()->json(['status' => true, 'graph' => Cache::get('users.graph.visits')]);
    }
}
