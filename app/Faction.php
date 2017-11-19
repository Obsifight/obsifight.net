<?php

namespace App;

use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
    static public function getFromName($name)
    {
        $body = @file_get_contents(env('DATA_SERVER_ENDPOINT') . '/factions/' . $name);
        if (!$body) return false;
        $data = @json_decode($body);
        if (!$data) return false;
        if (!$data->status) return false;
        $faction = $data->data;

        // Order players by rank
        usort($faction->players, function ($a, $b) {
            if ($a->role === $b->role)
                return 0;
            if ($a->role === 'leader')
                return 1;
            if ($a->role === 'officer' && $b->role === 'member')
                return 1;
        });
        $faction->players = array_reverse($faction->players);
        // Find leader
        $faction->leader = $faction->players[0];
        // date
        $faction->created_at = new Carbon($faction->created_at);

        return $faction;
    }

    static public function getSuccessList($factionId)
    {
        $body = @file_get_contents(env('DATA_SERVER_ENDPOINT') . '/factions/' . $factionId . '/success');
        if (!$body) return false;
        $data = @json_decode($body);
        if (!$data) return false;
        if (!$data->status) return false;
        $rawSuccessList = $data->data;
        $successList = [];

        foreach ($rawSuccessList as $successName => $successValue) {
            if (is_bool($successValue)) {
                $successList[] = [
                    __('stats.success.' . $successName) => $successValue,
                ];
            } else {
                $array = [];
                foreach ($successValue as $value => $percentage) {
                    $array[__('stats.success.' . $successName, ['number' => $value])] = round($percentage, 0.1);
                }
                $successList[] = $array;
            }
        }

        return $successList;
    }

    static public function getStats($factionId)
    {
        $body = @file_get_contents(env('DATA_SERVER_ENDPOINT') . '/factions/' . $factionId . '/graph');
        if (!$body) return false;
        $data = @json_decode($body);
        if (!$data) return false;
        if (!$data->status) return false;
        $stats = $data->data;
        // Update range
        Carbon::setLocale(\Config::get('app.locale'));
        $stats->update_range = call_user_func_array([CarbonInterval::class, 'create'], explode(', ', $stats->update_range));
        // x axis
        $stats->graphs->materials->x_axis = array_map(function ($label) {
            return Carbon::parse($label)->diffForHumans();
        }, $stats->graphs->materials->x_axis);
        return $stats;
    }

    static public function getFactionFromUsername($username)
    {
        $body = @file_get_contents(env('DATA_SERVER_ENDPOINT') . '/factions/search/user/' . $username);
        if (!$body) return false;
        $data = @json_decode($body);
        if (!$data) return false;
        if (!$data->status) return false;
        return ($data->data);
    }
}
