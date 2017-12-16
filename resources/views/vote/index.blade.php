@extends('layouts.app')

@section('title', __('vote.title'))

@section('content')
    <div class="ui container page-content">
        <div class="ui info message">
            <div class="header">
                @lang('vote.position', ['position' => '<span id="position">&nbsp;&nbsp;<div class="ui active inline tiny loader"></div>&nbsp;&nbsp;</span>'])
            </div>
            <p>
                @lang('vote.tutorial.title')
            </p>
            <a href="{{ url(env('VOTE_HELP_LINK')) }}" target="_blank" class="ui primary button" style="position:absolute;right:10px;top:23px;">
                @lang('vote.tutorial.btn')
            </a>
        </div>

        <div class="ui ordered fluid stackable top attached steps">
            <div class="active step" data-step-display="1">
                <div class="content">
                    <div class="title">@lang('vote.step.one.title')</div>
                    <div class="description">@lang('vote.step.one.content')</div>
                </div>
            </div>
            <div class="disabled step" data-step-display="2">
                <div class="content">
                    <div class="title">@lang('vote.step.two.title')</div>
                    <div class="description">@lang('vote.step.two.content')</div>
                </div>
            </div>
            <div class="disabled step" data-step-display="3">
                <div class="content">
                    <div class="title">@lang('vote.step.three.title')</div>
                    <div class="description">@lang('vote.step.three.content')</div>
                </div>
            </div>
            <div class="disabled step" data-step-display="4">
                <div class="content">
                    <div class="title">@lang('vote.step.four.title')</div>
                    <div class="description">@lang('vote.step.four.content')</div>
                </div>
            </div>
        </div>
        <div class="ui attached segment">
            <div data-step="1" class="active">
                <form class="ui form" method="post" action="{{ url('/vote/step/one') }}" data-ajax
                      data-ajax-custom-callback="afterStepOne">

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
                <form class="ui form" method="post" action="{{ url('/vote/step/three') }}" data-ajax
                      data-ajax-custom-callback="afterStepThree">

                    <div class="field">
                        <label>@lang('vote.step.three.content.input.label')</label>
                        <input type="text" name="out" autocomplete="off"
                               placeholder="@lang('vote.step.three.content.input.placeholder')"
                               style="width:200px;text-align:center;">
                    </div>

                    <button type="submit" class="ui green animated button">
                        <div class="visible content">@lang('vote.step.three.content.input.btn')</div>
                        <div class="hidden content"><i class="right arrow icon"></i></div>
                    </button>
                </form>
            </div>
            <div data-step="4">
                <form method="post" action="{{ url('/vote/step/four') }}" data-ajax
                      data-ajax-custom-callback="afterStepFour" data-ajax-custom-data="beforeStepFour">
                    <button type="submit" data-reward-type="now"
                            class="ui animated fade yellow massive button disabled">
                        <div class="visible content">@lang('vote.step.four.content.btn.now')</div>
                        <div class="hidden content">
                            @lang('vote.step.four.content.btn.now.hover') <i class="right arrow icon"></i>
                        </div>
                    </button>
                    <button type="submit" data-reward-type="after" class="ui animated fade yellow massive button">
                        <div class="visible content">@lang('vote.step.four.content.btn.after')</div>
                        <div class="hidden content">
                            @lang('vote.step.four.content.btn.after.hover') <i class="right arrow icon"></i>
                        </div>
                    </button>
                </form>
            </div>
        </div>

    </div>
    <div class="colored-block">
        <div class="ui container text-center">
            <h2 class="ui header">
                <div class="content">
                    @lang('vote.ranking')
                    <div class="sub header">@lang('vote.ranking.subtitle')</div>
                </div>
            </h2>
            <table class="ui very basic table table">
                <thead>
                <tr>
                    <th>@lang('vote.ranking.position')</th>
                    <th>@lang('vote.ranking.username')</th>
                    <th>@lang('vote.ranking.count')</th>
                    <th>@lang('vote.ranking.win')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($ranking as $row)
                    <tr>
                        <td>#{{ $loop->index + 1 }}</td>
                        <td>{{ $row->user->username }}</td>
                        <td>{{ $row->votes_count }}</td>
                        <td>
                            <div class="ui yellow button" data-placement="left center" data-toggle="popup"
                                 data-content="- {{ implode('<br>-', explode(',', $kits[$loop->index]->content)) }}">
                                Kit voteur {{ $loop->index + 1 }}
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="white-block">
        <div class="ui container text-center">
            <h2 class="ui header">
                <div class="content">
                    @lang('vote.rewards.title')
                    <div class="sub header">@lang('vote.rewards.subtitle')</div>
                </div>
            </h2>
            <table class="ui very basic table table">
                <thead>
                <tr>
                    <th>@lang('vote.rewards.name')</th>
                    <th>@lang('vote.rewards.probability')</th>
                </tr>
                </thead>
                <tbody>
                @foreach ($rewards as $reward)
                    <tr>
                        <td>{{ $reward->name }}</td>
                        <td>
                            <div class="ui {{ ($reward->probability <= 5 ? 'red' : ($reward->probability <= 10 ? 'orange' : ($reward->probability <= 30 ? 'green' : 'grey'))) }} horizontal label">{{ $reward->probability }}
                                %
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
@section('script')
    <script type="text/javascript">
        function next(step) {
            $('[data-step-display="' + (step - 1).toString() + '"]').removeClass('active').addClass('completed')
            $('[data-step-display="' + step.toString() + '"]').removeClass('disabled').addClass('active')
        }

        function afterStepOne(req, res) {
            $('[data-step="1"]').slideUp(100, function () {
                $('[data-step="2"]').slideDown(100)
                next(2)
            })
        }

        function afterStepThree(req, res) {
            // Check if logged
            $.get('{{ url('/user/server/logged') }}/' + $('input[name="username"]').val(), function (data) {
                if (data.status && data.logged) {
                    $('[data-reward-type="now"]').removeClass('disabled')
                    $('[data-reward-type="after"]').addClass('disabled')
                }
            })
            $('[data-step="3"]').slideUp(150, function () {
                $('[data-step="4"]').slideDown(150)
                next(4)
            })
        }

        function beforeStepFour(form, btn) {
            if (btn.attr('data-reward-type') == 'now')
                return {type: 'now'}
            else
                return {type: 'after'}
        }

        function afterStepFour(req, res) {
            $('[data-reward-type="now"]').addClass('disabled')
            $('[data-reward-type="after"]').addClass('disabled')
        }

        $(document).ready(function () {
            $('[data-step="2"] a').on('click', function () {
                $('[data-step="2"]').slideUp(100, function () {
                    $('[data-step="3"]').slideDown(100)
                    next(3)
                })
            })
            $('[data-toggle="popup"]').each(function (k, el) {
                $(el).popup({
                    html: $(el).attr('data-content'),
                    position: $(el).attr('data-placement')
                })
            })
        })

        $.get('{{ url('/vote/position') }}', function (data) {
            if (!data.status)
                return $('#position').parent().remove()
            $('#position').html(data.position)
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

        .colored-block h2 {
            margin-bottom: 50px !important;
        }

        .colored-block table.very.basic.table {
            color: #fff;
        }

        .colored-block table.very.basic.table thead th {
            color: #fff;
        }
    </style>
@endsection
