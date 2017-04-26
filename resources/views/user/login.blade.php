@extends('layouts.app')

@section('title', __('user.login'))

@section('content')
  <div class="ui container page-content">

    <div class="ui two column middle aligned very relaxed stackable grid">
      <div class="column">
        <h1 class="ui header">
          <i class="sign in icon"></i>
          <div class="content">
            @lang('user.login')
          </div>
        </h1>
        <div class="ui divider"></div>
        <form method="post" action="{{ url('/login') }}" data-ajax class="ui form">
          <div class="field">
            <label>@lang('user.field.username')</label>
            <div class="ui left icon input">
              <input type="text" placeholder="Eywek">
              <i class="user icon"></i>
            </div>
          </div>
          <div class="field">
            <label>@lang('user.field.password') <small><em><a href="{{ url('/user/password/forgot') }}">@lang('user.password.forgot')</a></em></small></label>
            <div class="ui left icon input">
              <input type="password" placeholder="*********">
              <i class="lock icon"></i>
            </div>
          </div>
          <button type="submit" class="ui blue submit button">@lang('user.login')</button>
        </form>
      </div>
      <div class="ui vertical divider">
        @lang('global.or')
      </div>
      <div class="center aligned column">
        <a href="{{ url('/signup') }}" class="ui big green labeled icon button">
          <i class="signup icon"></i>
          @lang('user.signup')
        </a>
      </div>
    </div>

  </div>
@endsection
@section('style')
  <style media="screen">
    .ui.grid>.column+.divider, .ui.grid>.row>.column+.divider {
      left: 50%;
    }
    .grid {
      position: relative;
    }
    .page-content {
      padding-top: 30px;
      padding-bottom: 30px;
    }
  </style>
@endsection
