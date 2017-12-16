@extends('layouts.app')

@section('title', __('global.navbar.home'))

@section('content')
  <div class="ui container page-content">
    <img class="ui left floated image" src="{{ url('/img/logo-min.png') }}" width="130">
    <h2>@lang('home.description.title')</h2>

    @lang('home.description')

    <div class="ui divider"></div>

    <div class="ui four statistics">
      <div class="statistic">
        <div class="value">
          <span id="users_count">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
        </div>
        <div class="label">
          @lang('stats.count.registered')
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          <span id="server_count">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
        </div>
        <div class="label">
          @lang('stats.count.online')
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          <span id="visits_count">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
        </div>
        <div class="label">
          @lang('stats.count.visits')
        </div>
      </div>
      <div class="statistic">
        <div class="value">
          <span id="server_max">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
        </div>
        <div class="label">
          @lang('stats.count.online.max')
        </div>
      </div>
    </div>
  </div>
  <div class="parallax-block" style="background-image: url('https://obsifight.net/forum/styles/material/uix/bg/1.png')">
    <div class="ui container text-center ">
      <div class="ui huge header text-center">
        @lang('home.trailer.title', ['version' => env('APP_VERSION_COUNT')])
        <div class="sub-header mobile-hide" style="color:#fff;">
          @lang('home.trailer.subtitle')
        </div>
      </div>

      <div class="video-wrapper">
        <div class="video-container">
          <iframe width="560" height="315" src="https://www.youtube.com/embed/z9IQypcGFkM?autoplay=0&amp;loop=1&amp;autohide=1&amp;controls=0&amp;theme=light" frameborder="0" allowfullscreen=""></iframe>
        </div>
      </div>
    </div>
  </div>
@endsection
@section('script')
  <script type="text/javascript">
    $.get('{{ url('/stats/users/count') }}', function (data) {
      if (data.status)
        $('#users_count').html(nFormatter(data.count, 1))
    })
    $.get('{{ url('/stats/server/count') }}', function (data) {
      if (data.status)
        $('#server_count').html(nFormatter(data.count, 1))
    })
    $.get('{{ url('/stats/server/max') }}', function (data) {
      if (data.status)
        $('#server_max').html(nFormatter(data.count, 1))
    })
    $.get('{{ url('/stats/visits/count') }}', function (data) {
      if (data.status)
        $('#visits_count').html(nFormatter(data.count, 1))
    })
  </script>
@endsection
