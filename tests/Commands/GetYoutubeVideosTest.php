<?php
namespace Tests\Feature;

use Tests\TestCase;
use \Artisan as Artisan;

use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class YoutubeChannelsTest {
  public $uploads = 'playlist-id';
  public static $nextPageToken = null;

  public function __construct($videos, $pageToken = null)
  {
    $this->videos = $videos;
    self::$nextPageToken = $pageToken;
  }
  public function listChannels()
  {
    return new YoutubeChannelsTest($this->videos);
  }
  public function getItems()
  {
    return [new YoutubeChannelsTest($this->videos)];
  }
  public function getContentDetails()
  {
    return new YoutubeChannelsTest($this->videos);
  }
  public function getRelatedPlaylists()
  {
    return new YoutubeChannelsTest($this->videos);
  }
  public function listPlaylistItems()
  {
    return new YoutubeChannelPlaylist($this->videos);
  }
  public function listVideos($string, $video)
  {
    return new YoutubeChannelVideos($this->videos, $video['id']);
  }
}
class YoutubeChannelPlaylist {
  public function __construct($videos, $pageToken = null)
  {
    $this->videos = $videos;
    $this->nextPageToken = $pageToken;
  }
  public function __get($property)
  {
    if ($property === 'nextPageToken') {
      $data = YoutubeChannelsTest::$nextPageToken;
      YoutubeChannelsTest::$nextPageToken = null;
    } else {
      $data = $this->{$property};
    }
    return $data;
  }
  public function getItems()
  {
    $data = [];
    foreach ($this->videos as $video) {
      $data[] = (object)[
        'contentDetails' => (object)[
          'videoId' => $video['video_id'],
          'videoPublishedAt' => $video['publication_date']
        ]
      ];
    }
    return $data;
  }
}
class YoutubeChannelVideos {
  public function __construct($videos, $videoId)
  {
    $this->videos = $videos;
    $this->videoId = $videoId;
  }
  public function getItems()
  {
    $data = [];
    foreach ($this->videos as $video) {
      if ($video['video_id'] === $this->videoId)
        $data[] = new YoutubeChannelVideo($video);
    }
    return $data;
  }
}
class YoutubeChannelVideo {
  public function __construct($video)
  {
    $this->video = $video;
    $this->status = (object)[
      'privacyStatus' => $video['privacyStatus']
    ];
    $this->statistics = (object)[
      'viewCount' => $this->video['views'],
      'likeCount' => $this->video['likes']
    ];
  }
  public function getSnippet()
  {
    return (object)[
      'localized' => (object)[
        'title' => $this->video['title'],
        'description' => $this->video['description']
      ],
      'thumbnails' => (object)[
        'medium' => (object)[
          'url' => $this->video['thumbnail_link']
        ]
      ]
    ];
  }
}

class GetYoutubeVideosTest extends TestCase
{
  use DatabaseMigrations;

  public function setUp()
  {
    parent::setUp();
    \Artisan::call('db:seed', ['--class' => 'TestingGoogleTablesSeeder']);
    \Artisan::call('db:seed', ['--class' => 'PermissionsTablesSeeder']);
  }

  // NO CHANNELS
  public function testWithoutChannels()
  {
    \App\UsersYoutubeChannel::find(1)->delete();
    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('0 channels found!', $resultAsText);
  }

  // PAGINATION
  public function testWithOneVideo()
  {
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([['title' => 'Ma vidéo', 'description' => 'Ma description', 'views' => 10, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => '2017-03-02 20:10:30', 'video_id' => 'fake-id', 'privacyStatus' => 'public']]);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);

    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo')->where('description', 'Ma description')->where('views_count', 10)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', '2017-03-02 20:10:30')->where('eligible', 0)->count();
    $this->assertEquals(1, $videoCount);
  }
  public function testWithPageToken()
  {
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([
      ['title' => 'Ma vidéo', 'description' => 'Ma description', 'views' => 10, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => '2017-03-02 20:10:30', 'video_id' => 'fake-id', 'privacyStatus' => 'public'],
      ['title' => 'Ma vidéo 2', 'description' => 'Ma description 2', 'views' => 300, 'likes' => 20, 'thumbnail_link' => 'http://link2.fr', 'publication_date' => '2017-03-02 20:10:30', 'video_id' => 'fake-id-2', 'privacyStatus' => 'public']
    ], 2);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);
    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo')->where('description', 'Ma description')->where('views_count', 10)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', '2017-03-02 20:10:30')->where('eligible', 0)->count();
    $this->assertEquals(1, $videoCount);
    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id-2')->where('title', 'Ma vidéo 2')->where('description', 'Ma description 2')->where('views_count', 300)->where('likes_count', 20)->where('thumbnail_link', 'http://link2.fr')->where('publication_date', '2017-03-02 20:10:30')->where('eligible', 0)->count();
    $this->assertEquals(1, $videoCount);
  }

  // CONDITIONS
  public function testWithPrivateVideo()
  {
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([['title' => 'Ma vidéo', 'description' => 'Ma description', 'views' => 10, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => '2017-03-02 20:10:30', 'video_id' => 'fake-id', 'privacyStatus' => 'private']]);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);

    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo')->where('description', 'Ma description')->where('views_count', 10)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', '2017-03-02 20:10:30')->where('eligible', 0)->count();
    $this->assertEquals(0, $videoCount);
  }
  public function testWithOldVideo()
  {
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([['title' => 'Ma vidéo', 'description' => 'Ma description', 'views' => 10, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => '2016-03-02 20:10:30', 'video_id' => 'fake-id', 'privacyStatus' => 'public']]);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);

    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo')->where('description', 'Ma description')->where('views_count', 10)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', '2017-03-02 20:10:30')->where('eligible', 0)->count();
    $this->assertEquals(0, $videoCount);
  }

  // ELIGIBLE
  public function testWithUneligibleVideo()
  {
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([['title' => 'Ma vidéo', 'description' => 'Ma description', 'views' => 10, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => '2017-03-02 20:10:30', 'video_id' => 'fake-id', 'privacyStatus' => 'public']]);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);

    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo')->where('description', 'Ma description')->where('views_count', 10)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', '2017-03-02 20:10:30')->where('eligible', 0)->count();
    $this->assertEquals(1, $videoCount);
  }
  public function testWithVideoWithObsifightInTitle()
  {
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([['title' => 'Ma vidéo sur ObsiFight', 'description' => 'Ma description', 'views' => 10, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => '2017-03-02 20:10:30', 'video_id' => 'fake-id', 'privacyStatus' => 'public']]);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);

    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo sur ObsiFight')->where('description', 'Ma description')->where('views_count', 10)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', '2017-03-02 20:10:30')->where('eligible', 0)->count();
    $this->assertEquals(1, $videoCount);
  }
  public function testWithVideoWithObsifightInDescription()
  {
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([['title' => 'Ma vidéo sur ObsiFight', 'description' => 'Ma description sur ObsiFight', 'views' => 10, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => '2017-03-02 20:10:30', 'video_id' => 'fake-id', 'privacyStatus' => 'public']]);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);

    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo sur ObsiFight')->where('description', 'Ma description sur ObsiFight')->where('views_count', 10)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', '2017-03-02 20:10:30')->where('eligible', 0)->count();
    $this->assertEquals(1, $videoCount);
  }
  public function testWithVideoWithObsifightLinkInDescription()
  {
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([['title' => 'Ma vidéo sur ObsiFight', 'description' => 'Ma description sur ObsiFight http://obsifight.net', 'views' => 10, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => '2017-03-02 20:10:30', 'video_id' => 'fake-id', 'privacyStatus' => 'public']]);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);

    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo sur ObsiFight')->where('description', 'Ma description sur ObsiFight http://obsifight.net')->where('views_count', 10)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', '2017-03-02 20:10:30')->where('eligible', 0)->count();
    $this->assertEquals(1, $videoCount);
  }
  public function testWithVideoWithPublicationDateUnderSevenDays()
  {
    $date = date('Y-m-d H:i:s', strtotime('-3 days'));
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([['title' => 'Ma vidéo sur ObsiFight', 'description' => 'Ma description sur ObsiFight http://obsifight.net', 'views' => 10, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => $date, 'video_id' => 'fake-id', 'privacyStatus' => 'public']]);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);

    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo sur ObsiFight')->where('description', 'Ma description sur ObsiFight http://obsifight.net')->where('views_count', 10)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', $date)->where('eligible', 0)->count();
    $this->assertEquals(1, $videoCount);
  }
  public function testWithVideoWithMoreThanHundredViews()
  {
    $date = date('Y-m-d H:i:s', strtotime('-3 days'));
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([['title' => 'Ma vidéo sur ObsiFight', 'description' => 'Ma description sur ObsiFight http://obsifight.net', 'views' => 200, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => $date, 'video_id' => 'fake-id', 'privacyStatus' => 'public']]);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);

    $videoCount = \App\UsersYoutubeChannelVideo::where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo sur ObsiFight')->where('description', 'Ma description sur ObsiFight http://obsifight.net')->where('views_count', 200)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', $date)->where('eligible', 1)->count();
    $this->assertEquals(1, $videoCount);
  }

  // SAVE
  public function testWithVideoAlreadySave()
  {
    $date = date('Y-m-d H:i:s', strtotime('-10 days'));
    \App\UsersYoutubeChannelVideo::truncate();
    \App\UsersYoutubeChannelVideo::insert([
      [
        'id' => 1,
        'title' => 'Ma vidéo sur ObsiFight',
        'description' => 'Ma description sur ObsiFight http://obsifight.net',
        'views_count' => 200,
        'likes_count' => 20,
        'thumbnail_link' => 'http://link.fr',
        'publication_date' => $date,
        'video_id' => 'fake-id',
        'channel_id' => 'user-2-channel-id',
        'eligible' => 1
      ]
    ]);
    $youtubeClient = $this->getMockBuilder(\Google_Service_YouTube::class)
                         ->setMethods(null)
                         ->disableOriginalConstructor()
                         ->getMock();
    $youtubeClient->channels = $youtubeClient->playlistItems = $youtubeClient->videos = new YoutubeChannelsTest([['title' => 'Ma vidéo sur ObsiFight modifiée', 'description' => 'Ma description sur ObsiFight http://obsifight.net', 'views' => 200, 'likes' => 20, 'thumbnail_link' => 'http://link.fr', 'publication_date' => $date, 'video_id' => 'fake-id', 'privacyStatus' => 'public']]);
    $this->app->singleton('\Google_Service_YouTube', function ($app) use ($youtubeClient) {
      return $youtubeClient;
    });

    Artisan::call('youtube:videos');
    $resultAsText = Artisan::output();

    $this->assertContains('1 channels found!', $resultAsText);
    $videoCount = \App\UsersYoutubeChannelVideo::where('id', 1)->where('channel_id', 'user-2-channel-id')->where('video_id', 'fake-id')->where('title', 'Ma vidéo sur ObsiFight modifiée')->where('description', 'Ma description sur ObsiFight http://obsifight.net')->where('views_count', 200)->where('likes_count', 20)->where('thumbnail_link', 'http://link.fr')->where('publication_date', $date)->where('eligible', 0)->count();
    $this->assertEquals(1, $videoCount);
    $this->assertEquals(1, \App\UsersYoutubeChannelVideo::count());
  }
}
