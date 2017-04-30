@extends('layouts.app')
@section('title', Auth::user()->username)

@section('content')
  <div class="ui container page-content">

    <h1 class="ui center aligned header">
      <img class="ui rounded image" src="{{ env('SKINS_DISPLAY_URL') }}/head/{{ Auth::user()->username }}">
      <div class="content">
        {{ Auth::user()->username }}
        <div class="sub header">@lang('user.profile.created.string', ['date' => Auth::user()->created_at->diffForHumans()])</div>
      </div>
    </h1>

    <div class="ui divider"></div>

    @if(!$confirmedAccount)
      <div class="ui warning message">
        <i class="close icon"></i>
        <div class="header">
          @lang('user.profile.confirmed.title')
        </div>
        @lang('user.profile.confirmed.description', ['url' => action('UserController@sendConfirmationMail')])
      </div>
    @endif

    <div class="ui grid two column">
      <div class="ui four wide column">
        <div class="ui vertical menu">
          <a class="item active">
            <i class="user left aligned icon"></i>
            @lang('user.profile.menu.infos')
          </a>
          <a class="item">
            <i class="theme left aligned icon"></i>
            @lang('user.profile.menu.appearence')
          </a>
          <a class="item">
            <i class="lock left aligned icon"></i>
            @lang('user.profile.menu.security')
            @if (!$twoFactorEnabled || !$findObsiGuardIPs)
              <i class="warning sign icon" style="color:#FE9A76"></i>
            @endif
          </a>
          <a class="item">
            <i class="shopping basket left aligned icon"></i>
            @lang('user.profile.menu.spendings')
          </a>
          <a class="item">
            <i class="twitter left aligned icon"></i>
            @lang('user.profile.menu.socials')
          </a>
        </div>
      </div>
      <div class="ui twelve wide column">
        <div class="menu-content">
          <div data-menu="infos">
            <form class="ui form">
              <h4 class="ui dividing header">@lang('user.profile.personnals.details')</h4>
              <div class="field">
                <label>@lang('user.field.email')</label>
                <div class="two fields">
                  <div class="field">
                    <input type="text" name="shipping[first-name]" placeholder="First Name">
                  </div>
                  <div class="field">
                    <input type="text" name="shipping[last-name]" placeholder="Last Name">
                  </div>
                </div>
              </div>
              <div class="field">
                <label>Billing Address</label>
                <div class="fields">
                  <div class="twelve wide field">
                    <input type="text" name="shipping[address]" placeholder="Street Address">
                  </div>
                  <div class="four wide field">
                    <input type="text" name="shipping[address-2]" placeholder="Apt #">
                  </div>
                </div>
              </div>
            </form>

            <div class="ui divider"></div>

            <div class="ui three statistics">
              <div class="statistic">
                <div class="value">
                  22
                </div>
                <div class="label">
                  @lang('user.money')
                </div>
              </div>
              <div class="statistic">
                <div class="value">
                  5
                </div>
                <div class="label">
                  @lang('user.votes')
                </div>
              </div>
              <div class="statistic">
                <div class="value">
                  42
                </div>
                <div class="label">
                  @lang('user.rewards_waited')
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
@section('style')
  <style media="screen">
    .ui.vertical.menu .left.aligned.icon {
      float: left;
      margin: 0 .5em 0 0;
    }
  </style>
@endsection
