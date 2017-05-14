@extends('layouts.app')

@section('title', __('join.title'))

@section('content')
  <div class="ui container page-content">
    <div class="text-center">
      <img src="{{ url('/img/logo-banner.png') }}" height="170" alt="Logo">
    </div>

  </div>
  <div class="colored-block">
    <div class="ui container text-center">
      <h2 class="ui header">
        <img src="{{ url('/img/logo-min.png') }}" class="ui circular image">
        <div class="content">
          @lang('join.step.one.title')
          <div class="sub header">@lang('join.step.one.subtitle')</div>
        </div>
      </h2>
      <p>
        @lang('join.step.one.content')
      </p>
    </div>
  </div>
  <div class="white-block">
    <div class="ui container text-center">
      <h2 class="ui header">
        <img src="{{ url('/img/logo-min.png') }}" class="ui circular image">
        <div class="content">
          @lang('join.step.two.title')
          <div class="sub header">@lang('join.step.two.subtitle')</div>
        </div>
      </h2>
      <p>
        @lang('join.step.two.content')
      </p>
      <a href="" class="circular ui icon yellow massive button">
        <i class="linux icon"></i>
      </a>
      <a href="" class="circular ui icon grey massive button">
        <i class="apple icon"></i>
      </a>
      <a href="" class="circular ui icon primary massive button">
        <i class="windows icon"></i>
      </a>

    </div>
  </div>
  <div class="colored-block">
    <div class="ui container text-center">
      <h2 class="ui header">
        <img src="{{ url('/img/logo-min.png') }}" class="ui circular image">
        <div class="content">
          @lang('joinstep.three.title')
          <div class="sub header">@lang('joinstep.three.subtitle')</div>
        </div>
      </h2>
      <p>
        @lang('joinstep.three.content')
      </p>
    </div>
  </div>
  <div class="white-block">
    <div class="ui container text-center">
      <h2 class="ui header">
        <img src="{{ url('/img/logo-min.png') }}" class="ui circular image">
        <div class="content">
          @lang('join.step.four.title')
          <div class="sub header">@lang('join.step.four.subtitle')</div>
        </div>
      </h2>
      @lang('join.step.four.content', ['link' => 'http://www.teamspeak.com/?page=downloads'])
      <img src="{{ url('/img/teamspeak.png') }}" alt="">
    </div>
  </div>
  <div class="black-block">
    <div class="ui container text-center">
      <h2 class="ui header">
        <img src="{{ url('/img/logo-min.png') }}" class="ui circular image">
        <div class="content">
          @lang('join.step.five.title')
          <div class="sub header">@lang('join.step.five.subtitle')</div>
        </div>
      </h2>
      <div class="ui divider"></div>
      <ul class="ui list text-left">
        @lang('join.step.five.content', ['cgu_link' => 'http://forum.obsifight.fr/index.php?threads/c-g-u-dobsifight.15819/'])
      </ul>
    </div>
  </div>
@endsection
@section('style')
  <style media="screen">
    .ui.button>.icon:not(.button) {
      height: auto;
    }
    .ui.circular.button>.icon {
      width: auto;
    }
    .ui.circular.button i.icon {
      font-size: 3em;
    }
    .ui.circular.button {
      height: 120px!important;
      padding: 45px 10px!important;
      width: 120px;
    }
  </style>
@endsection
