<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\User;
use Illuminate\Support\Facades\Auth;

class TwitterController extends Controller
{
  public function auth(Request $request)
  {
    $endpoint = 'http://api.obsifight.net/socials/twitter/authorization/request?';
    $params = [
      'userId' => Auth::user()->id,
      'callback' => url('/user/socials/twitter/link/success'),
      'notification' => url('/user/socials/twitter/link/callback'),
      'authKey' => hash('sha256', Auth::user()->password)
    ];
    $url = $endpoint . http_build_query($params);

    return redirect($url);
  }

  public function callback(Request $request)
  {
    if (!$request->has('accessToken') || !$request->has('accessSecret') || !$request->has('user') || !$request->has('userId') || !$request->has('authKey'))
      return abort(400);
    // Check authkey
    $user = \App\User::where('id', $request->input('userId'))->firstOrFail();
    if ($request->input('authKey') !== hash('sha256', $user->password))
      return abort(403);

    // check if twitter account is already used
    $twitterAccountCount = \App\UsersTwitterAccount::where('twitter_id', $request->input('user')['id'])->count();
    if ($twitterAccountCount > 0)
      return abort(403);

    // Save data
    \App\UsersTwitterAccount::updateOrCreate(
      ['user_id' => $request->input('userId')],
      [
        'user_id' => $request->input('userId'),
        'twitter_id' => $request->input('user')['id'],
        'screen_name' => $request->input('user')['screen_name'],
        'access_token' => $request->input('accessToken'),
        'access_secret' => $request->input('accessSecret')
      ]
    );
  }

  public function success(Request $request)
  {
    return redirect('/user')->with('flash.success', __('user.profile.socials.twitter.link.success', ['screen_name' => $request->input('screen_name')]));
  }
}
