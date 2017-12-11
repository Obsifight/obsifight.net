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
              <span id="server_max">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
            </div>
            <div class="label">
              @lang('stats.users.amount.max')
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              <span id="server_count">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
            </div>
            <div class="label">
              @lang('stats.users.amount.online')
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              <span id="users_count">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
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
              <span id="users_count_this_version">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
            </div>
            <div class="label">
              @lang('stats.users.amount.register.version')
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              <span id="factions_count">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
            </div>
            <div class="label">
              @lang('stats.factions.amount')
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              <span id="fights_count">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
            </div>
            <div class="label">
              @lang('stats.fights.amount')
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              <span id="visits_count">&nbsp;&nbsp;<div class="ui active inline medium loader"></div>&nbsp;&nbsp;</span>
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

        <div id="graphPlayers">
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

        <h3 class="ui dividing header">
          @lang('stats.graph.users.hours')
        </h3>

        <div class="ui two column grid">
            <div class="column">
                <div id="graphPeakPlayersDays">
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
            <div class="column">
                <div id="graphPeakPlayersHours">
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

        <div class="ui divider"></div>

        <h1 class="ui header">
          <i class="hand pointer icon"></i>
          <div class="content">
            @lang('stats.graph.visits')
            <div class="sub header">@lang('stats.graph.range')</div>
          </div>
        </h1><br>

          <div id="graphVisits">
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

        <div class="ui divider"></div>

        <h1 class="ui header">
          <i class="signup icon"></i>
          <div class="content">
            @lang('stats.graph.register')
            <div class="sub header">@lang('stats.graph.range')</div>
          </div>
        </h1><br>

          <div id="graphRegister">
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
  <script type="text/javascript">
      $.get('{{ url('/stats/users/count/version') }}', function (data) {
          if (data.status)
              $('#users_count_this_version').html(nFormatter(data.count, 1))
      })
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
      $.get('{{ url('/stats/factions/count') }}', function (data) {
          if (data.status)
              $('#factions_count').html(nFormatter(data.count, 1))
      })
      $.get('{{ url('/stats/fights/count') }}', function (data) {
          if (data.status)
              $('#fights_count').html(nFormatter(data.count, 1))
      })
  </script>
  <script src="{{ url('/js/highcharts.src.js') }}"></script>
  <script src="{{ url('/js/moment.js') }}"></script>
  <script>
      Highcharts.setOptions(Highcharts.theme);
      $.get('{{ url('/stats/users/graph') }}', function (data) {
          data = data.graph;
          data.reverse();
          new Highcharts.Chart({
              chart: {
                  zoomType: 'x',
                  type: 'area',
                  renderTo: 'graphPlayers',
                  backgroundColor: "#fff"
              },
              colors: ["#f39c12"],
              title: {
                  text: false
              },
              subtitle: {
                  text: 'Cliquez et glissez pour zoomer.'
              },
              xAxis: {
                  type: 'datetime'
              },
              yAxis: {
                  title: {
                      text: 'Joueurs'
                  },
                  floor: 0
              },
              legend: {
                  enabled: false
              },
              tooltip: {
                  shared: true,
                  valueSuffix: ' joueurs',
                  formatter: function() {
                      var s = '<span style="font-size: 10px">' +  new Date(this.x).toLocaleString() + '</span>';
                      var sortedPoints = this.points.sort(function(a, b){
                          return ((a.y > b.y) ? -1 : ((a.y < b.y) ? 1 : 0));
                      });

                      $.each(sortedPoints , function(i, point) {
                          s += '<br/><b>'+ point.y + " joueurs</b>";
                      });

                      return s;
                  }
              },
              plotOptions: {
                  spline: {
                      lineWidth: 3,
                      states: {
                          hover: {
                              lineWidth: 4
                          }
                      },
                      marker: {
                          enabled : false
                      }
                  }
              },
              series: [{
                  name: 'ObsiFight',
                  data: data
              }]
          });
      });
  </script>
  <script>
      $.get('{{ url('/stats/users/graph/peak') }}', function (data) {
          data = data.graph;
          var days = [];
          for (day in data.days)
          {
              days.push({
                name: day,
                y: parseInt(data.days[day])
              })
          }
          var hours = []
          for (hour in data.hours)
          {
              hours.push({
                  name: hour + 'h',
                  y: parseInt(data.hours[hour])
              })
          }

          $('#graphPeakPlayersDays').highcharts({
              chart: {
                  plotBackgroundColor: null,
                  plotBorderWidth: null,
                  plotShadow: false,
                  type: 'pie'
              },
              title: {
                  text: 'Jours les plus fréquentés'
              },
              tooltip: {
                  pointFormat: '{series.name}: <b>~{point.y} joueurs</b>'
              },
              plotOptions: {
                  pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                          enabled: false,
                          style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                          }
                      },
                      showInLegend: true,
                      colors: (function () {
                          var colors = [],
                              base = "#2981ba",
                              i;

                          for (i = 0; i < 10; i += 1) {
                              // Start out with a darkened base color (negative brighten), and end
                              // up with a much brighter color
                              colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
                          }
                          return colors;
                      }())
                  }
              },
              series: [{
                  name: 'Connectés',
                  colorByPoint: true,
                  data: days
              }]
          });

          $('#graphPeakPlayersHours').highcharts({
              chart: {
                  plotBackgroundColor: null,
                  plotBorderWidth: null,
                  plotShadow: false,
                  type: 'pie'
              },
              title: {
                  text: 'Heures les plus fréquentées'
              },
              tooltip: {
                  pointFormat: '{series.name}: <b>~{point.y} joueurs</b>'
              },
              plotOptions: {
                  pie: {
                      allowPointSelect: true,
                      cursor: 'pointer',
                      dataLabels: {
                          enabled: false,
                          style: {
                              color: (Highcharts.theme && Highcharts.theme.contrastTextColor) || 'black'
                          }
                      },
                      showInLegend: true,
                      colors: (function () {
                          var colors = [],
                              base = "#27ae60",
                              i;

                          for (i = 0; i < 10; i += 1) {
                              // Start out with a darkened base color (negative brighten), and end
                              // up with a much brighter color
                              colors.push(Highcharts.Color(base).brighten((i - 3) / 7).get());
                          }
                          return colors;
                      }())
                  }
              },
              series: [{
                  name: 'Connectés',
                  colorByPoint: true,
                  data: hours
              }]
          });

      });
  </script>
  <script>
      $.get('{{ url('/stats/users/graph/register') }}', function (data) {
          data = data.graph;

          Highcharts.setOptions(Highcharts.theme);
          moment.locale('fr');
          new Highcharts.Chart({
              chart: {
                  zoomType: 'x',
                  type: 'column',
                  renderTo: 'graphRegister',
                  backgroundColor: "#fff"
              },
              colors: ["#8E44AD"],
              title: {
                  text: false
              },
              subtitle: {
                  text: false
              },
              xAxis: {
                  categories: [
                      moment().subtract(7, 'day').format('dddd'),
                      moment().subtract(6, 'day').format('dddd'),
                      moment().subtract(5, 'day').format('dddd'),
                      moment().subtract(4, 'day').format('dddd'),
                      moment().subtract(3, 'day').format('dddd'),
                      moment().subtract(2, 'day').format('dddd'),
                      moment().subtract(1, 'day').format('dddd'),
                      moment().format('dddd')
                  ],
                  crosshair: true,
                  labels: {
                      enabled : false
                  }
              },
              yAxis: {
                  min: 0,
                  title: {
                      text: 'Inscriptions'
                  }
              },
              legend: {
                  enabled: false
              },
              tooltip: {
                  shared: true,
                  valueSuffix: ' inscriptions',
                  formatter: function() {
                      var s = '<span style="font-size: 10px">'+ this.x +'</span>';
                      var sortedPoints = this.points.sort(function(a, b){
                          return ((a.y > b.y) ? -1 : ((a.y < b.y) ? 1 : 0));
                      });

                      $.each(sortedPoints , function(i, point) {
                          s += '<br/><b>'+ point.y + " inscriptions</b>";
                      });

                      return s;
                  }
              },
              plotOptions: {
                  spline: {
                      lineWidth: 3,
                      states: {
                          hover: {
                              lineWidth: 4
                          }
                      },
                      marker: {
                          enabled : false
                      }
                  }
              },
              series: [{
                  name: 'ObsiFight',
                  data: data
              }]
          });
      })
  </script>
  <script>
      $.get('{{ url('/stats/users/graph/visits') }}', function (data) {
          data = data.graph;

          Highcharts.setOptions(Highcharts.theme);
          moment.locale('fr');
          new Highcharts.Chart({
              chart: {
                  zoomType: 'x',
                  type: 'column',
                  renderTo: 'graphVisits',
                  backgroundColor: "#fff"
              },
              colors: ["#27AE60"],
              title: {
                  text: false
              },
              subtitle: {
                  text: false
              },
              xAxis: {
                  categories: [
                      moment().subtract(7, 'day').format('dddd'),
                      moment().subtract(6, 'day').format('dddd'),
                      moment().subtract(5, 'day').format('dddd'),
                      moment().subtract(4, 'day').format('dddd'),
                      moment().subtract(3, 'day').format('dddd'),
                      moment().subtract(2, 'day').format('dddd'),
                      moment().subtract(1, 'day').format('dddd'),
                      moment().format('dddd')
                  ],
                  crosshair: true,
                  labels: {
                      enabled : false
                  }
              },
              yAxis: {
                  min: 0,
                  title: {
                      text: 'Visites'
                  }
              },
              legend: {
                  enabled: false
              },
              tooltip: {
                  shared: true,
                  valueSuffix: ' visites',
                  formatter: function() {
                      var s = '<span style="font-size: 10px">'+ this.x +'</span>';
                      var sortedPoints = this.points.sort(function(a, b){
                          return ((a.y > b.y) ? -1 : ((a.y < b.y) ? 1 : 0));
                      });

                      $.each(sortedPoints , function(i, point) {
                          s += '<br/><b>'+ point.y + " visites</b>";
                      });

                      return s;
                  }
              },
              plotOptions: {
                  spline: {
                      lineWidth: 3,
                      states: {
                          hover: {
                              lineWidth: 4
                          }
                      },
                      marker: {
                          enabled : false
                      }
                  }
              },
              series: [{
                  name: 'ObsiFight',
                  data: data
              }]
          });
      })
  </script>
@endsection