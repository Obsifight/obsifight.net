@extends('layouts.app')

@section('title', __('stats.title'))

@section('content')
  <div class="ui container page-content">

    <div class="ui stackable grid">
      <div class="ui four wide column">
        <div class="ui search">
          <div class="ui left icon input" style="width: 100%;">
            <input class="prompt" type="text" placeholder="Chercher un joueur">
            <i class="user icon"></i>
          </div>
        </div>

        <h3 class="ui center aligned icon header">
          <i class="circular users icon"></i>
        </h3>

        <h2 class="ui dividing red staff header">
          Administrateurs
        </h2>
        @foreach (['Suertzz', 'HiZe_', 'roumi1996', 'Fenixx57'] as $username)
          <a href="{{ url('/stats/' . $username) }}">
            <img src="https://skins.obsifight.net/head/{{ $username }}/64" class="ui rounded staff image" alt="{{ $username }}" data-toggle="popup" data-variation="inverted" data-placement="top center" data-content="{{ $username }}">
          </a>
        @endforeach

        <h2 class="ui dividing red staff header">
          Développeurs
        </h2>
        @foreach (['Eywek', 'CharpenteDocile', 'ThisIsMac'] as $username)
          <a href="{{ url('/stats/' . $username) }}">
            <img src="https://skins.obsifight.net/head/{{ $username }}/64" class="ui rounded staff image" alt="{{ $username }}" data-toggle="popup" data-variation="inverted" data-placement="top center" data-content="{{ $username }}">
          </a>
        @endforeach

        <h2 class="ui dividing green staff header">
          Modérateurs
        </h2>
        @foreach (["CronosS","Ludoo","yelrambec","SkyThenak","Newon_","lummix","ANEMIC"] as $username)
          <a href="{{ url('/stats/' . $username) }}">
            <img src="https://skins.obsifight.net/head/{{ $username }}/64" class="ui rounded staff image" alt="{{ $username }}" data-toggle="popup" data-variation="inverted" data-placement="top center" data-content="{{ $username }}">
          </a>
        @endforeach

        <h2 class="ui dividing olive staff header">
          Supports
        </h2>
        @foreach (["pTanguy"] as $username)
          <a href="{{ url('/stats/' . $username) }}">
            <img src="https://skins.obsifight.net/head/{{ $username }}/64" class="ui rounded staff image" alt="{{ $username }}" data-toggle="popup" data-variation="inverted" data-placement="top center" data-content="{{ $username }}">
          </a>
        @endforeach

        <h2 class="ui dividing yellow staff header">
          Animateurs
        </h2>
        @foreach (["_Clem01_","Droweurss","Gallix2","Fairyme","KogMaw"] as $username)
          <a href="{{ url('/stats/' . $username) }}">
            <img src="https://skins.obsifight.net/head/{{ $username }}/64" class="ui rounded staff image" alt="{{ $username }}" data-toggle="popup" data-variation="inverted" data-placement="top center" data-content="{{ $username }}">
          </a>
        @endforeach
      </div>

      <div class="ui twelve wide column">

        <div class="ui three large statistics">
          <div class="statistic">
            <div class="value">
              530
            </div>
            <div class="label">
              Record de joueurs
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              350
            </div>
            <div class="label">
              Joueurs en ligne
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              77.012
            </div>
            <div class="label">
              Joueurs inscrits
            </div>
          </div>
        </div>
        <div class="ui divider"></div>
        <div class="ui four small statistics">
          <div class="statistic">
            <div class="value">
              3.700
            </div>
            <div class="label">
              Joueurs uniques
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              2.200
            </div>
            <div class="label">
              Factions créées
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              160.000
            </div>
            <div class="label">
              Combats
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              980.000
            </div>
            <div class="label">
              Visites du site
            </div>
          </div>
        </div>

        <div class="ui divider"></div>

        <h1 class="ui header">
          <i class="child icon"></i>
          <div class="content">
            Statistiques des joueurs
            <div class="sub header">Calculées sur les 7 derniers jours</div>
          </div>
        </h1><br>

        <div class="ui icon info message">
          <i class="notched circle loading icon"></i>
          <div class="content">
            <div class="header">
              Juste une seconde
            </div>
            <p>Nous affichons le graphique pour vous.</p>
          </div>
        </div>

        <h3 class="ui dividing header">
          Les heures avec le plus de connectés
        </h3>

        <div class="ui icon info message">
          <i class="notched circle loading icon"></i>
          <div class="content">
            <div class="header">
              Juste une seconde
            </div>
            <p>Nous affichons les graphiques pour vous.</p>
          </div>
        </div>

        <div class="ui divider"></div>

        <h1 class="ui header">
          <i class="hand pointer icon"></i>
          <div class="content">
            Statistiques des visites
            <div class="sub header">Calculées sur les 7 derniers jours</div>
          </div>
        </h1><br>

        <div class="ui icon info message">
          <i class="notched circle loading icon"></i>
          <div class="content">
            <div class="header">
              Juste une seconde
            </div>
            <p>Nous affichons le graphique pour vous.</p>
          </div>
        </div>

        <div class="ui divider"></div>

        <h1 class="ui header">
          <i class="signup icon"></i>
          <div class="content">
            Statistiques des inscriptions
            <div class="sub header">Calculées sur les 7 derniers jours</div>
          </div>
        </h1><br>

        <div class="ui icon info message">
          <i class="notched circle loading icon"></i>
          <div class="content">
            <div class="header">
              Juste une seconde
            </div>
            <p>Nous affichons le graphique pour vous.</p>
          </div>
        </div>

      </div>
    </div>

  </div>
@endsection
@section('style')
  <style media="screen">
    img.staff.image {
      background-color: #bdc3c7;
      border: 2px solid #c0392b;
      margin-right: 5px;
      margin-bottom: 5px;
      display: inline-block;
    }
    h2.ui.dividing.red.staff.header,
    h2.ui.dividing.green.staff.header,
    h2.ui.dividing.olive.staff.header,
    h2.ui.dividing.yellow.staff.header {
      color: #4a4a4a!important;
    }

    .page-content {
      padding-top: 30px;
    }

    .ui.search .results .result .content {
      margin-top: 2px!important;
    }
    .ui.search .results .result .image {
      float: left;
      width: 20px;
      height: 20px;
      margin-right: 10px;
    }
  </style>
@endsection
@section('script')
  <script type="text/javascript">
    $(document).ready(function () {
      $('[data-toggle="popup"]').each(function (k, el) {
        $(el).popup({
          html: $(el).attr('data-content'),
          position: $(el).attr('data-placement'),
          variation: $(el).attr('data-variation')
        })
      })
    })
    $('.ui.search').search({
      apiSettings: {
        url: '{{ url('/stats/users/search') }}?q={query}'
      },
      fields: {
        results: 'users',
        title: 'username',
        url: 'url',
        image: 'img'
      },
      minCharacters : 3
    })
  </script>
@endsection
