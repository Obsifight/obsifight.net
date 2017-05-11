<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\User;
use Illuminate\Support\Facades\Auth;

class GoogleController extends Controller
{
  public function auth(Request $request)
  {
    // INIT GOOGLE CLIENT
    /*$Google_Client = resolve('\Google_Client');
    $client = new $Google_Client();*/
    $client = resolve('\Google_Client');
    //$client = new \Google_Client();
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

    // SEND COMMANDS TO SERVER

    // REDIRECT WITH SUCCESS
    return redirect('/user')->with('flash.success', __('user.profile.socials.youtube.link.success'));
  }
}
