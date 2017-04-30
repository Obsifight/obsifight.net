<div class="site-header">
  @include('layouts.navbar')
  <div class="ui container">
    @if (isset($didYouKnow))
      <h2 class="ui header">
        <i class="info icon"></i>
        <div class="content">
          @lang('global.header.did-you-know')
        </div>
        <p>{{ $didYouKnow }}</p>
      </h2>
    @endif

    <!--<div class="text-center">
      <a href="https://www.facebook.com/ObsiFight/" class="ui facebook button">
        <i class="facebook icon"></i> Facebook
      </a>
      <a href="https://twitter.com/ObsiFight" class="ui twitter button">
        <i class="twitter icon"></i> Twitter
      </a>
      <a href="https://www.youtube.com/user/WaVeiTv" class="ui youtube button">
        <i class="youtube icon"></i> YouTube
      </a>
    </div>-->
  </div>
</div>
