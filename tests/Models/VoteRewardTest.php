<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use \App\VoteReward;

class VoteRewardTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \DB::table('vote_rewards')->truncate();
    for ($i=1; $i < 5; $i++) {
      \DB::table('vote_rewards')->insert([
        'name' => "Reward#$i",
        'probability' => 25,
        'commands' => '',
        'created_at' => date('Y-m-d H:i:s'),
        'updated_at' => date('Y-m-d H:i:s')
      ]);
    }
  }

  public function testGetRandom()
  {
    $results = [];
    // Get 1k random rewards
    for ($i=0; $i < 1000; $i++) {
      $reward = VoteReward::getRandom();
      $this->assertNotNull($reward);
      if (isset($results[$reward->id]))
        $results[$reward->id]++;
      else
        $results[$reward->id] = 1;
    }
    // Check percentage
    $percentages = [];
    foreach ($results as $id => $number) {
      $percentages[] = round(($number / 1000) * 100);
    }

    $this->assertEquals(true, (20 <= $percentages[0]) && ($percentages[0] <= 30));
    $this->assertEquals(true, (20 <= $percentages[1]) && ($percentages[1] <= 30));
    $this->assertEquals(true, (20 <= $percentages[2]) && ($percentages[2] <= 30));
    $this->assertEquals(true, (20 <= $percentages[3]) && ($percentages[3] <= 30));
  }

  public function testGetRandomWithProbabilityMaxNotEqualToHundred()
  {
    \DB::table('vote_rewards')->delete(4); // remove 1 item

    $results = [];
    // Get 1k random rewards
    for ($i=0; $i < 1000; $i++) {
      $reward = VoteReward::getRandom();
      $this->assertNotNull($reward);
      if (isset($results[$reward->id]))
        $results[$reward->id]++;
      else
        $results[$reward->id] = 1;
    }
    // Check percentage
    $percentages = [];
    foreach ($results as $id => $number) {
      $percentages[] = round(($number / 1000) * 100);
    }

    $this->assertEquals(true, (28 <= $percentages[0]) && ($percentages[0] <= 40));
    $this->assertEquals(true, (28 <= $percentages[1]) && ($percentages[1] <= 40));
    $this->assertEquals(true, (28 <= $percentages[2]) && ($percentages[2] <= 40));
  }
}
