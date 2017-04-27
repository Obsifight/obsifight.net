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
});
Route::post('/login', 'UserController@login');
Route::post('/login/two-factor-auth', 'UserController@validLogin');
Route::get('/logout', 'UserController@logout');
Route::get('/signup', function () {
    return view('user.signup');
});
Route::post('/signup', 'UserController@signup');
