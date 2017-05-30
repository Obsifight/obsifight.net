@extends('layouts.app')

@section('title', $article->title)

@section('content')
  <div class="ui container page-content">
    <h2 class="ui center aligned header">
      <div class="content">
        {{ $article->title }}
      </div>
    </h2>
    <div class="ui divider"></div>
    @if ($article->version == env('APP_VERSION_COUNT'))
      <div class="ui info message">
        <div class="header">
          @lang('wiki.new')
        </div>
        <p>@lang('wiki.new.content', ['version' => env('APP_VERSION_COUNT')])</p>
      </div>
    @endif
    <article>
      {!! $article->content !!}
    </article>
    <small>
      <em style="float:right;margin:10px;color:#777;">
        @lang('wiki.version', ['version' => $article->version])
      </em>
    </small>
    <br><br>
  </div>
@endsection
