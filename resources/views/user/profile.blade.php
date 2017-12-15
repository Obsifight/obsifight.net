@extends('layouts.app')
@section('title', Auth::user()->username)

@section('content')
  <div class="ui container page-content">

    <h1 class="ui center aligned header">
      <img class="ui rounded image" src="{{ env('SKINS_DISPLAY_URL') }}/head/{{ Auth::user()->username }}">
      <div class="content">
        {{ Auth::user()->username }}
        <div class="sub header">@lang('user.profile.created.string', ['date' => Auth::user()->created_at->diffForHumans()])</div>
      </div>
    </h1>

    <div class="ui divider"></div>

    @role('restricted')
      <div class="ui red message">
        <i class="close icon"></i>
        <div class="header">
          @lang('user.role.restricted')
        </div>
        @lang('user.role.restricted.description')
      </div>
    @endrole

    @if(!$confirmedAccount && Auth::user()->can('user-send-confirmation-email'))
      <div class="ui warning message">
        <i class="close icon"></i>
        <div class="header">
          @lang('user.profile.confirmed.title')
        </div>
        @lang('user.profile.confirmed.description', ['url' => action('UserController@sendConfirmationMail')])
      </div>
    @endif

    @if($notifications)
      @foreach ($notifications as $notification)
        <div class="ui {{ $notification['type'] }} message">
          {!! $notification['message'] !!}
          @if (!$notification->auto_seen)
            /user/notification/seen/
          @endif
        </div>
      @endforeach
    @endif

    <div class="ui grid two column">
      <div class="ui sixteen wide mobile sixteen wide tablet four wide computer column">
        <div class="ui vertical fluid menu">
          <a class="item toggle-menu active" data-toggle="infos">
            <i class="user left aligned icon"></i>
            @lang('user.profile.menu.infos')
          </a>
          @ability('', 'user-upload-skin,user-upload-cape')
            <a class="item toggle-menu" data-toggle="appearence">
              <i class="theme left aligned icon"></i>
              @lang('user.profile.menu.appearence')
            </a>
          @endability
          @ability('', 'user-enable-two-factor-auth,user-disable-two-factor-auth,user-enable-obsiguard,user-disable-obsiguard,user-add-ip-obsiguard,user-remove-ip-obsiguard')
            <a class="item toggle-menu" data-toggle="security">
              <i class="lock left aligned icon"></i>
              @lang('user.profile.menu.security')
              @if (!$twoFactorEnabled || !$findObsiGuardIPs)
                <i class="warning sign icon" style="color:#{{ $twoFactorEnabled ? 'FE9A76' : 'B03060' }}"></i>
              @endif
            </a>
          @endability
          <a class="item toggle-menu" data-toggle="login-logs">
            <i class="sign in left aligned icon"></i>
            @lang('user.profile.menu.login.logs')
          </a>
          <a class="item toggle-menu" data-toggle="spendings">
            <i class="shopping basket left aligned icon"></i>
            @lang('user.profile.menu.spendings')
          </a>
          <a class="item toggle-menu" data-toggle="socials">
            <i class="twitter left aligned icon"></i>
            @lang('user.profile.menu.socials')
          </a>
        </div>
      </div>
      <div class="ui sixteen wide mobile sixteen wide tablet twelve wide computer column">
        <div class="menu-content">
          <div data-menu="infos">
            <div class="ui form">
              <h4 class="ui dividing header">@lang('user.profile.personnals.details')</h4>
              <div class="field">
                <label>@lang('user.field.username')</label>
                <div class="fields">
                  <div class="twelve wide field">
                    <input type="text" id="username" value="{{ Auth::user()->username }}" disabled>
                  </div>
                  @permission('user-edit-username')
                    <div class="four wide field" id="usernameBtn">
                      <button type="button" class="fluid ui primary button" onClick="$('.ui.modal#editUsername').modal({blurring: true}).modal('show')"><i class="edit icon"></i> @lang('user.profile.username.edit')</button>
                    </div>
                  @endpermission
                </div>
              </div>
              <div class="field">
                <label>@lang('user.field.email')</label>
                <div class="fields">
                  <div class="twelve wide field">
                    <input type="text" value="{{ Auth::user()->email }}" disabled>
                  </div>
                  @permission('user-request-edit-email')
                    <div class="four wide field">
                      <button type="button" class="fluid ui primary button" onClick="$('.ui.modal#editEmail').modal({blurring: true}).modal('show')"><i class="edit icon"></i> @lang('user.profile.email.edit')</button>
                    </div>
                  @endpermission
                </div>
              </div>
              @permission('user-edit-password')
                <div class="ui divider"></div>
                <div class="field">
                  <label>@lang('user.field.password')</label>
                  <form method="post" action="{{ url('/user/password') }}" data-ajax>
                    <div class="fields">
                      <div class="five wide field">
                        <input type="password" name="password" placeholder="@lang('user.profile.password.edit.placeholder')">
                      </div>
                      <div class="five wide field">
                        <input type="password" name="password_confirmation" placeholder="@lang('user.profile.password.edit.placeholder')">
                      </div>
                      <div class="six wide field">
                        <button type="submit" class="fluid ui red button"><i class="edit icon"></i> @lang('user.profile.password.edit')</button>
                      </div>
                    </div>
                  </form>
                </div>
              @endpermission
            </div>

            <br><br>

            <div class="ui divider"></div>

            <div class="ui three statistics">
              <div class="statistic">
                <div class="value">
                  <span id="money">{{ Auth::user()->money }}</span>
                  @permission('user-transfer-money')
                    <i onClick="$('.ui.modal#transferMoney').modal({blurring: true}).modal('show')" class="send icon"></i>
                  @endpermission
                </div>
                <div class="label">
                  @lang('user.money')
                </div>
              </div>
              <div class="statistic">
                <div class="value">
                  {{ $votesCount }}
                </div>
                <div class="label">
                  @lang('user.votes')
                </div>
              </div>
              <div class="statistic">
                <div class="value">
                  {{ $rewardsWaitedCount }}
                  <a href="{{ url('/vote/reward/get/waited') }}"><i class="hand rock icon"></i></a>
                </div>
                <div class="label">
                  @lang('user.rewards_waited')
                </div>
              </div>
            </div>

            <br><br>
          </div>

          <div data-menu="appearence" style="display:none;">

            @permission('user-upload-skin')
              <h3 class="ui dividing header">
                @lang('user.profile.appearence.skin')
              </h3>

              @if ($votesCount < 3)
                <div class="ui error message">
                  <div class="header">
                    @lang('form.error.title')
                  </div>
                  @lang('user.profile.appearence.skin.error.vote')
                </div>
              @else
                <div class="ui card" style="width:500px;">
                  <div class="content">
                    <h4 class="ui sub header">@lang('user.profile.appearence.specifics')</h4>
                    <div class="ui small feed">
                      <div class="ui list">
                        <a class="item">
                          <i class="resize horizontal icon"></i>
                          <div class="content">
                            <div class="header">@lang('user.profile.appearence.specifics.width')</div>
                            <div class="description">@lang('user.profile.appearence.specifics.width.subtitle', ['max_width' => env('SKINS_UPLOAD_MAX_WIDTH'), 'max_height' => env('SKINS_UPLOAD_MAX_HEIGHT')])</div>
                          </div>
                        </a>
                        <a class="item">
                          <i class="compress icon"></i>
                          <div class="content">
                            <div class="header">@lang('user.profile.appearence.specifics.size')</div>
                            <div class="description">@lang('user.profile.appearence.specifics.size.subtitle', ['max_size' => round(env('SKINS_UPLOAD_MAX_SIXE') / 1000000)])</div>
                          </div>
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="extra content text-center">
                    <form action="{{ url('/user/skin') }}" method="post" data-ajax data-ajax-upload-image>
                      <div style="display:inline-block">
                        <label for="skin" class="ui labeled icon button">
                          <i class="file image outline icon"></i>
                          <span class="filename">@lang('user.profile.appearence.skin.choose')</span>
                        </label>
                        <input type="file" name="image" accept="image/x-png,image/png" id="skin" style="display:none">
                      </div>
                      <button type="submit" class="ui primary button">@lang('user.profile.appearence.skin.send')</button>
                    </form>
                  </div>
                </div>
              @endif
            @endpermission

            @permission('user-upload-cape')
              <h3 class="ui dividing header">
                @lang('user.profile.appearence.cape')
              </h3>

              @if ($votesCount < 3)
                <div class="ui error message">
                  <div class="header">
                    @lang('form.error.title')
                  </div>
                  @lang('user.profile.appearence.cape.error.vote')
                </div>
              @elseif ($cape === 0)
                <div class="ui error message">
                  <div class="header">
                    @lang('form.error.title')
                  </div>
                  @lang('user.profile.appearence.cape.error.purchase')
                </div>
              @else
                <div class="ui card" style="width:500px;">
                  <div class="content">
                    <h4 class="ui sub header">@lang('user.profile.appearence.specifics')</h4>
                    <div class="ui small feed">
                      <div class="ui list">
                        <a class="item">
                          <i class="resize horizontal icon"></i>
                          <div class="content">
                            <div class="header">@lang('user.profile.appearence.specifics.width')</div>
                            <div class="description">@lang('user.profile.appearence.specifics.width.subtitle', ['max_width' => env('SKINS_UPLOAD_MAX_WIDTH'), 'max_height' => env('SKINS_UPLOAD_MAX_HEIGHT')])</div>
                          </div>
                        </a>
                        <a class="item">
                          <i class="compress icon"></i>
                          <div class="content">
                            <div class="header">@lang('user.profile.appearence.specifics.size')</div>
                            <div class="description">@lang('user.profile.appearence.specifics.size.subtitle', ['max_size' => round(env('SKINS_UPLOAD_MAX_SIXE') / 1000000)])</div>
                          </div>
                        </a>
                      </div>
                    </div>
                  </div>
                  <div class="extra content text-center">
                    <form action="{{ url('/user/cape') }}" method="post" data-ajax data-ajax-upload-image>
                      <div style="display:inline-block">
                        <label for="cape" class="ui labeled icon button">
                          <i class="file image outline icon"></i>
                          <span class="filename">@lang('user.profile.appearence.cape.choose')</span>
                        </label>
                        <input type="file" name="image" accept="image/x-png,image/png" id="cape" style="display:none">
                      </div>
                      <button type="submit" class="ui primary button">@lang('user.profile.appearence.cape.send')</button>
                    </form>
                  </div>
                </div>
              @endif
            @endpermission

          </div>

          <div data-menu="security" style="display:none;">
            <div class="ui info icon message">
              <i class="protect icon"></i>
              <div class="content">
                <div class="header">
                  @if (!$twoFactorEnabled)
                    @lang('user.two_factor_auth.title.enable')
                  @else
                    @lang('user.two_factor_auth.title.disable')
                  @endif
                </div>
                <p>@lang('user.two_factor_auth.subtitle', ['link' => 'http://forum.obsifight.net/threads/la-double-authentification.20892/'])</p>
                @if (!$twoFactorEnabled)
                  <a href="{{ url('/user/two-factor-auth/enable') }}" class="ui primary button" style="position:absolute;right:10px;top:10px;">@lang('user.two_factor_auth.enable')</a>
                @else
                  <a href="{{ url('/user/two-factor-auth/disable') }}" class="ui primary button" style="position:absolute;right:10px;top:10px;">@lang('user.two_factor_auth.disable')</a>
                @endif
              </div>
            </div>

            <div class="ui divider"></div>

            <div id="obsiguardDisabled" style="display:{{ count($findObsiGuardIPs) <= 0 ? 'block' : 'none' }};">
              <div class="ui info icon message">
                <i class="protect icon"></i>
                <div class="content">
                  <div class="header">
                    @lang('user.obsiguard.title.enable')
                  </div>
                  <p>@lang('user.obsiguard.subtitle', ['link' => 'http://forum.obsifight.net/threads/utiliser-obsiguard.17946/'])</p>
                  <button data-obsiguard-action="enable" class="ui primary button" style="position:absolute;right:10px;top:10px;">@lang('user.obsiguard.enable')</button>
                </div>
              </div>
            </div>
            <div id="obsiguardEnabled" style="display:{{ count($findObsiGuardIPs) <= 0 ? 'none' : 'block' }};">
              <h3 class="ui center aligned icon header">
                <i class="circular protect icon"></i>
                @lang('user.obsiguard')
                <small><em><a href="#" data-obsiguard-action="disable" style="color:#dd4b39;">@lang('user.obsiguard.disable')</a></em></small>
              </h3>

              <h4 class="ui dividing header">@lang('user.obsiguard.list')</h4>

              <table class="ui striped table">
                <thead>
                  <tr>
                    <th>@lang('user.obsiguard.list.ip')</th>
                    <th>@lang('user.obsiguard.list.action')</th>
                  </tr>
                </thead>
                <tbody id="obsiguard-ips">
                  @foreach ($findObsiGuardIPs as $ip)
                    <tr data-obsiguard-id="{{ $ip->id }}">
                      <td>{{ $ip->ip }}</td>
                      <td>
                        <button class="ui red button" data-obsiguard-action="remove" name="button">@lang('user.obsiguard.list.action.remove')</button>
                      </td>
                    </tr>
                  @endforeach
                  <tr id="addObsiguardIP">
                    <td class="ui form">
                      <div class="field">
                        <input type="text" data-obsiguard-action="add" name="ip">
                      </div>
                    </td>
                    <td>
                      <button class="ui green button" data-obsiguard-action="add" name="button">@lang('user.obsiguard.list.action.add')</button>
                    </td>
                  </tr>
                </tbody>
              </table>

              <p>@lang('user.obsiguard.ip', ['ip' => request()->ip()])</p>

              <div class="ui info message">
                <div class="content">
                  <div class="header ui form">
                    <div class="ui checkbox">
                      <input type="checkbox" data-obsiguard-action="switchDynamicIP" tabindex="0" class="hidden" {{ $obsiguardDynamicIP ? 'checked' : '' }}>
                      <label style="color:#0E566C;">@lang('user.obsiguard.ip.dynamic.title')</label>
                    </div>
                  </div>
                  <p>@lang('user.obsiguard.ip.dynamic.subtitle')</p>
                </div>
              </div>
            </div>
          </div>

          <div data-menu="login-logs" style="display:none;">
            <h3 class="ui dividing header">
              @lang('user.profile.login.logs.website')
            </h3>

            <table class="ui striped table">
              <thead>
                <tr>
                  <th>@lang('user.profile.login.logs.ip')</th>
                  <th>@lang('user.profile.login.logs.date')</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($websiteLoginLogs as $log)
                  <tr>
                    <td>{{ $log->ip }}</td>
                    <td>{{ $log->created_at->diffForHumans() }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>

            <h3 class="ui dividing header">
              @lang('user.profile.login.logs.launcher')
            </h3>

            <table class="ui striped table">
              <thead>
                <tr>
                  <th>@lang('user.profile.login.logs.ip')</th>
                  <th>@lang('user.profile.login.logs.date')</th>
                </tr>
              </thead>
              <tbody>
                @foreach ($launcherLoginLogs as $log)
                  <tr>
                    <td>{{ $log->ip }}</td>
                    <td>{{ $log->created_at->diffForHumans() }}</td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div data-menu="spendings" style="display:none;">
            <h3 class="ui dividing header">
              @lang('user.profile.spendings.title')
            </h3>

            <table class="ui striped table">
              <tbody>
                @foreach ($spendings as $spending)
                  <tr>
                    <td>
                      @if ($spending['type'] === 'money')
                        <i class="exchange icon"></i> @lang('user.profile.spendings.transfer', ['amount' => $spending['amount'], 'username' => $spending['receiver']['username']])
                      @else
                        <i class="shopping basket icon"></i> @lang('user.profile.spendings.item', ['price' => $spending['item']['price'], 'item_name' => $spending['item']['name']])
                      @endif
                      &nbsp;&nbsp;<small><em style="color:#777;">{{ \Carbon\Carbon::parse($spending['created_at'])->diffForHumans() }}</em></small>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>

          <div data-menu="socials" style="display:none;">

            @ability('', 'user-link-google-account,user-youtube-view-own-videos,user-youtube-get-remuneration')
              <h3 class="ui dividing header">
                @lang('user.profile.socials.youtube')
              </h3>

              <div class="text-center">
                <a href="{{ (count($youtube) > 0) ? url('/user/socials/youtube/videos') : url('/user/socials/google/link') }}" class="ui youtube button">
                  <i class="youtube icon"></i>
                  @if (count($youtube) > 0)
                    @lang('user.profile.socials.youtube.videos.see')
                  @else
                    @lang('user.profile.socials.youtube.link')
                  @endif
                </a>
              </div>

              <div class="ui info message">
                <div class="header">
                  @lang('user.profile.socials.link.why')
                </div>
                <ul class="list">
                  <li>@lang('user.profile.socials.youtube.list.0')</li>
                  <li>@lang('user.profile.socials.youtube.list.1')</li>
                </ul>
              </div>
            @endability

            @permission('user-link-twitter-account')
              <h3 class="ui dividing header">
                @lang('user.profile.socials.twitter')
              </h3>

              <div class="text-center">
                @if (count($twitter) > 0)
                  <button class="ui twitter button">
                    <i class="twitter icon"></i>
                    {{ '@' . $twitter->screen_name }}
                  </button>
                @else
                  <a href="{{ url('/user/socials/twitter/link') }}" class="ui twitter button">
                    <i class="twitter icon"></i>
                    @lang('user.profile.socials.twitter.link')
                  </a>
                @endif
              </div>

              <div class="ui info message">
                <div class="header">
                  @lang('user.profile.socials.link.why')
                </div>
                <ul class="list">
                  <li>@lang('user.profile.socials.twitter.list.0')</li>
                  <li>@lang('user.profile.socials.twitter.list.1')</li>
                </ul>
              </div>
            @endpermission

          </div>
        </div>
      </div>
    </div>

  </div>

  @permission('user-request-edit-email')
    <div class="ui modal" id="editEmail">
      <i class="close icon"></i>
      <div class="header">
        @lang('user.profile.email.edit')
      </div>
      <div class="content">
        <form action="{{ url('/user/email') }}" method="post" data-ajax data-ajax-custom-callback="afterRequestedEditEmail">
        <div class="ui form">
          <h4 class="ui dividing header">@lang('user.profile.email.edit.subtitle')</h4>
          <div class="field">
            <label>@lang('user.field.email')</label>
            <input type="text" name="email">
          </div>
          <div class="field">
            <label>@lang('user.profile.email.edit.reason')</label>
            <textarea name="reason"></textarea>
          </div>
        </div>
      </div>
      <div class="actions">
        <button type="submit" class="ui green button">@lang('user.profile.email.edit.send')</button>
        </form>
      </div>
    </div>
  @endpermission
  @permission('user-edit-username')
    <div class="ui modal" id="editUsername">
      <i class="close icon"></i>
      <div class="header">
        @lang('user.profile.username.edit')
      </div>
      <div class="content">
        <div class="ui warning message">
          <i class="close icon"></i>
          <div class="header">
            @lang('global.warning')
          </div>
          @lang('user.profile.edit.username.warning')
        </div>
        <form action="{{ url('/user/username') }}" method="post" data-ajax data-ajax-custom-callback="afterRequestedEditUsername">
        <div class="ui form">
          <h4 class="ui dividing header">@lang('user.profile.edit.username.subtitle')</h4>
          <div class="field">
            <label>@lang('user.field.username')</label>
            <input type="text" name="username">
          </div>
          <div class="field">
            <label>@lang('user.field.password')</label>
            <input type="password" name="password">
          </div>
        </div>
      </div>
      <div class="actions">
        <button type="submit" class="ui green button">@lang('user.profile.edit.username.send')</button>
        </form>
      </div>
    </div>
  @endpermission
  @permission('user-transfer-money')
    <div class="ui modal" id="transferMoney">
      <i class="close icon"></i>
      <div class="header">
        @lang('user.profile.transfer.money')
      </div>
      <div class="content">
        <form action="{{ url('/user/money') }}" method="put" data-ajax data-ajax-custom-callback="afterRequestedTransferMoney">
        <div class="ui form">
          <h4 class="ui dividing header">@lang('user.profile.transfer.money.subtitle')</h4>
          <div class="field">
            <label>@lang('user.profile.transfer.money.field.amount')</label>
            <input type="number" name="amount">
          </div>
          <div class="field">
            <label>@lang('user.profile.transfer.money.field.to')</label>
            <input type="text" name="to">
          </div>
        </div>
      </div>
      <div class="actions">
        <button type="submit" class="ui green button">@lang('user.profile.transfer.money.send')</button>
        </form>
      </div>
    </div>
  @endpermission
  @ability('', 'user-disable-obsiguard,user-add-ip-obsiguard,user-remove-ip-obsiguard')
    <div class="ui modal" id="obsiguardSecurity">
      <i class="close icon"></i>
      <div class="header">
        @lang('user.obsiguard.security.title')
      </div>
      <div class="content">
        <form action="{{ url('/user/obsiguard/security/valid') }}" method="post" data-ajax data-ajax-custom-callback="afterValidObsiguardSecurity">
        <div class="ui form">
          <h4 class="ui dividing header">@lang('user.obsiguard.security.subtitle')</h4>
          <div class="field">
            <label>@lang('user.obsiguard.security.code')</label>
            <input type="text" name="code">
          </div>
        </div>
      </div>
      <div class="actions">
        <button type="submit" class="ui green button">@lang('user.obsiguard.security.valid')</button>
        </form>
      </div>
    </div>
  @endability
@endsection
@section('style')
  <style media="screen">
    .ui.vertical.menu .left.aligned.icon {
      float: left;
      margin: 0 .5em 0 0;
    }
    .statistic .value .icon {
      font-size: 30px;
      line-height: 10px;
      cursor: pointer;
      color: #000;
    }
  </style>
@endsection
@section('script')
  <script type="text/javascript">
    function afterRequestedEditEmail(req, res) {
      $('.ui.modal#editEmail form .ui.form').slideUp(150)
      $('.ui.modal#editEmail .actions').remove()
    }
    function afterRequestedEditUsername(req, res) {
      $('.ui.modal#editUsername').modal('hide')
      $('#username').val(req.username)
      $('#usernameBtn').remove()
      toastr.success(res.success)
    }
    function afterRequestedTransferMoney(req, res) {
      $('.ui.modal#transferMoney').modal('hide')
      $('#money').html(res.money)
      toastr.success(res.success)
    }

    $(document).ready(function () {
      $('.toggle-menu[data-toggle]').on('click', function () {
        var btn = $(this)
        var menuName = btn.attr('data-toggle')
        var menu = $('.menu-content [data-menu="' + menuName +'"]')

        $('.toggle-menu[data-toggle].active').removeClass('active')
        btn.addClass('active')
        $('.menu-content [data-menu]:visible').fadeOut(150)
        setTimeout(function () {
          menu.fadeIn(100)
        }, 100)
      })
    })
    $(document).ready(function() {
      $('input[type="file"]').on('change', function () {
        var input = $(this)
        var label = input.parent().find('label')
        var filePath = input.val()

        if (filePath) {
          var startIndex = (filePath.indexOf('\\') >= 0 ? filePath.lastIndexOf('\\') : filePath.lastIndexOf('/'))
          var filename = filePath.substring(startIndex)
          if (filename.indexOf('\\') === 0 || filename.indexOf('/') === 0)
            filename = filename.substring(1)
          label.find('span.filename').html(filename)
        }
      })
    })
  </script>
  <script type="text/javascript">
    $(document).ready(function () {
      $('button[data-obsiguard-action="add"]').on('click', function (e) {
        e.preventDefault()
        var btn = $(this)
        var tr = btn.parent().parent()
        var input = tr.find('input[data-obsiguard-action="add"]')

        btn.addClass('loading')
        window.btn = btn

        obsiguardAdd(input.val())
      })
      $('[data-obsiguard-action="disable"]').on('click', function (e) {
        e.preventDefault()
        var btn = $(this)
        var tr = btn.parent().parent()

        btn.addClass('loading')
        window.btn = btn

        obsiguardDisable()
      })
      $('[data-obsiguard-action="enable"]').on('click', function (e) {
        e.preventDefault()
        var btn = $(this)

        $.get('{{ url('/user/obsiguard/enable') }}', function (data) {
          $('#obsiguardDisabled').slideUp(150)
          $('#obsiguardEnabled').slideDown(150)
          $('tr[data-obsiguard-id]').remove()
          var html = '<tr data-obsiguard-id="' + data.data.id + '">'
            html += '<td>' + data.data.ip + '</td>'
            html += '<td>'
              html += '<button class="ui red button" data-obsiguard-action="remove" name="button">@lang('user.obsiguard.list.action.remove')</button>'
            html += '</td>'
          html += '</tr>'
          $(html).insertBefore($('#addObsiguardIP'))
          toastr.success(data.success)
          initObsiguardDeleteEvents()
        })
      })
      $('[data-obsiguard-action="switchDynamicIP"]').on('change', function () {
        var input = $(this)
        if (!input.prop('checked'))
          var url = '{{ url('/user/obsiguard/ip/dynamic/disable') }}'
        else
          var url = '{{ url('/user/obsiguard/ip/dynamic/enable') }}'
        $.get(url)
      })
      initObsiguardDeleteEvents()
    })
    function initObsiguardDeleteEvents() {
      $('[data-obsiguard-action="remove"]').unbind('click')
      $('[data-obsiguard-action="remove"]').on('click', function (e) {
        e.preventDefault()
        var btn = $(this)
        var tr = btn.parent().parent()
        var id = tr.attr('data-obsiguard-id')

        btn.addClass('loading')
        window.btn = btn

        obsiguardDelete(id)
      })
    }
    function obsiguardDisable() {
      $.get('{{ url('/user/obsiguard/disable') }}', function (data) {
        obsiguardSecurity('disable', undefined, data, function (data) {
          if (data.status) {
            $('#obsiguardEnabled').slideUp(150)
            $('#obsiguardDisabled').slideDown(150)
            toastr.success(data.success)
          } else {
            toastr.error(data.error)
          }
          window.btn.removeClass('loading')
        })
      })
    }
    function obsiguardAdd(ip) {
      $.post('{{ url('/user/obsiguard/ip') }}', {ip: ip}, function (data) {
        obsiguardSecurity('add', ip, data, function (data) {
          if (data.status) {
            var html = '<tr data-obsiguard-id="' + data.data.id + '">'
              html += '<td>' + data.data.ip + '</td>'
              html += '<td>'
                html += '<button class="ui red button" data-obsiguard-action="remove" name="button">@lang('user.obsiguard.list.action.remove')</button>'
              html += '</td>'
            html += '</tr>'
            $(html).insertBefore($('#addObsiguardIP'))
            toastr.success(data.success)
            initObsiguardDeleteEvents()
            $('input[data-obsiguard-action="add"]').val('')
          } else {
            toastr.error(data.error)
          }
          window.btn.removeClass('loading')
        })
      })
    }
    function obsiguardDelete(id) {
      $.ajax({
        url: '{{ url('/user/obsiguard/ip', ['id' => 'ID']) }}'.replace('ID', id),
        type: 'DELETE',
        success: function (data) {
          obsiguardSecurity('delete', id, data, function (data) {
            if (data.status) {
              $('tr[data-obsiguard-id="' + id + '"]').remove()
            } else {
              toastr.error(data.error)
            }
            window.btn.removeClass('loading')
          })
        }
      })
    }
    function obsiguardSecurity(action, data, res, next) {
      if (!res.status) return next(res)
      if (res.obsiguard === undefined || res.obsiguard === true) return next(res)
      // Modal
      $('#obsiguardSecurity').modal({blurring: true}).modal('show')
      window.obsiguard = {
        action: action,
        data: data
      }
    }
    function afterValidObsiguardSecurity(req, res) {
      $('#obsiguardSecurity').modal('hide')
      if (window.obsiguard.action === 'add')
        obsiguardAdd(window.obsiguard.data)
      else if (window.obsiguard.action === 'delete')
        obsiguardDelete(window.obsiguard.data)
      else if (window.obsiguard.action === 'disable')
        obsiguardDisable(window.obsiguard.data)
    }
  </script>
@endsection
