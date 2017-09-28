@extends('layouts.app')

@section('title', __('sanction.contest.title'))

@section('content')
    <div class="ui container page-content">

        @if(Auth::user())
            <h2 class="ui center aligned icon header">
                <i class="circular ban icon"></i>
                @lang('sanction.contest.title')
            </h2>

            <div class="ui three top attached ordered steps">
                <div class="active step" id="stepchooseSanction">
                    <div class="content">
                        <div class="title">@lang('sanction.contest.sanction')</div>
                        <div class="description">@lang('sanction.contest.step.one.subtitle')</div>
                    </div>
                </div>
                <div class="disabled step" id="stepExplainSanction">
                    <div class="content">
                        <div class="title">@lang('sanction.contest.step.two.title')</div>
                        <div class="description">@lang('sanction.contest.step.two.subtitle')</div>
                    </div>
                </div>
            </div>
            <div class="ui attached segment">

                <div class="step-content" data-step="chooseSanction">

                    @foreach($sanctions as $sanction)

                        <div class="ui segment">
                            @if ($sanction->type === 'ban')
                                <div class="ui red horizontal label">@lang('sanction.ban')</div>
                            @else
                                <div class="ui orange horizontal label">@lang('sanction.mute')</div>
                            @endif
                            « <b>{{ $sanction->reason }}</b> »
                            <em>{{ $sanction->date->diffForHumans() }}</em>
                            @if($sanction->contest)
                                <a href="{{ url('/sanctions/contest') . '/' . $sanction->contest->id }}" class="ui primary right floated button" style="margin-top: -7px;">
                                    @lang('sanction.contest.view.btn')
                                </a>
                            @else
                                <button data-sanction='{{ json_encode($sanction) }}' class="ui primary right floated button" style="margin-top: -7px;">
                                    @lang('sanction.contest')
                                </button>
                            @endif
                            <div class="clearfix"></div>
                        </div>

                        @if(!$loop->last)
                            <div class="ui divider"></div>
                        @endif
                    @endforeach

                </div>

                <div class="step-content" data-step="ExplainSanction" style="display:none;">
                    <div class="ui stackable two column grid">

                        <div class="sixteen wide mobile eight wide tablet four wide computer column">
                            <div class="ui card">
                                <div class="content">
                                    <div class="header">@lang('sanction.contest.infos')</div>
                                    <div class="meta" data-sanction-info="date">...</div>
                                </div>
                                <div class="content">
                                    <h4 class="ui sub header" data-sanction-info="msg-type">...</h4>
                                    <div class="ui small feed">

                                        <div class="ui visible message" style="margin-bottom:0px;" data-sanction-info="reason">
                                            ...
                                        </div>

                                        <a class="ui pointing blue image floated right label" style="margin-top:5px;" data-sanction-info="staff-username">
                                            ...
                                        </a>

                                    </div>
                                </div>
                                <div class="extra content">
                                    <a class="fluid ui negative button" style="display:none;" data-sanction-info="duration-permanent">@lang('sanction.permanent')</a>
                                    <a class="fluid ui orange button" style="display:none;" data-sanction-info="duration">@lang('sanction.temp') (<span class="sanction-duration">N/A</span>)</a>
                                </div>
                            </div>
                        </div>

                        <div class="sixteen wide mobile eight wide tablet twelve wide computer column">
                            <form class="ui form" method="post" action="{{ url('/sanctions/contest') }}" data-ajax>
                                <div class="ajax-message" style="margin-bottom:10px;"></div>
                                <input type="hidden" name="sanction">
                                <input type="hidden" name="sanction_type">
                                <div class="field">
                                    <label>@lang('sanction.contest.reason')</label>
                                    <textarea name="reason"></textarea>
                                </div>
                                <button id="confirmExplain" class="ui labeled icon green right floated button">
                                    <i class="checkmark icon"></i>
                                    @lang('global.confirm')
                                </button>
                            </form>
                        </div>

                    </div>
                </div>
            </div>
        @else
            <div class="ui segment">
                <div class="ui icon error message">
                    <i class="notched user icon"></i>
                    <div class="content">
                        <div class="header">
                           @lang('sanction.contest.title')
                        </div>
                        <p>@lang('sanction.contest.need_login')</p>
                    </div>
                </div>
            </div>
        @endif

        @if (count($contests))
            <div class="ui stacked segment">
                <table class="ui celled table" id="list">
                    <thead>
                    <tr>
                        <th>@lang('sanction.contest.user')</th>
                        <th>@lang('sanction.contest.type')</th>
                        <th>@lang('sanction.contest.date')</th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                        @foreach ($contests as $contest)
                            <tr>
                                <td>{{ $contest->user->username }}</td>
                                <td>
                                    @if ($contest->sanction_type === 'ban')
                                        <div class="ui red horizontal label">@lang('sanction.ban')</div>
                                    @else
                                        <div class="ui orange horizontal label">@lang('sanction.mute')</div>
                                    @endif
                                </td>
                                <td>
                                    {{ $contest->created_at->diffForHumans() }}
                                </td>
                                <td>
                                    <a href="{{ url('/sanctions/contest/' . $contest->id) }}" class="ui primary right floated button">@lang('sanction.contest.view.btn')</a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif

    </div>
@endsection
@section('script')
    <script type="text/javascript">
        $('button[data-sanction]').on('click', function (e) {
            e.preventDefault()

            var btn = $(this)
            var sanction = JSON.parse(btn.attr('data-sanction'))
            var type = sanction.type

            $('[data-sanction-info="date"]').html(sanction.date)

            // type
            if (type === 'ban') // ban
                $('[data-sanction-info="msg-type"]').html('@lang('sanction.ban.msg')')
            else if (type === 'mute') // mute
                $('[data-sanction-info="msg-type"]').html('@lang('sanction.mute.msg')')
            else
                throw new Error('Invalid sanction type')

            // reason
            $('[data-sanction-info="reason"]').html(sanction.reason)

            // staff
            $('[data-sanction-info="staff-username"]').html(sanction.staff.username)

            // duration
            if (sanction.duration === 'PERMANENT')
                $('[data-sanction-info="duration-permanent"]').show()
            else {
                $('[data-sanction-info="duration"]').show()
                $('.sanction-duration').html(formatSeconds(sanction.duration))
            }

            $('input[name="sanction"]').val(sanction.id)
            $('input[name="sanction_type"]').val(sanction.type)

            // display next step
            setTimeout(function () {
                nextStep('chooseSanction', 'ExplainSanction')
            }, 500)
        })

        function nextStep(previous, next) {
            var previousStepDiv = $('#step' + previous)
            var previousStepContent = $('.step-content[data-step="' + previous + '"]')

            var nextStepDiv = $('#step' + next)
            var nextStepContent = $('.step-content[data-step="' + next + '"]')

            previousStepDiv.addClass('completed').removeClass('active')
            nextStepDiv.addClass('active').removeClass('disabled')

            previousStepContent.transition('slide right')
            nextStepContent.transition('slide left')
        }

        function formatSeconds (s, brut) {
            var fm = [
                Math.floor(s / 60 / 60 / 24), // DAYS
                Math.floor(s / 60 / 60) % 24, // HOURS
                Math.floor(s / 60) % 60, // MINUTES
                s % 60 // SECONDS
            ]
            var result = $.map(fm, function (v, i) {
                return v
            })

            if (brut)
                return result

            // formatting to string
            var durationFormatted = ''

            if (result[0] > 0)
                durationFormatted += result[0] + ' @lang('global.days') '
            if (result[1] > 0)
                durationFormatted += result[1] + ' @lang('global.hours') '
            if (result[2] > 0)
                durationFormatted += result[2] + ' @lang('global.minutes') '
            if (result[3] > 0)
                durationFormatted += result[3] + ' @lang('global.seconds') '

            return durationFormatted.slice(0, -1)
        }
    </script>
@endsection