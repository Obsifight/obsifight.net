<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Faction extends Model
{
  static public function getFromName($name)
  {
    $body = @file_get_contents(env('DATA_SERVER_ENDPOINT') . '/factions/name/' . $name);
    if (!$body) return false;
    $data = @json_decode($body);
    if (!$data) return false;
    if (isset($data->error)) return false;
    $faction = $data;

    // Add vars
    $faction->name = $faction->factionname;
    $faction->id = $faction->factionid;
    // Order members by rank
    usort($faction->members, function ($a, $b) {
      if ($a->relation === $b->relation)
        return 0;
      if ($a->relation === 'LEADER')
        return 1;
      if ($a->relation === 'OFFICER' && $b->relation === 'MEMBER')
        return 1;
    });
    $faction->members = array_reverse($faction->members);
    // Find leader
    $faction->leader = $faction->members[0];

    return $faction;
  }
}
