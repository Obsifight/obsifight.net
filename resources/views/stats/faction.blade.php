@extends('layouts.app')

@section('title', $faction->name)

@section('content')
    <div class="ui container page-content">
        <h1 class="ui center aligned header">
            <div class="content">
                <a href="{{ url('/stats/' . $faction->leader->username) }}" class="ui blue image medium label">
                    {{ $faction->leader->username }}
                    <div class="detail">@lang('stats.factions.role.leader')</div>
                </a>
                {{ $faction->name }}
                <div class="sub header" style="margin-top:5px;">@lang('stats.factions.created_at', ['date' => $faction->created_at->diffForHumans()])</div>
            </div>
        </h1>
        <div class="ui divider"></div>

        <div class="ui stackable grid" style="position:relative;">

            <div class="ui eight wide column">
                <h2 class="ui header">
                    @lang('stats.factions.infos.title')
                    <div class="sub header">@lang('stats.factions.infos.subtitle')</div>
                </h2>
                <br>

                <div class="ui two small statistics">
                    <div class="statistic">
                        <div class="value">
                            <i class="list icon" style="color:#ffd700"></i>
                            {{ $faction->position }}
                            @if ($faction->stats->counts->position > 0)
                                <span class="stats-up">
                                    <i class="icon chevron up"></i>
                                    {{ $faction->stats->counts->position }}
                                </span>
                            @elseif ($faction->stats->counts->position < 0)
                                <span class="stats-down">
                                    <i class="icon chevron down"></i>
                                    {{ $faction->stats->counts->position * - 1 }}
                                </span>
                            @endif
                        </div>
                        <div class="label">
                            @lang('stats.factions.position')
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="value">
                            <i class="trophy icon" style="color:#ffd700"></i> {{ $faction->score }}
                        </div>
                        <div class="label">
                            @lang('stats.factions.score')
                        </div>
                    </div>
                </div>
                <div class="ui four small statistics">
                    <div class="statistic">
                        <div class="value">
                            {{ count($faction->players) }}
                        </div>
                        <div class="label">
                            @lang('stats.factions.players.count')
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="value">
                            {{ $faction->claims_count }}
                        </div>
                        <div class="label">
                            @lang('stats.factions.claims.count')
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="value">
                            {{ $faction->kills_count }}
                        </div>
                        <div class="label">
                            @lang('stats.factions.kills')
                        </div>
                    </div>
                    <div class="statistic">
                        <div class="value">
                            {{ $faction->deaths_count }}
                        </div>
                        <div class="label">
                            @lang('stats.factions.deaths')
                        </div>
                    </div>
                </div>
                <br>
                <div class="ui indicating progress" id="power">
                    <div class="bar">
                        <div class="progress"></div>
                    </div>
                    <div class="label">@lang('stats.factions.power')</div>
                </div>
            </div>
            <div class="ui vertical divider"></div>
            <div class="ui eight wide column">
                <h2 class="ui header">
                    @lang('stats.factions.members.title')
                    <div class="sub header">@lang('stats.factions.members.subtitle')</div>
                </h2>
                <br>

                @foreach ($faction->players as $member)
                    <a href="{{ url('/stats/' . $member->username) }}">
                        <img src="https://skins.obsifight.net/head/{{ $member->username }}/64"
                             class="ui rounded member image" alt="{{ $member->username }}" data-toggle="popup"
                             data-variation="inverted" data-placement="top center"
                             data-content="{{ $member->username }}">
                    </a>
                @endforeach
            </div>

        </div>
        <div class="ui divider"></div>
        <div class="ui stackable grid" style="position:relative;">
            <div class="ui eight wide column">
                <h2 class="ui header">
                    @lang('stats.success.title')
                    <div class="sub header">@lang('stats.success.subtitle', ['number' => env('APP_VERSION_COUNT')])</div>
                </h2>
                <br>

                @foreach($faction->successList as $successList)
                    @foreach($successList as $successName => $successValue)
                        <span class="ui achievement {{ is_bool($successValue) ? ($successValue ? 'green' : 'red') : ($successValue == 100 ? 'green' : 'active p' . $successValue) }} label">
                            <i class="{{ $successValue == 100 || $successValue === true ? 'check' : ($successValue === false ? 'remove' : 'wait') }} icon"></i>
                            {{ $successName }}
                        </span>
                    @endforeach
                    @if (!$loop->last)
                        <div class="ui divider"></div>
                    @endif
                @endforeach

            </div>
            <div class="ui vertical divider"></div>
            <div class="ui eight wide column">
                <h2 class="ui header">
                    @lang('stats.factions.ressources.title')
                    <div class="sub header">@lang('stats.factions.ressources.subtitle')</div>
                </h2>
                <br>
                <div id="graph-materials"></div>
            </div>
        </div>
    </div>
@endsection
@section('style')
    <style media="screen">
        .stats-down, .stats-up {
            font-size: 15px;
            position: absolute;
            margin-left: 5px;
        }

        .stats-up {
            color: #30b535;
        }

        .stats-down {
            color: #b53124;
        }

        img.member.image {
            background-color: #bdc3c7;
            border: 2px solid #c0392b;
            margin-right: 5px;
            margin-bottom: 5px;
            display: inline-block;
        }

        .ui.grid > .column + .divider, .ui.grid > .row > .column + .divider {
            left: 50%;
        }

        .ui.vertical.divider:after, .ui.vertical.divider:before {
            height: 100%;
        }

        .label {
            margin-top: 5px !important;
        }

        .achievement.active {
            color: #fff !important;
        }

        .achievement.active.label {
            position: relative;
            background: transparent !important;
        }

        .achievement.active.label:before {
            background: #767676;
            content: '';
            z-index: -2;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: 0;
            border-radius: .28571429rem;
        }

        .achievement.active.label:after {
            background: #2185D0;
            content: '';
            z-index: -1;
            position: absolute;
            top: 0;
            bottom: 0;
            left: 0;
            right: calc(100% - 0%);
            border-radius: .28571429rem;
        }

        @for ($i = 0; $i <= 100; $i += 0.1)
            .achievement.active.label.p{{ str_replace('.', '-', round($i, 1)) }}:after {
                right: calc(100% - {{ round($i, 1) }}%);
            }
        @endfor
    </style>
@endsection
@section('script')
    <script src="https://code.highcharts.com/highcharts.js"></script>
    <script type="text/javascript">
        $('#power').progress({
            total: '{{ $faction->max_power }}',
            value: '{{ $faction->current_power }}',
            text: {
                percent: '{value}/{total}'
            }
        })
        $(document).ready(function () {
            $('[data-toggle="popup"]').each(function (k, el) {
                $(el).popup({
                    html: $(el).attr('data-content'),
                    position: $(el).attr('data-placement'),
                    variation: $(el).attr('data-variation')
                })
            })
        })
    </script>
    <script type="text/javascript">
        Highcharts.chart('graph-materials', {

            title: {
                text: '@lang('stats.factions.graph.title.materials')'
            },

            subtitle: {
                text: '@lang('stats.factions.graph.update.range', ['range' => $faction->stats->update_range])'
            },

            yAxis: {
                title: {
                    text: ''
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'middle'
            },

            xAxis: {
                categories: {!! json_encode($faction->stats->graphs->materials->x_axis) !!},
            },

            series: {!! json_encode($faction->stats->graphs->materials->data) !!},

            responsive: {
                rules: [{
                    condition: {
                        maxWidth: 1500
                    },
                    chartOptions: {
                        legend: {
                            layout: 'horizontal',
                            align: 'center',
                            verticalAlign: 'bottom'
                        }
                    }
                }]
            }

        });
    </script>
@endsection
