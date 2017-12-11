@extends('layouts.app')

@section('title', __('errors.forbidden'))

@section('content')
    <div class="ui container page-content">
        <img class="error" src="{{ url('/img/villager.png') }}" alt="villager">
        <h1 class="ui header">
            <div class="content">
                @lang('errors.forbidden.title')
                <div class="sub header">@lang('errors.forbidden.subtitle')</div>
            </div>
        </h1>
        <div class="clearfix"></div>
    </div>
@endsection
@section('style')
    <style>
        img.error {
            float: left;
        }
        h1.ui.header .content {
            padding-top: 10%;
            font-size: 2.8em;
            line-height: 1em;
        }
        h1.ui.header .content .sub.header {
            padding-top: 4%;
            text-align: center;
            font-style: italic;
            font-size: 0.3em;
        }
        .clearfix {
            content: "";
            display: table;
            clear: both;
        }
    </style>
@endsection