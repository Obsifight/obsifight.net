<?php
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
  return view('pages.home');
});

/*
===========
  USERS
===========
*/
Route::get('/login', function () {
  return view('user.login');
})->name('login');
Route::post('/login', 'UserController@login');
Route::get('/logged', function () {
  return response()->json([
    'logged' => Auth::check()
  ]);
});
Route::post('/login/two-factor-auth', 'UserController@validLogin');
Route::get('/logout', 'UserController@logout');
Route::get('/signup', function () {
  return view('user.signup');
});
Route::post('/signup', 'UserController@signup');
Route::get('/user/email/confirm/{token}', 'UserController@confirmEmail')->where('token', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::get('/user/email/send', 'UserController@sendConfirmationMail')->middleware('auth')->middleware('permission:user-send-confirmation-email');

Route::get('/user', 'UserController@profile')->middleware('auth');

Route::post('/user/password/forgot', 'UserController@forgotPassword');
Route::get('/user/password/reset/{token}', function (\Illuminate\Http\Request $request) {
  // Find token
  $token = \App\UsersToken::where('token', $request->token)->where('type', 'PASSWORD')->where('used_ip', null)->where('created_at', '>', date('Y-m-d H:i:s', strtotime('-24 hours')))->firstOrFail();
  return view('user.password_reset');
})->where('token', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::post('/user/password/reset/{token}', 'UserController@resetPassword')->where('token', '[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}');
Route::post('/user/password', 'UserController@editPassword')->middleware('auth')->middleware('permission:user-edit-password');

Route::post('/user/email', 'UserController@requestEditEmail')->middleware('auth')->middleware('permission:user-request-edit-email');
Route::post('/user/username', 'UserController@editUsername')->middleware('auth')->middleware('permission:user-edit-username');
Route::put('/user/money', 'UserController@transferMoney')->middleware('auth')->middleware('permission:user-transfer-money');
Route::post('/user/skin', 'UserController@uploadSkin')->middleware('auth')->middleware('permission:user-upload-skin');
Route::post('/user/cape', 'UserController@uploadCape')->middleware('auth')->middleware('permission:user-upload-cape');
Route::get('/user/two-factor-auth/enable', 'UserController@enableTwoFactorAuth')->middleware('auth')->middleware('permission:user-enable-two-factor-auth');
Route::post('/user/two-factor-auth/enable', 'UserController@validEnableTwoFactorAuth')->middleware('auth')->middleware('permission:user-enable-two-factor-auth');
Route::get('/user/two-factor-auth/disable', 'UserController@disableTwoFactorAuth')->middleware('auth')->middleware('permission:user-disable-two-factor-auth');

Route::get('/user/obsiguard/enable', 'ObsiguardController@enable')->middleware('auth')->middleware('permission:user-enable-obsiguard');
Route::post('/user/obsiguard/security/valid', 'ObsiguardController@validSecurityCode')->middleware('auth');
Route::get('/user/obsiguard/disable', 'ObsiguardController@disable')->middleware('auth')->middleware('permission:user-disable-obsiguard')->middleware('obsiguard');
Route::post('/user/obsiguard/ip', 'ObsiguardController@addIP')->middleware('auth')->middleware('permission:user-add-ip-obsiguard')->middleware('obsiguard');
Route::delete('/user/obsiguard/ip/{id}', 'ObsiguardController@removeIP')->middleware('auth')->middleware('permission:user-remove-ip-obsiguard')->middleware('obsiguard')->where('id', '([0-9])+');
Route::get('/user/obsiguard/ip/dynamic/enable', 'ObsiguardController@enableDynamicIP')->middleware('auth')->middleware('permission:user-enable-dynamic-ip-obsiguard');
Route::get('/user/obsiguard/ip/dynamic/disable', 'ObsiguardController@disableDynamicIP')->middleware('auth')->middleware('permission:user-disable-dynamic-ip-obsiguard');

Route::get('/user/socials/google/link', 'GoogleController@auth')->middleware('auth')->middleware('permission:user-link-google-account');
