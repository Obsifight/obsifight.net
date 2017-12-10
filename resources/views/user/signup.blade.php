@extends('layouts.app')

@section('title', __('user.signup'))

@section('content')
  <div class="sub-header rotate">
    <div class="ui large header">@lang('user.signup.join_now')</div>
  </div>
  <div class="parallax-block" style="background-image: url('{{ url('/img/parallax-2.jpg') }}');">
    <div class="ui container page-content">

      <div class="ui grid">
        <div class="ten wide computer only column explain">
          <h2>Pourquoi s'inscrire sur ObsiFight ?</h2>

          <p>Nous avons choisi de créer un système d'inscription des joueurs, retenant leurs identifiants et autres données dans notre base de données. Cela nous permet d'ajouter de nombreuses fonctionnalités, en plus d'une protection optimale.</p>

          <ul class="list-unstyled login-features">
            <li>
              <i class="lock icon"></i> Nos bases de données sont parmi les plus sûres.
            </li>
            <li>
              <i class="shield icon"></i> ObsiGuard protège vos comptes des vols de mots de passe.
            </li>
            <li>
              <i class="crosshairs icon"></i> Une qualité de jeu parfaite pour des combats optimaux.
            </li>
            <li>
              <i class="question circle icon"></i> Des modérateurs à l'écoute pour répondre à vos questions.
            </li>
            <li>
              <i class="legal icon"></i> Un forum complet pour partager vos exploits.
            </li>
            <li>
              <i class="microphone icon"></i> Un TeamSpeak conçu pour accueillir vos channels de factions.
            </li>
            <li>
              <i class="key icon"></i> Et des Events vous débloquant des avantages hors normes !
            </li>
          </ul>
        </div>
        <div class="sixteen wide mobile sixteen wide tablet six wide computer column">
          <div class="ui raised padded segment">
            <h2 class="ui header">
              <i class="signup icon"></i>
              <div class="content">
                @lang('user.signup')
              </div>
            </h2>
            <div class="ui divider"></div>
            <form method="post" action="{{ url('/signup') }}" data-ajax class="ui form">
              <div class="field">
                <label>@lang('user.field.username')</label>
                <div class="ui left icon input">
                  <input type="text" name="username" placeholder="Eywek">
                  <i class="user icon"></i>
                </div>
              </div>
              <div class="field">
                <label>@lang('user.field.email')</label>
                <div class="ui left icon input">
                  <input type="email" name="email" placeholder="contact@obsifight.net">
                  <i class="mail icon"></i>
                </div>
              </div>
              <div class="field">
                <label>@lang('user.field.password')</label>
                <div class="ui left icon input">
                  <input type="password" name="password" placeholder="*********">
                  <i class="lock icon"></i>
                </div>
              </div>
              <div class="ui indicating progress password-strengh">
                <div class="bar"></div>
              </div>
              <div class="field">
                <label>@lang('user.field.password')</label>
                <div class="ui left icon input">
                  <input type="password" name="password_confirmation" placeholder="*********">
                  <i class="lock icon"></i>
                </div>
              </div>
              <div class="field">
                {!! ReCaptcha::render() !!}
              </div>
              <div class="field">
                <div class="ui checkbox">
                  <input type="checkbox" tabindex="0" name="legal" class="hidden">
                  <label>@lang('user.signup.field.legal', ['link' => 'http://forum.obsifight.net/link-forums/c-g-u-dobsifight.151/'])</label>
                </div>
              </div>
              <div class="text-center">
                <button type="submit" class="ui red submit button">@lang('user.signup')</button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </div>

  </div>
@endsection
@section('style')
  <style media="screen">
    .explain {
      font-weight: 300;
      font-size: 15px;
    }
    .explain ul {
      padding-left: 0;
      list-style: none;
    }
    .explain ul li {
      padding: 8px 0;
      font-size: 16px;
      font-weight: 300;
      line-height: 30px;
    }

    .progress.password-strengh {
      margin-bottom: 10px;
    }
  </style>
@endsection
@section('script')
  <script type="text/javascript">
    $(document).ready(function () {
      $('.password-strengh').progress({percent: 0})

      $('input[name="password"]').on('keyup', function () { // Test strengh
        var value = $(this).val()
        // Must have capital letter, numbers and lowercase letters
        var strongRegex = new RegExp("^(?=.{8,})(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*\\W).*$", "g")
        // Must have either capitals and lowercase letters or lowercase and numbers
        var mediumRegex = new RegExp("^(?=.{7,})(((?=.*[A-Z])(?=.*[a-z]))|((?=.*[A-Z])(?=.*[0-9]))|((?=.*[a-z])(?=.*[0-9]))).*$", "g")
        // Must be at least 6 characters long
        var okRegex = new RegExp("(?=.{6,}).*", "g")

        if (strongRegex.test(value))
          $('.password-strengh').progress({percent: 100})
        else if(mediumRegex.test(value))
          $('.password-strengh').progress({percent: 60})
        else if(okRegex.test(value))
          $('.password-strengh').progress({percent: 30})
        else
          $('.password-strengh').progress({percent: 10})
      })
    })
  </script>
@endsection
