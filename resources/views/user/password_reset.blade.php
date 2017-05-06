@extends('layouts.app')

@section('title', __('user.password.reset'))

@section('content')
  <div class="ui container page-content">
    <h1 class="ui header">
      <i class="sign in icon"></i>
      <div class="content">
        @lang('user.password.reset')
      </div>
    </h1>
    <div class="ui divider"></div>
    <form method="post" action="{{ Request::url() }}" data-ajax class="ui form">
      <div class="field">
        <label>@lang('user.field.password')</label>
        <div class="ui left icon input">
          <input type="password" name="password" placeholder="*********">>
          <i class="user icon"></i>
        </div>
      </div>
      <div class="field">
        <label>@lang('user.field.password')</label>
        <div class="ui left icon input">
          <input type="password" name="password_confirmation" placeholder="*********">
          <i class="lock icon"></i>
        </div>
      </div>
      <button type="submit" class="ui blue submit button">@lang('user.password.reset.action')</button>
    </form>
  </div>
@endsection
