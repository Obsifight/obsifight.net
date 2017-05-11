<?php
namespace Tests\Feature;

/*class Youtube {
  public $channels;
  public function __construct()
  {
    $this->channels = new YoutubeChannels();
  }
}*/
class YoutubeChannels {
  public function __construct($subs, $id)
  {
    $this->subs = $subs;
    $this->id = $id;
  }
  public function listChannels()
  {
    return new YoutubeChannelsItems($this->subs, $this->id);
  }
}
class YoutubeChannelsItems {
  public function __construct($subs, $id)
  {
    $this->subs = $subs;
    $this->id = $id;
  }
  public function getItems()
  {
    return [new YoutubeChannel($this->subs, $this->id)];
  }
}
class YoutubeChannel {
  public function __construct($subs, $id)
  {
    $this->subs = $subs;
    $this->id = $id;
  }
  public function getStatistics()
  {
    return new YoutubeChannelStats($this->subs, $this->id);
  }
  public function getId()
  {
    return $this->id;
  }
}
class YoutubeChannelStats {
  public function __construct($subs, $id)
  {
    $this->subs = $subs;
    $this->id = $id;
  }
  public function getSubscriberCount()
  {
    return $this->subs;
  }
}

use Tests\TestCase;
use Illuminate\Http\Response;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

use Illuminate\Support\Facades\Mail;

class GoogleControllerTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingGoogleTablesSeeder']);
    \Artisan::call('db:seed', ['--class' => 'PermissionsTablesSeeder']);
  }

  public function testAuthUnlogged()
  {
    $response = $this->call('GET', '/user/socials/google/link');
    $response->assertStatus(302);
  }

  public function testAuthWithoutPermission()
  {
    $user = \App\User::find(3);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/google/link');
    $response->assertStatus(403);
  }

  public function testAuthWithoutCode()
  {
    // mock google
    $googleClient = $this->getMockBuilder(\Google_Client::class)
                         ->setMethods(['setClientId', 'setClientSecret', 'setScopes', 'setRedirectUri', 'createAuthUrl'])
                         ->getMock();
    $googleClient->expects($this->once())
        ->method('createAuthUrl')
        ->willReturn('https://accounts.google.com/o/oauth2/auth');
    $this->app->instance('\Google_Client', $googleClient);

    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/google/link');
    $response->assertStatus(302);
    $this->assertContains('https://accounts.google.com/o/oauth2/auth', $response->headers->get('location'));
  }

  public function testAuthWithAnInvalidCode()
  {
    // mock google
    $googleClient = $this->getMockBuilder(\Google_Client::class)
                         ->setMethods(['setClientId', 'setClientSecret', 'setScopes', 'setRedirectUri', 'authenticate', 'createAuthUrl'])
                         ->getMock();
    $googleClient->expects($this->once())
        ->method('authenticate')
        ->willReturn(new \Exception('Invalid code'));
    $googleClient->expects($this->once())
        ->method('createAuthUrl')
        ->willReturn('https://accounts.google.com/o/oauth2/auth');
    $this->app->instance('\Google_Client', $googleClient);

    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/google/link?code=invalid');
    $response->assertStatus(302);
    $this->assertContains('https://accounts.google.com/o/oauth2/auth', $response->headers->get('location'));
  }

  public function testAuthWithInvalidAccessToken()
  {
    // mock google
    $googleClient = $this->getMockBuilder(\Google_Client::class)
                         ->setMethods(['setClientId', 'setClientSecret', 'setScopes', 'setRedirectUri', 'authenticate', 'createAuthUrl', 'getAccessToken'])
                         ->getMock();
    $googleClient->expects($this->once())
        ->method('authenticate')
        ->willReturn(true);
    $googleClient->expects($this->once())
        ->method('createAuthUrl')
        ->willReturn('https://accounts.google.com/o/oauth2/auth');
    $googleClient->expects($this->once())
        ->method('getAccessToken')
        ->willReturn(false);
    $this->app->instance('\Google_Client', $googleClient);

    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/google/link?code=valid');
    $response->assertStatus(302);
    $this->assertContains('https://accounts.google.com/o/oauth2/auth', $response->headers->get('location'));
  }

  public function testAuthWithChannelUnderSevenHundredFiftySubs()
  {
    // mock google
    $googleClient = $this->getMockBuilder(\Google_Client::class)
                         ->setMethods(['setClientId', 'setClientSecret', 'setScopes', 'setRedirectUri', 'authenticate', 'createAuthUrl', 'getAccessToken'])
                         ->getMock();
    $googleClient->expects($this->once())
        ->method('authenticate')
        ->willReturn(true);
    $googleClient->expects($this->once())
        ->method('getAccessToken')
        ->willReturn(true);
    $this->app->instance('\Google_Client', $googleClient);

    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = new YoutubeChannels(100, 'fake-id');
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/google/link?code=valid');
    $response->assertStatus(302);
    $this->assertContains('/user', $response->headers->get('location'));
    $response->assertSessionHas('flash.error', __('user.profile.socials.youtube.link.error.subs'));
  }

  public function testAuthWithChannelAlreadyLinked()
  {
    // mock google
    $googleClient = $this->getMockBuilder(\Google_Client::class)
                         ->setMethods(['setClientId', 'setClientSecret', 'setScopes', 'setRedirectUri', 'authenticate', 'createAuthUrl', 'getAccessToken'])
                         ->getMock();
    $googleClient->expects($this->once())
        ->method('authenticate')
        ->willReturn(true);
    $googleClient->expects($this->once())
        ->method('getAccessToken')
        ->willReturn(true);
    $this->app->instance('\Google_Client', $googleClient);

    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = new YoutubeChannels('800', 'user-2-channel-id');
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/google/link?code=valid');
    $response->assertStatus(302);
    $this->assertContains('/user', $response->headers->get('location'));
    $response->assertSessionHas('flash.error', __('user.profile.socials.youtube.link.error.already'));
  }

  public function testAuth()
  {
    // mock google
    $googleClient = $this->getMockBuilder(\Google_Client::class)
                         ->setMethods(['setClientId', 'setClientSecret', 'setScopes', 'setRedirectUri', 'authenticate', 'createAuthUrl', 'getAccessToken'])
                         ->getMock();
    $googleClient->expects($this->once())
        ->method('authenticate')
        ->willReturn(true);
    $googleClient->expects($this->once())
        ->method('getAccessToken')
        ->willReturn(true);
    $this->app->instance('\Google_Client', $googleClient);

    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = new YoutubeChannels('800', 'user-1-channel-id');
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    $user = \App\User::find(1);
    $this->be($user);

    $response = $this->call('GET', '/user/socials/google/link?code=valid');
    $response->assertStatus(302);
    $this->assertContains('/user', $response->headers->get('location'));
    $response->assertSessionHas('flash.success', __('user.profile.socials.youtube.link.success'));
    // check channel added
    $channel = \App\UsersYoutubeChannel::where('user_id', 1)->where('channel_id', 'user-1-channel-id')->where('link_ip', '127.0.0.1')->count();
    $this->assertEquals(1, $channel);
  }

}
