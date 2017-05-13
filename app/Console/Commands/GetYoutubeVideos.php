<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GetYoutubeVideos extends Command
{
    protected $youtube;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'youtube:videos';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get Youtube videos';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
      $this->info('Starting task...');

      // INIT GOOGLE API
      $client = resolve('\Google_Client');
      $client->setDeveloperKey(env('GOOGLE_DEVELOPER_KEY'));
      $this->youtube = \App::makeWith('\Google_Service_YouTube', ['client' => $client]);

      // GET ALL USERS CHANNELS
      $channels = \App\UsersYoutubeChannel::get();
      $channelsCount = count($channels);
      $this->info("$channelsCount channels found!");

      // GET VIDEOS FROM EACH
      $i = 0;
      foreach ($channels as $channel) {
        $i++;
        $this->info("[$i/$channelsCount] Channel $channel->channel_id");

        // Get user's videos playlist id
        $playlists = $this->youtube->channels->listChannels('contentDetails', array(
          'id' => $channel->channel_id
        ));
        $playlistId = $playlists->getItems()[0]->getContentDetails()->getRelatedPlaylists()->uploads;

        // Get user's videos
        $videosList = $this->__getUploadsFromUploadsPlaylist($playlistId, $channel);

        // save
        if (!empty($videosList))
          foreach ($videosList as $video) {
            \App\UsersYoutubeChannelVideo::updateOrCreate(['id' => $video['id']], $video);
          }
      }
    }

    private function __getUploadsFromUploadsPlaylist($playlistId, $channel)
    {
      $continue = true; // to stop the while
      $pageToken = null; // pagination
      $videos = [];
      while ($continue) {
        // GET VIDEOS FROM PLAYLIST
        $params = array(
          'playlistId' => $playlistId,
          'maxResults' => 50
        );
        if ($pageToken)
          $params['pageToken'] = $pageToken;
        // request
        $findUploadsList = $this->youtube->playlistItems->listPlaylistItems('contentDetails', $params);
        $pageToken = $findUploadsList->nextPageToken;

        // HANDLE VIDEOS
        foreach ($findUploadsList->getItems() as $item) {
          // Get data
          $video = $this->youtube->videos->listVideos('statistics,status,snippet', array(
            'id' => $item->contentDetails->videoId
          ));
          $video = $video->getItems()[0];
          $publicData = $video->getSnippet();
          // Check if public
          if ($video->status->privacyStatus !== 'public')
            continue;
          // Check if this video is too old or not
          if (strtotime($item->contentDetails->videoPublishedAt) < strtotime(env('APP_LAST_VERSION_DATE'))) {
            $continue = false;
            break;
          }
          // Try to find video into table
          $findVideoInDatabase = \App\UsersYoutubeChannelVideo::where('video_id', $item->contentDetails->videoId)->first();
          // formatting
          $data = [
            'id' => ($findVideoInDatabase && !empty($findVideoInDatabase)) ? $findVideoInDatabase->id : null,
            'channel_id' => $channel->channel_id,
            'video_id' => $item->contentDetails->videoId,
            'title' => $publicData->localized->title,
            'description' => $publicData->localized->description,
            'views_count' => $video->statistics->viewCount,
            'likes_count' => $video->statistics->likeCount,
            'thumbnail_link' => $publicData->thumbnails->medium->url,
            'publication_date' => date('Y-m-d H:i:s', strtotime($item->contentDetails->videoPublishedAt)),
            'eligible' => false
          ];
          // check eligible
          if (preg_match('/obsifight/im', $data['title'])) // need contains obsifight in title
            if (preg_match('/obsifight/im', $data['description'])) // need contains obsifight in description
              if (preg_match('/obsifight\.(fr|net)/im', $data['description'])) // need contains link to obsifight.net or obsifight.fr
                if ($data['views_count'] >= 100) // need more than 100 views
                  if (strtotime('+7 days', strtotime($data['publication_date'])) > time()) // upload last 7 days
                    $data['eligible'] = true;
          // add to result
          $videos[] = $data;
        }
        // Stop if haven't a pageToken
        if ($pageToken === null)
          $continue = false;
      }
      return $videos;
    }
}
