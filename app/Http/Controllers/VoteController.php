<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\User;
use App\Vote;
use \Cache;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
  public function __construct()
  {
    \Carbon\Carbon::setLocale(\Config::get('app.locale'));
    \Carbon\Carbon::setToStringFormat('d/m/Y à H:i:s');
  }

  public function index(Request $request)
  {
    // Ranking
    $kits = \App\VoteKit::get();
    $ranking = Vote::where('created_at', '>=', date('Y-m-01 00:00:00'))->where('created_at', '<=', date('Y-m-01 00:00:00', strtotime('+1 month')))->groupBy('user_id')->select('user_id')->selectRaw('COUNT(*) AS votes_count')->orderBy('votes_count', 'DESC')->limit(count($kits))->get();
    // Rewards
    $rewards = \App\VoteReward::get();
    // View
    return view('vote/index', compact('ranking', 'kits', 'rewards'));
  }

  public function stepOne(Request $request)
  {
    if (!$request->has('username'))
      return response()->json([
        'status' => false,
        'error' => __('form.error.fields')
      ]);
    // Check if user exist
    $user = User::where('username', $request->input('username'))->first();
    if (!$user || empty($user))
      return response()->json([
        'status' => false,
        'error' => __('vote.step.one.error.user')
      ]);

    // check if user can vote
    $findVote = Vote::where('user_id', $user->id)->where('created_at', '>=', date('Y-m-d H:i:s', strtotime('- ' . env('VOTE_TIME') . ' minutes')))->first();
    if ($findVote && !empty($findVote)) {
      $nextVote = $findVote->created_at->addMinutes(env('VOTE_TIME'));
      $diff = \Carbon\Carbon::now()->diffInSeconds($nextVote);
      $diff = explode(':', gmdate('H:i:s', $diff));
      return response()->json([
        'status' => false,
        'error' => __('vote.step.one.error.already', ['hours' => $diff[0], 'minutes' => $diff[1], 'seconds' => $diff[2]])
      ]);
    }

    // Save user
    $request->session()->put('vote.user.id', $user->id);

    // Success
    return response()->json([
      'status' => true,
      'success' => __('vote.step.one.success')
    ]);
  }

  public function stepThree(Request $request)
  {
    if (!$request->has('out'))
      return response()->json([
        'status' => false,
        'error' => __('form.error.fields')
      ]);

    // Check if out is valid
    $client = resolve('\GuzzleHttp\Client');
    $result = $client->get(env('VOTE_OUT_URL'));
    if ($result->getStatusCode() === 200) { // OK
      $content = (string) $result->getBody();
      $out = substr($content, strpos($content, 'Clic Sortant : ')); // Cut before 'Clic Sortant : '
      $out = substr($out, strlen('Clic Sortant : ')); // Remove this sentence
      $out = substr($out, 0, strpos($out, '</td>')); // Cut after out number
      $out = intval($out);

      // Check out request
      if ($out !== intval($request->get('out')))
        return response()->json([
          'status' => false,
          'error' => __('vote.step.three.error.out')
        ]);
    }

    // Save valid
    $request->session()->put('vote.valid', true);
    $request->session()->put('vote.out', (isset($out) ? $out : 0));

    // Success
    return response()->json([
      'status' => true,
      'success' => __('vote.step.three.success')
    ]);
  }

  public function stepFour(Request $request)
  {
    if (!$request->has('type') || !in_array($request->input('type'), ['now', 'after']))
      return abort(400);
    if (!$request->session()->has('vote.valid'))
      return response()->json(['status' => false, 'error' => __('vote.step.error.valid')], 403);
    $reward_getted = false;

    // get reward
    $reward = \App\VoteReward::getRandom();

    // try to give if type === now
    if ($request->input('type') === 'now') {
      // TODO: command to server
      // if fail, set $reward_getted = false, else set $reward_getted = true
    }

    // add money
    $money_earned = $this->random(['1' => 10, '2' => 20, '3' => 30, '4' => 15, '5' => 10, '6' => 4, '7' => 3, '8' => 3, '9' => 3, '10' => 2], 100);
    $request->user->money = $request->user->money + floatval($money_earned);
    $request->user->save();

    // add vote
    $vote = new Vote();
    $vote->user_id = $request->user->id;
    $vote->out = $request->session()->get('vote.out');
    $vote->reward_id = $reward->id;
    $vote->reward_getted = $reward_getted;
    $vote->money_earned = $money_earned;
    $vote->save();

    // success
    $request->session()->forget('vote');
    return response()->json([
      'status' => true,
      'success' => __(($reward_getted ? 'vote.step.four.success.now' : 'vote.step.four.success.after'), ['reward' => $reward->name, 'money_earned' => $money_earned])
    ]);
  }

  public function getRewardWaited(Request $request)
  {
    // Find vote
    $vote = Vote::where('user_id', Auth::user()->id)->where('reward_getted', 0)->firstOrFail();

    // TODO: Give

    // Update vote
    $vote->reward_getted = 1;
    $vote->save();

    // Success & redirect
    return redirect('/user')->with('flash.success', __('vote.rewards.get.success', ['reward' => $vote->reward->name]));
  }

  public function getRPGParadizePosition(Request $request)
  {
    // cache
    if (Cache::has('vote.position'))
      return response()->json(['status' => true, 'position' => Cache::get('vote.position')]);
    // get
    $client = resolve('\GuzzleHttp\Client');
    $result = $client->get(env('VOTE_OUT_URL'));
    if ($result->getStatusCode() === 200) { // OK
      $content = (string) $result->getBody();
      $position = substr($content, strpos($content, 'Position ')); // Cut before 'Position : '
      $position = substr($position, strlen('Position ')); // Remove this sentence
      $position = substr($position, 0, strpos($position, '</b>')); // Cut after out number
      $position = intval($position);
      // store
      Cache::put('vote.position', $position, 120); // 2 hours
      return response()->json(['status' => true, 'position' => $position]);
    }
    return response()->json(['status' => false]);
  }

  public function getRewardKit(Request $request)
  {
    $kit = \App\VoteUserKit::where('user_id', Auth::user()->id)->firstOrFail();
    // TODO: give kit
    // remove kit
    $kit->delete();
    $notification = \App\Notification::where('user_id', Auth::user()->id)->where('type', 'info')->where('key', 'vote.reset.kit.get')->where('seen', 0)->where('auto_seen', 0)->update(['seen' => 1]);
    // redirect
    return redirect('/user')->with('flash.success', __('vote.reset.kit.get.success'));
  }
}
