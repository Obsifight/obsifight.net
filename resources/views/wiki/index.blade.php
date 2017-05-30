@extends('layouts.app')

@section('title', __('wiki.title'))

@section('content')
  <div class="ui container page-content">

    <div class="ui grid">
      @foreach ($categories as $category)
        <div class="ui sixteen wide mobile eight wide tablet four wide computer column">
          <div class="ui card">
            <div class="content">
              <div class="header">{{ $category->name }}</div>
            </div>
            <div class="content">
              <h4 class="ui sub header">@lang('wiki.articles')</h4>
              <div class="ui small feed">
                @foreach ($category->articles as $article)
                  <div class="event">
                    <div class="content">
                      <div class="summary">
                        <div class="ui list">
                          <a class="item {{ !$article->displayed ? 'disabled' : '' }}" href="{{ url("/wiki/{$article->slug}") }}" {{ !$article->displayed ? 'disabled' : '' }}>
                            <i class="right triangle icon"></i>
                            <div class="content">
                              <div class="header">
                                {{ $article->title }}
                                @if (!$article->displayed)
                                  &nbsp;&nbsp;<div class="ui yellow horizontal label">@lang('wiki.soon.label')</div>
                                @elseif ($article->version == env('APP_VERSION_COUNT'))
                                  &nbsp;&nbsp;<div class="ui green horizontal label">@lang('wiki.new.label')</div>
                                @endif
                              </div>
                            </div>
                          </a>
                        </div>
                      </div>
                    </div>
                  </div>
                @endforeach
              </div>
            </div>
          </div>
        </div>
      @endforeach
    </div>

  </div>
@endsection
@section('style')
  <style media="screen">
    .ui.list a.item .content .header {
      color: rgba(80, 79, 79, 0.87)!important;
    }
    .ui.card {
      width: 100%;
    }
    .label {
      text-transform: uppercase;
    }
  </style>
@endsection
