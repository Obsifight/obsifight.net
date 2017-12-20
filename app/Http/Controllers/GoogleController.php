<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
  public function __construct()
  {
    \Carbon\Carbon::setLocale(\Config::get('app.locale'));
    \Carbon\Carbon::setToStringFormat('d/m/Y à H:i:s');
  }

  public function auth(Request $request)
  {
    // INIT GOOGLE CLIENT
    $client = resolve('\Google_Client');
    $client->setClientId(env('GOOGLE_CLIENT_ID'));
    $client->setClientSecret(env('GOOGLE_CLIENT_SECRET'));
    $client->setScopes('https://www.googleapis.com/auth/youtube.readonly');
    $client->setRedirectUri($request->url());

    // REDIRECT TO GET A CODE
    if (!isset($request->code))
      return redirect($client->createAuthUrl());

    // TRY TO AUTHENTICATE
    try {
      $client->authenticate($request->code);
    } catch (Exception $e) {
      return redirect($client->createAuthUrl()); // FAILS: REDIRECT TO GET A CODE
    }

    // CHECK ACCESS TOKEN
    if (!$client->getAccessToken())
      return redirect($client->createAuthUrl()); // FAILS: REDIRECT TO GET A CODE

    // GET YOUTUBE CHANNEL INFOS
    $youtube = \App::makeWith('\Google_Service_YouTube', ['client' => $client]);
    $channels = $youtube->channels->listChannels('statistics', array(
      'mine' => true
    ));
    $channel = $channels->getItems()[0];
    $subs = intval($channel->getStatistics()->getSubscriberCount());
    $channelId = $channel->getId();

    // REDIRECT IF HAVEN'T 750 SUBS
    if ($subs < 750)
      return redirect('/user')->with('flash.error', __('user.profile.socials.youtube.link.error.subs'));

    // HAVE MORE THAN 750 SUBS, CHECK IF CHANNEL IS NOT ALREADY LINKED
    $channel = \App\UsersYoutubeChannel::where('channel_id', $channelId)->count();
    if ($channel > 0)
      return redirect('/user')->with('flash.error', __('user.profile.socials.youtube.link.error.already'));

    // SAVE
    $channel = new \App\UsersYoutubeChannel();
    $channel->user_id = Auth::user()->id;
    $channel->channel_id = $channelId;
    $channel->link_ip = $request->ip();
    $channel->save();

    $server = resolve('\Server');
    $server->sendCommand(strtr(env('YOUTUBE_RANK_CMD'), ['{PLAYER}' => Auth::user()->username]))->get();

    // REDIRECT WITH SUCCESS
    return redirect('/user')->with('flash.success', __('user.profile.socials.youtube.link.success'));
  }

  public function viewYoutubeVideos(Request $request)
  {
    // Check if have a channel
    $channel = \App\UsersYoutubeChannel::where('user_id', Auth::user()->id)->firstOrFail();
    // Get videos
    $videos = \App\UsersYoutubeChannelVideo::where('channel_id', $channel->channel_id)->get();

    return view('user.socials.youtube_videos', compact('channel', 'videos'));
  }

  public function getYoutubeVideoRemuneration(Request $request)
  {
    // Check if have a channel
    $channel = \App\UsersYoutubeChannel::where('user_id', Auth::user()->id)->firstOrFail();
    // Get video
    $video = \App\UsersYoutubeChannelVideo::where('channel_id', $channel->channel_id)->where('id', $request->id)->where('eligible', 1)->where('payed', 0)->firstOrFail();
    $remuneration = $video->remuneration;

    // Add money to user
    $currentUser = User::find(Auth::user()->id);
    $currentUser->money = ($currentUser->money + floatval($remuneration));
    $currentUser->save();

    // Set as payed
    $video->payed = true;
    $video->save();

    // Save to history
    $history = new \App\UsersYoutubeChannelVideoRemunerationHistory();
    $history->user_id = Auth::user()->id;
    $history->video_id = $video->id;
    $history->remuneration = $remuneration;
    $history->ip = $request->ip();
    $history->save();

    // redirect with success
    return redirect('/user/socials/youtube/videos')->with('flash.success', __('user.profile.socials.youtube.remuneration.success', ['remuneration' => $remuneration]));
  }
}
