@extends('layouts.app')

@section('title', __('vote.title'))

@section('content')
  <div class="ui container page-content">

    <div class="ui ordered fluid stackable top attached steps">
      <div class="active step">
        <div class="content">
          <div class="title">@lang('vote.step.one.title')</div>
          <div class="description">@lang('vote.step.one.content')</div>
        </div>
      </div>
      <div class="disabled step">
        <div class="content">
          <div class="title">@lang('vote.step.two.title')</div>
          <div class="description">@lang('vote.step.two.content')</div>
        </div>
      </div>
      <div class="disabled step">
        <div class="content">
          <div class="title">@lang('vote.step.three.title')</div>
          <div class="description">@lang('vote.step.three.content')</div>
        </div>
      </div>
      <div class="disabled step">
        <div class="content">
          <div class="title">@lang('vote.step.four.title')</div>
          <div class="description">@lang('vote.step.four.content')</div>
        </div>
      </div>
    </div>
    <div class="ui attached segment">
      <div data-step="1" class="active">
        <form class="ui form" method="post"  action="{{ url('/vote/step/one') }}" data-ajax data-ajax-custom-callback="afterStepOne">

          <div class="field">
            <label>@lang('vote.step.one.content.input.label')</label>
            <input type="text" name="username" style="width:200px;text-align:center;">
          </div>

          <button type="submit" class="ui green animated button">
            <div class="visible content">@lang('vote.step.one.content.input.btn')</div>
            <div class="hidden content"><i class="right arrow icon"></i></div>
          </button>
        </form>
      </div>
      <div data-step="2">
        <a target="_blank" href="{{ env('VOTE_URL') }}" class="ui animated fade yellow massive button">
          <div class="visible content">@lang('vote.step.two.content.link')</div>
          <div class="hidden content">
            <i class="right arrow icon"></i>
          </div>
        </a>
      </div>
      <div data-step="3">
        <div class="ui info message">
          <div class="header">
            @lang('global.info')
          </div>
          <p>@lang('vote.step.three.content.help', ['help_link' => env('VOTE_HELP_LINK')])</p>
        </div>
        <form class="ui form" method="post"  action="{{ url('/vote/step/three') }}" data-ajax data-ajax-custom-callback="afterStepThree">

          <div class="field">
            <label>@lang('vote.step.three.content.input.label')</label>
            <input type="text" name="out" placeholder="@lang('vote.step.three.content.input.placeholder')" style="width:200px;text-align:center;">
          </div>

          <button type="submit" class="ui green animated button">
            <div class="visible content">@lang('vote.step.three.content.input.btn')</div>
            <div class="hidden content"><i class="right arrow icon"></i></div>
          </button>
        </form>
      </div>
      <div data-step="4">
        4
      </div>
    </div>

  </div>
@endsection
@section('script')
  <script type="text/javascript">
    function afterStepOne(req, res) {
      $('[data-step="1"]').slideUp(100, function () {
        $('[data-step="2"]').slideDown(100)
      })
    }
    function afterStepThree(req, res) {
      $('[data-step="3"]').slideUp(100, function () {
        $('[data-step="4"]').slideDown(100)
      })
    }
    $(document).ready(function () {
      $('[data-step="2"] a').on('click', function () {
        $('[data-step="2"]').slideUp(100, function () {
          $('[data-step="3"]').slideDown(100)
        })
      })
    })
  </script>
@endsection
@section('style')
  <style media="screen">
    div[data-step] {
      text-align: center;
    }
    div[data-step]:not(.active) {
      display: none;
    }
  </style>
@endsection
