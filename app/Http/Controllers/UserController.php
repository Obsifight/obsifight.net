<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use Illuminate\Support\Facades\Event;

use Illuminate\Support\Facades\Auth;
use App\User;

class UserController extends Controller
{
  public function login(Request $request)
  {
    // Check form
    if (!$request->has('username') || !$request->has('password'))
      return response()->json([
        'status' => false,
        'error' => __('form.error.fields')
      ]);

    // Check if not temporaly disabled
    $lastRetry = \App\UsersLoginRetry::where('ip', $request->ip())->where('updated_at', '>', date('Y-m-d H:i:s', strtotime('-30 minutes')))->first();
    if (!empty($lastRetry) && $lastRetry->count >= 10) // Temporaly blocked
      return response()->json([
        'status' => false,
        'error' => __('user.login.error.blocked')
      ]);

    // Check credentials
    $user = User::where('username', $request->input('username'))->first();
    if (empty($user)) // User not found
      return response()->json([
        'status' => false,
        'error' => __('user.login.error.notfound')
      ]);
    if ($user->password !== User::hash($request->input('password'), $request->input('username'))) { // invalid password
      // save try
      $count = (empty($lastRetry)) ? 1 : ($lastRetry->count + 1);
      \App\UsersLoginRetry::updateOrCreate(
        ['ip' => $request->ip()],
        ['count' => $count]
      );
      // send response
      return response()->json([
        'status' => false,
        'error' => __('user.login.error.credentials')
      ]);
    }

    // Check if two factor auth
    $twoFactorAuthSecret = \App\UsersTwoFactorAuthSecret::where('user_id', $user->id)->first();
    if (!empty($twoFactorAuthSecret) && $twoFactorAuthSecret->enabled) { // TwoFactor is enabled, store user_id & send to user
      session(['twoFactorAuth' => ['user_id' => $user->id, 'remember_me' => $request->input('remember_me')]]);
      return response()->json([
        'status' => true,
        'twoFactorAuth' => true,
        'success' => ''
      ]);
    }

    // Success
    return $this->__loginSuccess($user->id, $request->input('remember_me'), $request);
  }

  public function validLogin(Request $request)
  {
    // Check form
    if (!$request->has('code'))
      return response()->json([
        'status' => false,
        'error' => __('form.error.fields')
      ]);
    // Check session
    if (!$request->session()->has('twoFactorAuth'))
      return abort(403);
    $userId = $request->session()->get('twoFactorAuth')['user_id'];
    $rememberMe = $request->session()->get('twoFactorAuth')['remember_me'];

    // Get secret
    $twoFactorAuthSecret = \App\UsersTwoFactorAuthSecret::where('user_id', $userId)->first();
    if (empty($twoFactorAuthSecret) || !$twoFactorAuthSecret->enabled)
      return abort(403);

    // Valid secret
    require __DIR__ . '/../../../vendor/PHPGangsta/GoogleAuthenticator.php';
    $ga = new \PHPGangsta_GoogleAuthenticator();

    // check code
    $checkResult = $ga->verifyCode($twoFactorAuthSecret->secret, $request->input('code'), 2);    // 2 = 2*30sec clock tolerance
    if (!$checkResult)
      return response()->json([
        'status' => false,
        'error' => __('user.login.error.two_factor_auth')
      ]);

    // Remove session
    $request->session()->forget('twoFactorAuth');

    return $this->__loginSuccess($userId, $rememberMe, $request);
  }

  private function __loginSuccess($userId, $rememberMe, $request)
  {
    // Log user
    Auth::loginUsingId($userId, ($rememberMe ? true : false));

    return response()->json([
      'status' => true,
      'success' => __('user.login.success')
    ]);
  }

  public function logout(Request $request)
  {
    Auth::logout();
    return redirect('/');
  }

  public function signup(Request $request)
  {
    // Check form
    if (!$request->has('username') || !$request->has('password'))
      return response()->json([
        'status' => false,
        'error' => __('form.error.fields')
      ]);

    // Check if username or email is already used

    // register user

    // log user

    // success response
  }
}
