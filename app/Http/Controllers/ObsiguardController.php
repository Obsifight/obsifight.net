<?php
namespace App\Http\Controllers;

use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\User;
use Illuminate\Support\Facades\Auth;

use Illuminate\Support\Facades\Mail;

use Carbon\Carbon;

class ObsiguardController extends Controller
{

  public function enable(Request $request)
  {
    $ip = new \App\UsersObsiguardIP();
    $ip->user_id = Auth::user()->id;
    $ip->ip = $request->ip();
    $ip->save();

    // generate token
    $code = str_random(5);
    $token = \App\UsersToken::generate('OBSIGUARD', Auth::user()->id, $code);
    $token = \App\UsersToken::where('user_id', Auth::user()->id)->where('token', $token)->first();
    $token->used_ip = $request->ip();
    $token->save();

    // add log
    $log = new \App\UsersObsiguardLog();
    $log->type = 'ENABLE';
    $log->user_id = Auth::user()->id;
    $log->ip = $request->ip();
    $log->save();

    $request->session()->put('user.obsiguard.security.code', $token->token);
    return response()->json([
      'status' => true,
      'success' => __('user.obsiguard.enable.success'),
      'data' => [
        'ip' => $ip->ip,
        'id' => $ip->id
      ]
    ]);
  }

  public function disable(Request $request)
  {
    \App\UsersObsiguardIP::where('user_id', Auth::user()->id)->delete();

    // add log
    $log = new \App\UsersObsiguardLog();
    $log->type = 'DISABLE';
    $log->user_id = Auth::user()->id;
    $log->ip = $request->ip();
    $log->save();

    return response()->json([
      'status' => true,
      'success' => __('user.obsiguard.disable.success')
    ]);
  }

  public function addIP(Request $request)
  {
    // Check form
    if (!$request->has('ip'))
      return response()->json([
        'status' => false,
        'error' => __('form.error.fields')
      ]);
    if (Validator::make(['ip' => $request->input('ip')], ['ip' => 'required|ipv4'])->fails())
      return response()->json([
        'status' => false,
        'error' => __('user.obsiguard.add.error')
      ]);

    // add ip
    $ip = new \App\UsersObsiguardIP();
    $ip->user_id = Auth::user()->id;
    $ip->ip = $request->input('ip');
    $ip->save();

    // add log
    $log = new \App\UsersObsiguardLog();
    $log->type = 'ADD';
    $log->user_id = Auth::user()->id;
    $log->ip = $request->ip();
    $log->data = $ip->ip;
    $log->save();

    // success
    return response()->json([
      'status' => true,
      'success' => __('user.obsiguard.add.success'),
      'data' => ['id' => $ip->id, 'ip' => $ip->ip]
    ]);
  }

  public function removeIP(Request $request)
  {
    // find
    $ip = \App\UsersObsiguardIP::where('user_id', Auth::user()->id)->where('id', $request->id)->first();
    // remove
    $remove = \App\UsersObsiguardIP::where('user_id', Auth::user()->id)->where('id', $request->id)->delete();

    if (count($ip) === 1) {
      // add log
      $log = new \App\UsersObsiguardLog();
      $log->type = 'REMOVE';
      $log->user_id = Auth::user()->id;
      $log->ip = $request->ip();
      $log->data = $ip->ip;
      $log->save();
    }

    // success
    return response()->json([
      'status' => true,
      'success' => ''
    ]);
  }

  public function validSecurityCode(Request $request)
  {
    // Check form
    if (!$request->has('code'))
      return response()->json([
        'status' => false,
        'error' => __('form.error.fields')
      ]);
    // find code
    $token = \App\UsersToken::where('type', 'OBSIGUARD')->where('user_id', Auth::user()->id)->where('data', $request->input('code'))->first();
    if (count($token) <= 0)
      return response()->json([
        'status' => false,
        'error' => __('user.obsiguard.security.error')
      ]);

    // edit token
    $token->used_ip = $request->ip();
    $token->save();

    // save session and response
    $request->session()->put('user.obsiguard.security.code', $token->token);
    return response()->json([
      'status' => true,
      'success' => ''
    ]);
  }

}
