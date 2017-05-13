<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Mail;

class TwitterControllerTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingTwitterTablesSeeder']);
    \Artisan::call('db:seed', ['--class' => 'PermissionsTablesSeeder']);
  }

  public function testAuthUnlogged()
  {
    $response = $this->call('GET', '/user/socials/twitter/link');
    $response->assertStatus(302);
  }
  public function testAuthWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/twitter/link');
    $response->assertStatus(403);
  }
  public function testAuth()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/twitter/link');
    $response->assertStatus(302);
    $endpoint = 'http://api.obsifight.net/socials/twitter/authorization/request?';
    $params = [
      'userId' => 1,
      'callback' => url('/user/socials/twitter/link/success'),
      'notification' => url('/user/socials/twitter/link/callback'),
      'authKey' => hash('sha256', $user->password)
    ];
    $url = $endpoint . http_build_query($params);
    $this->assertEquals($url, $response->headers->get('location'));
  }

  public function testSuccessUnlogged()
  {
    $response = $this->call('GET', '/user/socials/twitter/link/success');
    $response->assertStatus(302);
  }
  public function testSuccessWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/twitter/link/success');
    $response->assertStatus(403);
  }
  public function testSuccess()
  {
    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/twitter/link/success?screen_name=Test');
    $response->assertStatus(302);
    $this->assertContains('/user', $response->headers->get('location'));
    $response->assertSessionHas('flash.success', __('user.profile.socials.twitter.link.success', ['screen_name' => 'Test']));
  }

  public function testCallbackWithoutAccessToken()
  {
    $response = $this->call('POST', '/user/socials/twitter/link/callback');
    $response->assertStatus(400);
  }
  public function testCallbackWithoutAccessSecret()
  {
    $response = $this->call('POST', '/user/socials/twitter/link/callback', ['accessToken' => 'test']);
    $response->assertStatus(400);
  }
  public function testCallbackWithoutUser()
  {
    $response = $this->call('POST', '/user/socials/twitter/link/callback', ['accessToken' => 'test', 'accessSecret' => 'test']);
    $response->assertStatus(400);
  }
  public function testCallbackWithoutUserId()
  {
    $response = $this->call('POST', '/user/socials/twitter/link/callback', ['accessToken' => 'test', 'accessSecret' => 'test', 'user' => ['id' => 10, 'screen_name' => 'Test']]);
    $response->assertStatus(400);
  }
  public function testCallbackWithoutAuthKey()
  {
    $response = $this->call('POST', '/user/socials/twitter/link/callback', ['accessToken' => 'test', 'accessSecret' => 'test', 'user' => ['id' => 10, 'screen_name' => 'Test'], 'userId' => 1]);
    $response->assertStatus(400);
  }
  public function testCallbackWithInvalidAuthKey()
  {
    $response = $this->call('POST', '/user/socials/twitter/link/callback', ['accessToken' => 'test', 'accessSecret' => 'test', 'user' => ['id' => 10, 'screen_name' => 'Test'], 'userId' => 1, 'authKey' => 'invalid']);
    $response->assertStatus(403);
  }
  public function testCallbackWhenTwitterAccountAlreadyLinked()
  {
    $response = $this->call('POST', '/user/socials/twitter/link/callback', ['accessToken' => 'test', 'accessSecret' => 'test', 'user' => ['id' => 202, 'screen_name' => 'Test'], 'userId' => 1, 'authKey' => hash('sha256', 'dd202cf35d550d12a536a277c8ada507159c7a05')]);
    $response->assertStatus(403);
  }
  public function testCallback()
  {
    $response = $this->call('POST', '/user/socials/twitter/link/callback', ['accessToken' => 'test2', 'accessSecret' => 'test', 'user' => ['id' => 203, 'screen_name' => 'Test'], 'userId' => 1, 'authKey' => hash('sha256', 'dd202cf35d550d12a536a277c8ada507159c7a05')]);
    $response->assertStatus(200);
    // check db
    $twitterAccountCount = \App\UsersTwitterAccount::where('user_id', 1)->where('twitter_id', 203)->where('screen_name', 'Test')->where('access_secret', 'test')->where('access_token', 'test2')->count();
    $this->assertEquals(1, $twitterAccountCount);
    $this->assertEquals(2, \App\UsersTwitterAccount::count());
  }
  public function testCallbackWhenAnotherTwitterAccountAlreadyLinked()
  {
    $response = $this->call('POST', '/user/socials/twitter/link/callback', ['accessToken' => 'test2', 'accessSecret' => 'test', 'user' => ['id' => 203, 'screen_name' => 'Test'], 'userId' => 2, 'authKey' => hash('sha256', 'ccf689101ea907d07be40d597179860ddf59876e')]);
    $response->assertStatus(200);
    // check db
    $twitterAccountCount = \App\UsersTwitterAccount::where('user_id', 2)->where('twitter_id', 203)->where('screen_name', 'Test')->where('access_secret', 'test')->where('access_token', 'test2')->count();
    $this->assertEquals(1, $twitterAccountCount);
    $this->assertEquals(1, \App\UsersTwitterAccount::count());
  }
}
