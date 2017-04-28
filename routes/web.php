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
Route::get('/user/email/send', 'UserController@sendConfirmationMail')->middleware('auth');
Route::get('/user', 'UserController@profile')->middleware('auth');
