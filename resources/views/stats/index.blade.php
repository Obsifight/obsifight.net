@extends('layouts.app')

@section('title', __('stats.title'))

@section('content')
  <div class="ui container page-content">

    <div class="ui stackable grid">
      <div class="ui four wide column">
        <div class="ui search">
          <div class="ui left icon input" style="width: 100%;">
            <input class="prompt" type="text" placeholder="@lang('stats.find.user')">
            <i class="user icon"></i>
          </div>
        </div>

        <h3 class="ui center aligned icon header">
          <i class="circular users icon"></i>
        </h3>

        @foreach($staff as $group => $data)
          <h2 class="ui dividing {{ $data['color'] }} staff header">
            {{ $group }}
          </h2>
          @foreach ($data['users'] as $username)
            <a href="{{ url('/stats/' . $username) }}">
              <img src="https://skins.obsifight.net/head/{{ $username }}/64" class="ui rounded staff image" alt="{{ $username }}" data-toggle="popup" data-variation="inverted" data-placement="top center" data-content="{{ $username }}">
            </a>
          @endforeach
        @endforeach
      </div>

      <div class="ui twelve wide column">

        <div class="ui three large statistics">
          <div class="statistic">
            <div class="value">
              530
            </div>
            <div class="label">
              @lang('stats.users.amount.max')
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              350
            </div>
            <div class="label">
              @lang('stats.users.amount.online')
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              77.012
            </div>
            <div class="label">
              @lang('stats.users.amount.register')
            </div>
          </div>
        </div>
        <div class="ui divider"></div>
        <div class="ui four small statistics">
          <div class="statistic">
            <div class="value">
              3.700
            </div>
            <div class="label">
              @lang('stats.users.amount.register.version')
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              2.200
            </div>
            <div class="label">
              @lang('stats.factions.amount')
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              160.000
            </div>
            <div class="label">
              @lang('stats.fights.amount')
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              980.000
            </div>
            <div class="label">
              @lang('stats.visits.amount')
            </div>
          </div>
        </div>

        <div class="ui divider"></div>

        <h1 class="ui header">
          <i class="child icon"></i>
          <div class="content">
            @lang('stats.graph.users')
            <div class="sub header">@lang('stats.graph.range')</div>
          </div>
        </h1><br>

        <div class="ui icon info message">
          <i class="notched circle loading icon"></i>
          <div class="content">
            <div class="header">
              @lang('stats.graph.load.title')
            </div>
            <p>@lang('stats.graph.load.subtitle')</p>
          </div>
        </div>

        <h3 class="ui dividing header">
          @lang('stats.graph.users.hours')
        </h3>

        <div class="ui icon info message">
          <i class="notched circle loading icon"></i>
          <div class="content">
            <div class="header">
              @lang('stats.graph.load.title')
            </div>
            <p>@lang('stats.graph.load.subtitle')</p>
          </div>
        </div>

        <div class="ui divider"></div>

        <h1 class="ui header">
          <i class="hand pointer icon"></i>
          <div class="content">
            @lang('stats.graph.visits')
            <div class="sub header">@lang('stats.graph.range')</div>
          </div>
        </h1><br>

        <div class="ui icon info message">
          <i class="notched circle loading icon"></i>
          <div class="content">
            <div class="header">
              @lang('stats.graph.load.title')
            </div>
            <p>@lang('stats.graph.load.subtitle')</p>
          </div>
        </div>

        <div class="ui divider"></div>

        <h1 class="ui header">
          <i class="signup icon"></i>
          <div class="content">
            @lang('stats.graph.register')
            <div class="sub header">@lang('stats.graph.range')</div>
          </div>
        </h1><br>

        <div class="ui icon info message">
          <i class="notched circle loading icon"></i>
          <div class="content">
            <div class="header">
              @lang('stats.graph.load.title')
            </div>
            <p>@lang('stats.graph.load.subtitle')</p>
          </div>
        </div>

      </div>
    </div>

  </div>
@endsection
@section('style')
  <style media="screen">
    img.staff.image {
      background-color: #bdc3c7;
      border: 2px solid #c0392b;
      margin-right: 5px;
      margin-bottom: 5px;
      display: inline-block;
    }
    h2.ui.dividing.red.staff.header,
    h2.ui.dividing.green.staff.header,
    h2.ui.dividing.olive.staff.header,
    h2.ui.dividing.yellow.staff.header {
      color: #4a4a4a!important;
    }

    .page-content {
      padding-top: 30px;
    }

    .ui.search .results .result .content {
      margin-top: 2px!important;
    }
    .ui.search .results .result .image {
      float: left;
      width: 20px;
      height: 20px;
      margin-right: 10px;
    }
  </style>
@endsection
@section('script')
  <script type="text/javascript">
    $(document).ready(function () {
      $('[data-toggle="popup"]').each(function (k, el) {
        $(el).popup({
          html: $(el).attr('data-content'),
          position: $(el).attr('data-placement'),
          variation: $(el).attr('data-variation')
        })
      })
    })
    $('.ui.search').search({
      apiSettings: {
        url: '{{ url('/stats/users/search') }}?q={query}'
      },
      fields: {
        results: 'users',
        title: 'username',
        url: 'url',
        image: 'img'
      },
      minCharacters : 3
    })
  </script>
@endsection
