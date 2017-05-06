@extends('layouts.app')

@section('title', __('user.two_factor_auth.enable.title'))

@section('content')
  <div class="ui container page-content">
    <h2 class="ui center aligned icon header">
      <i class="circular protect icon"></i>
      @lang('user.two_factor_auth.enable.title')
    </h2>

    <div class="text-center">
      <img src="{{ $qrCodeUrl }}" alt="">
      <p>
        <small style="color:#777;">@lang('user.two_factor_auth.field.secret', ['secret' => $secret])</small>
      </p>

      <form class="ui form" method="post"  action="{{ url('/user/two-factor-auth/enable') }}" data-ajax>

        <div class="field">
          <label>@lang('user.two_factor_auth.field.code')</label>
          <input type="text" name="code" style="width:200px;">
        </div>

        <button type="submit" class="ui green button">@lang('user.two_factor_auth.enable')</button>
      </form>
    </div>
  </div>
@endsection
