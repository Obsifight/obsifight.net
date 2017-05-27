<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class VoteReward extends Model
{
  /*
    Return a random reward based on probability
    Example:
      - Get [['id' => 1, 'probability' => 50], ['id' => 2, 'probability' => 50]]
      - probabilitySum = 100
      - Generate random number between 0 and 100 (Each number between [0, 50] and [50, 100])
  */
  static public function getRandom()
  {
    // get all
    $rewards = self::get();
    // set probability max
    $probabilitySum = 0;
    foreach ($rewards as $reward) {
      $probabilitySum += $reward->probability;
    }
    // get random
    $random = mt_rand(0, $probabilitySum);

    // get reward
    $i = 0;
    foreach ($rewards as $reward) {
      if ($random >= $i && $random < ($i+$reward->probability))
        return $reward;
      $i += $reward->probability;
    }
    if ($random === $probabilitySum)
      return $rewards->last();
  }
}
