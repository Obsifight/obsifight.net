<?php
namespace Tests\Feature;

class GuzzleResponse {
  public function getStatusCode()
  {
    return 200;
  }
  public function getBody()
  {
    return '<br /><b>Position 14</b><br><br>Clic Sortant : 10</td></tr>';
  }
}

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Mail;

class VoteControllerTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingVoteTablesSeeder']);
  }

  public function testLoadPage()
  {
    $response = $this->get('/vote');
    $response->assertStatus(200);
  }

  public function testStepOneWithoutUsername()
  {
    $response = $this->call('POST', '/vote/step/one');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testStepOneWithUnknownUser()
  {
    $response = $this->call('POST', '/vote/step/one', ['username' => 'Testeee']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('vote.step.one.error.user'))), $response->getContent());
  }
  public function testStepOneWithAlreadyVoted()
  {
    \App\Vote::create(['user_id' => 1, 'out' => 10, 'reward_id' => 1, 'reward_getted' => 1, 'money_earned' => 0]);
    $response = $this->call('POST', '/vote/step/one', ['username' => 'Test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('vote.step.one.error.already', ['hours' => '04', 'minutes' => '00', 'seconds' => '00']))), $response->getContent());
  }
  public function testStepOneWithAlreadyVotedExpired()
  {
    $response = $this->call('POST', '/vote/step/one', ['username' => 'Test2']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('vote.step.one.success'))), $response->getContent());
    $response->assertSessionHas('vote.user.id', 2);
  }
  public function testStepOne()
  {
    $response = $this->call('POST', '/vote/step/one', ['username' => 'Test']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('vote.step.one.success'))), $response->getContent());
    $response->assertSessionHas('vote.user.id', 1);
  }

  public function testStepThreeWithoutBeAuth()
  {
    $response = $this->call('POST', '/vote/step/three', ['out' => '10']);
    $response->assertStatus(403);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('vote.step.error.unauthorized'))), $response->getContent());
  }
  public function testStepThreeWithoutOut()
  {
    $response = $this->withSession(['vote.user.id' => 1])->call('POST', '/vote/step/three');
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('form.error.fields'))), $response->getContent());
  }
  public function testStepThreeWithoutValidOut()
  {
    $response = $this->withSession(['vote.user.id' => 1])->call('POST', '/vote/step/three', ['out' => 'invalid']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('vote.step.three.error.out'))), $response->getContent());
  }
  public function testStepThree()
  {
    $guzzleClient = $this->getMockBuilder(\GuzzleHttp\Client::class)
                         ->setMethods(['get'])
                         ->getMock();
    $guzzleClient->expects($this->once())
        ->method('get')
        ->willReturn(new GuzzleResponse());
    $this->app->instance('\GuzzleHttp\Client', $guzzleClient);

    $response = $this->withSession(['vote.user.id' => 1])->call('POST', '/vote/step/three', ['out' => '10']);
    $response->assertStatus(200);
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('vote.step.three.success'))), $response->getContent());
    $response->assertSessionHas('vote.user.id', 1);
    $response->assertSessionHas('vote.valid', true);
    $response->assertSessionHas('vote.out', '10');
  }

  public function testStepFourWithoutBeAuth()
  {
    $response = $this->call('POST', '/vote/step/four', ['type' => 'after']);
    $response->assertStatus(403);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('vote.step.error.unauthorized'))), $response->getContent());
  }
  public function testStepFourWithoutType()
  {
    $response = $this->withSession(['vote.user.id' => 1])->call('POST', '/vote/step/four');
    $response->assertStatus(400);
  }
  public function testStepFourWithInvalidType()
  {
    $response = $this->withSession(['vote.user.id' => 1])->call('POST', '/vote/step/four', ['type' => 'invalid']);
    $response->assertStatus(400);
  }
  public function testStepFourWithoutValidVote()
  {
    $response = $this->withSession(['vote.user.id' => 1])->call('POST', '/vote/step/four', ['type' => 'after']);
    $response->assertStatus(403);
    $this->assertEquals(json_encode(array('status' => false, 'error' => __('vote.step.error.valid'))), $response->getContent());
  }
  public function testStepFourNowWithProblem()
  {
    // TODO
  }
  public function testStepFourNow()
  {
    // TODO
  }
  public function testStepFourAfter()
  {
    $response = $this->withSession(['vote' => ['out' => 10, 'user' => ['id' => 1], 'valid' => true]])->call('POST', '/vote/step/four', ['type' => 'after']);
    $response->assertStatus(200);
    // Check history
    $vote = \App\Vote::where('user_id', 1)->where('out', 10)->where('reward_getted', 0)->first();
    $this->assertEquals(1, count($vote));
    // check money
    $user = \App\User::find(1);
    $this->assertEquals(10 + $vote->money_earned, $user->money);
    // check message
    $this->assertEquals(json_encode(array('status' => true, 'success' => __('vote.step.four.success.after', ['reward' => $vote->reward->name, 'money_earned' => round($vote->money_earned)]))), $response->getContent());
    // check session
    $response = $this->call('POST', '/vote/step/four', ['type' => 'after']);
    $response->assertStatus(403);
  }

  public function testGetRewardWaitedUnlogged()
  {
    $response = $this->call('GET', '/vote/reward/get/waited');
    $response->assertStatus(302);
  }
  public function testGetRewardWaitedWithoutRewardWaited()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('GET', '/vote/reward/get/waited');
    $response->assertStatus(404);
  }
  public function testGetRewardWaitedWithServerProblem()
  {
    // TODO
  }
  public function testGetRewardWaited()
  {
    $user = \App\User::find(2);
    $this->be($user);

    $response = $this->call('GET', '/vote/reward/get/waited');
    $response->assertStatus(302);
    $this->assertContains('/user', $response->headers->get('location'));
    $response->assertSessionHas('flash.success', __('vote.rewards.get.success', ['reward' => 'Reward#1']));
    // get vote
    $vote = \App\Vote::where('user_id', 2)->where('reward_id', 1)->where('reward_getted', 1)->where('id', 1)->count();
    $this->assertEquals(1, $vote);
  }
}
