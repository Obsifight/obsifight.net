<div class="ui menu navbar">
  <div class="logo">
    <img src="{{ url('/img/logo-min.png') }}" width="60" alt="Logo">
  </div>

  <a onClick="$('.ui.menu.navbar.sidebar').sidebar('toggle')" class="item mobile-menu">
    <i class="sidebar icon"></i>
    Menu
  </a>

  <a href="{{ url('/') }}" class="item"><i class="home icon"></i> @lang('global.navbar.home')</a>
  <a href="{{ url('/join') }}" class="item"><i class="share icon"></i> @lang('global.navbar.join')</a>

  <a href="{{ url('/shop') }}" class="ui item">
    <i class="shop icon"></i> @lang('global.navbar.shop')
  </a>

  <div class="ui dropdown item">
    <i class="globe icon"></i> @lang('global.navbar.community')
    <i class="dropdown icon"></i>
    <div class="menu">
      <a href="https://forum.obsifight.net" class="item"><i class="shopping basket icon"></i> @lang('global.navbar.forum')</a>
      <a href="{{ url('/ranking') }}" class="item"><i class="sort numeric ascending icon"></i> @lang('global.navbar.ranking')</a>
      <a href="{{ url('/sanctions') }}" class="item"><i class="ban icon"></i> @lang('global.navbar.sanctions')</a>
      <a href="{{ url('/stats') }}" class="item"><i class="bar chart icon"></i> @lang('global.navbar.stats')</a>
    </div>
  </div>

  <a href="{{ url('/vote') }}" class="item"><i class="external icon"></i> @lang('global.navbar.vote')</a>

  <div class="ui dropdown item">
    <i class="help icon"></i> @lang('global.navbar.help')
    <i class="dropdown icon"></i>
    <div class="menu">
      <a href="{{ url('/wiki') }}" class="item"><i class="info icon"></i> @lang('global.navbar.wiki')</a>
      <a href="{{ url('/faq') }}" class="item"><i class="help circle icon"></i> @lang('global.navbar.faq')</a>
    </div>
  </div>

  <div class="right menu">
    @if (Auth::user())
      <div class="item">
        <a href="{{ url('/user') }}" class="ui obsifight button"><i class="user icon"></i> {{ Auth::user()->username }}</a>
      </div>
      <div class="item">
        <a href="{{ url('/logout') }}" class="ui button"><i class="sign out icon"></i> @lang('user.logout')</a>
      </div>
    @else
      <div class="item">
        <a href="{{ url('/signup') }}" class="ui obsifight button"><i class="signup icon"></i> @lang('user.signup')</a>
      </div>
      <div class="item">
        <a href="{{ url('/login') }}" class="ui button"><i class="sign in icon"></i> @lang('user.login')</a>
      </div>
    @endif
  </div>
</div>
