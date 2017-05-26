<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Http\Response;

use App\User;
use Illuminate\Support\Facades\Auth;

class VoteController extends Controller
{
  public function index(Request $request)
  {
    return view('vote/index');
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

    // Success
    return response()->json([
      'status' => true,
      'success' => __('vote.step.three.success')
    ]);
  }
}
