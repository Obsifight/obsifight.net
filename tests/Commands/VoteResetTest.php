<?php
namespace Tests\Feature;

use Tests\TestCase;
use \Artisan as Artisan;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class VoteResetTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingVoteTablesSeeder']);
    \Artisan::call('db:seed', ['--class' => 'VotesTablesSeeder']);
  }

  public function test()
  {
    \DB::table('notifications')->truncate();
    \DB::table('vote_user_kits')->truncate();
    Artisan::call('vote:reset');
    // Check notification
    $notification = \App\Notification::where('type', 'info')->where('key', 'vote.reset.kit.get')->where('vars', '{"url":"http:\/\/localhost\/vote\/reward\/kit\/get","position":1}')->where('seen', 0)->where('auto_seen', 0)->get();
    $this->assertEquals(1, count($notification));
    $this->assertEquals(3, $notification[0]->user_id);
    // Check kit
    $kit = \App\VoteUserKit::get();
    $this->assertEquals(1, count($kit));
    $this->assertEquals(3, $kit[0]->user_id);
    $this->assertEquals(1, $kit[0]->kit_id);
  }
}
