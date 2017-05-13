@extends('layouts.app')

@section('title', __('global.navbar.faq'))

@section('content')
  <div class="ui container page-content">
    <h2 class="ui center aligned header">
      <i class="question mark icon"></i>
      <div class="content">
         @lang('global.navbar.faq')
      </div>
    </h2>
    <div class="ui divider"></div>

    <div class="ui styled fluid accordion">
      @foreach ($questions as $question)
        <div class="title">
          <i class="dropdown icon"></i>
          {{ $question->question }}
        </div>
        <div class="content">
          <p class="transition hidden">{!! $question->answer !!}</p>
        </div>
      @endforeach
    </div>
  </div>
@endsection
@section('script')
  <script type="text/javascript">
  $('.accordion').accordion()
  </script>
@endsection
