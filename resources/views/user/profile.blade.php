@extends('layouts.app')
@section('title', Auth::user()->username)

@section('content')
  <div class="ui container page-content">

    @if(!$confirmedAccount)
      <div class="ui warning message">
        <i class="close icon"></i>
        <div class="header">
          @lang('user.profile.confirmed.title')
        </div>
        @lang('user.profile.confirmed.description', ['url' => action('UserController@sendConfirmationMail')])
      </div>
    @endif


  </div>
@endsection
