@extends('layouts.app')

@section('title', 'Destiny')

@section('content')
  <div class="ui container page-content">
    <h1 class="ui center aligned header">
      <!--<img src="https://skins.obsifight.net/head/Eywek/64" class="ui rounded staff image" alt="Eywek">-->
      <div class="content">
        <a href="{{ url('/stats/Eywek') }}" class="ui blue image medium label">
          Eywek
          <div class="detail">Chef</div>
        </a>
        Destiny
        <div class="sub header" style="margin-top:5px;">Créée il y a 3 ans</div>
      </div>
    </h1>
    <div class="ui divider"></div>

    <div class="ui stackable grid" style="position:relative;">

      <div class="ui eight wide column">
        <h2 class="ui header">
          Ses infos
          <div class="sub header">Informations sur la saison en cours</div>
        </h2><br>

        <div class="ui two small statistics">
          <div class="statistic">
            <div class="value">
              <i class="list icon" style="color:#ffd700"></i> 1
            </div>
            <div class="label">
              Position au classement
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              <i class="trophy icon" style="color:#ffd700"></i> 175
            </div>
            <div class="label">
              Score
            </div>
          </div>
        </div>
        <div class="ui four small statistics">
          <div class="statistic">
            <div class="value">
              6
            </div>
            <div class="label">
              Joueurs
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              20
            </div>
            <div class="label">
              Claims
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              240
            </div>
            <div class="label">
              Tués
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              120
            </div>
            <div class="label">
              Morts
            </div>
          </div>
        </div>
        <br>
        <div class="ui indicating progress" id="power">
          <div class="bar">
            <div class="progress"></div>
          </div>
          <div class="label">Power</div>
        </div>
      </div>
      <div class="ui vertical divider"></div>
      <div class="ui eight wide column">
        <h2 class="ui header">
          Ses membres
          <div class="sub header">Triés par grade</div>
        </h2><br>

        @foreach (["Eywek","_Clem01_","Droweurss","Gallix2","Fairyme","KogMaw"] as $username)
          <a href="{{ url('/stats/' . $username) }}">
            <img src="https://skins.obsifight.net/head/{{ $username }}/64" class="ui rounded member image" alt="{{ $username }}" data-toggle="popup" data-variation="inverted" data-placement="top center" data-content="{{ $username }}">
          </a>
        @endforeach
      </div>

    </div>
    <div class="ui divider"></div>
    <div class="ui stackable grid" style="position:relative;">
      <div class="ui eight wide column">
        <h2 class="ui header">
          Ses succès
          <div class="sub header">Débloqués au cours de la saison {{ env('APP_VERSION_COUNT') }}</div>
        </h2><br>

        <span class="ui achievement green label">
          <i class="check icon"></i>
          Avoir plus de 100$
        </span>
        <span class="ui achievement active label">
          <i class="wait icon"></i>
          Avoir plus de 10.000$
        </span>

        <div class="ui divider"></div>

        <span class="ui achievement green label">
          <i class="check icon"></i>
          Tuer 10 enemis
        </span>
        <span class="ui achievement green label">
          <i class="check icon"></i>
          Tuer 50 enemis
        </span>
        <span class="ui achievement green label">
          <i class="check icon"></i>
          Tuer 100 enemis
        </span>
        <span class="ui achievement green label">
          <i class="check icon"></i>
          Tuer 500 enemis
        </span>
        <span class="ui achievement active label">
          <i class="wait icon"></i>
          Tuer 1.000 enemis
        </span>

        <div class="ui divider"></div>

        <span class="ui achievement green label">
          <i class="check icon"></i>
          Placer son crystal
        </span>
        <span class="ui achievement grey disabled label">
          <i class="remove icon"></i>
          Augmenter son crystal
        </span>
      </div>
      <div class="ui vertical divider"></div>
      <div class="ui eight wide column">
        <h2 class="ui header">
          Son crystal
          <div class="sub header">Le cœur de la faction</div>
        </h2><br>

        <div id="crystal">
          <h3><span>Crystal</span> - Destiny</h3>
          <h4>
            <i class="long arrow up icon"></i>
            Régénération
            <i class="long arrow up icon"></i>
          </h4>
          <p>
            <span class="min">137.353</span>
            /
            <span class="max">200</span>
            <span class="regen">2.75 <i class="caret up icon"></i></span>
          </p>
          <img src="{{ url('/img/crystal.gif') }}" alt="">
        </div>
      </div>
    </div>
  </div>
@endsection
@section('style')
  <style media="screen">
    #crystal {
      text-align: center;
    }
    #crystal h3 {
      color: #bbbbbb;
    }
    #crystal h3 span {
      color: #41d7d7;
      text-transform: uppercase;
    }
    #crystal h4 {
      color: #40fe52;
      margin-bottom: 2px;
      margin-top: 8px;
    }
    #crystal p {
      color: #d1a633;
    }
    #crystal p .min {
      color: #bd06ba;
    }
    #crystal p .regen {
      color: #00be01;
      margin-left: 15px;
    }

    img.member.image {
      background-color: #bdc3c7;
      border: 2px solid #c0392b;
      margin-right: 5px;
      margin-bottom: 5px;
      display: inline-block;
    }
    .ui.grid>.column+.divider, .ui.grid>.row>.column+.divider {
      left: 50%;
    }
    .ui.vertical.divider:after, .ui.vertical.divider:before {
      height: 100%;
    }

    .label {
      margin-top: 5px!important;
    }
    .achievement.active {
      color: #fff!important;
    }
    .achievement.active.label {
      position: relative;
      background: transparent!important;
    }
    .achievement.active.label:before {
      background: #767676;
      content: '';
      z-index: -2;
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: 0;
      border-radius: .28571429rem;
    }
    .achievement.active.label:after {
      background: #2185D0;
      content: '';
      z-index: -1;
      position: absolute;
      top: 0;
      bottom: 0;
      left: 0;
      right: calc(100% - 80%);
      border-radius: .28571429rem;
    }
  </style>
@endsection
@section('script')
  <script type="text/javascript">
    $('#power').progress({
      total: '60',
      value: '57',
      text: {
        percent: '{value}/{total}'
      }
    })
    $(document).ready(function () {
      $('[data-toggle="popup"]').each(function (k, el) {
        $(el).popup({
          html: $(el).attr('data-content'),
          position: $(el).attr('data-placement'),
          variation: $(el).attr('data-variation')
        })
      })
    })
  </script>
@endsection
