<div class="ui inverted vertical footer segment">
  <div class="ui container">
    <div class="ui stackable inverted divided equal height stackable grid">
      <div class="three wide column text-center">
        <h4 class="ui inverted header">@lang('global.footer.title.info')</h4>
        <div class="ui inverted link list">
          <a href="http://forum.obsifight.net/forums/bugs-site-et-boutique.104/" class="item">@lang('global.footer.report-bug')</a>
          <a href="http://forum.obsifight.net/misc/contact" class="item">@lang('global.footer.contact')</a>
          <a href="{{ url('/join') }}" class="item">@lang('global.footer.join')</a>
          <a href="{{ url('/shop') }}" class="item">@lang('global.footer.shop')</a>
        </div>
      </div>
      <div class="three wide column text-center">
        <h4 class="ui inverted header">@lang('global.footer.title.services')</h4>
        <div class="ui inverted link list">
          <a href="http://forum.obsifight.net" class="item">@lang('global.footer.forum')</a>
          <a href="https://obsifight.net/wiki" class="item">@lang('global.footer.wiki')</a>
          <a href="http://incidents.obsifight.net" class="item">@lang('global.footer.incidents')</a>
        </div>
      </div>
      <div class="three wide column">
        <div class="text-center">
          <a href="https://www.facebook.com/ObsiFight/" class="ui facebook button">
            <i class="facebook icon"></i> Facebook
          </a><br><br>
          <a href="https://twitter.com/ObsiFight" class="ui twitter button">
            <i class="twitter icon"></i> Twitter
          </a><br><br>
          <a href="https://www.youtube.com/user/WaVeiTv" class="ui youtube button">
            <i class="youtube icon"></i> YouTube
          </a>
        </div>
      </div>
      <div class="seven wide column text-center">
        <h4 class="ui inverted header">@lang('global.footer.title.credits')</h4>
        <p>@lang('global.footer.credit', ['link' => 'http://eywek.fr', 'username' => 'Eywek'])</p>
        @if(defined('VERSION'))
          <p>@lang('global.footer.version', ['version' => VERSION])</p>
        @endif
      </div>
    </div>
  </div>
</div>
