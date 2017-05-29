@extends('layouts.app')

@section('title', 'Eywek')

@section('content')
  <div class="ui container page-content">
    <h1 class="ui center aligned header">
      <img src="https://skins.obsifight.net/head/Eywek/64" class="ui rounded staff image" alt="Eywek">
      <div class="content">
        <a href="{{ url('/stats/faction/Destiny') }}" class="ui blue image medium label">
          Destiny
          <div class="detail">Chef</div>
        </a>
        Eywek
        <div class="sub header" style="margin-top:5px;"><i class="france flag"></i> Inscrit il y a 3 ans</div>
      </div>
    </h1>
    <div class="ui divider"></div>

    <div class="ui stackable grid" style="position:relative;">

      <div class="ui eight wide column">
        <h2 class="ui header">
          Ses infos
          <div class="sub header">Informations sur la saison en cours</div>
        </h2><br>

        <div class="ui four small statistics">
          <div class="statistic">
            <div class="value">
              100
            </div>
            <div class="label">
              Heures de jeu
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              300
            </div>
            <div class="label">
              Combats
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
        <div class="ui two small statistics">
          <div class="statistic">
            <div class="value">
              10.203
            </div>
            <div class="label">
              Blocs posés
            </div>
          </div>
          <div class="statistic">
            <div class="value">
              18.103
            </div>
            <div class="label">
              Blocs cassés
            </div>
          </div>
        </div>

        <div class="ui divider"></div>

        <span class="ui red label">
          <i class="remove icon"></i>
          Connecté pour la V4
        </span>
        <span class="ui green label">
          <i class="check icon"></i>
          Connecté pour la V5
        </span>
        <span class="ui green label">
          <i class="check icon"></i>
          Connecté pour la V6
        </span>
        <span class="ui green label">
          <i class="check icon"></i>
          Connecté pour la V7
        </span>

        <div class="ui divider"></div>

        <span class="ui grey disabled label">
          <i class="remove icon"></i>
          Possède une cape
        </span>
        <span class="ui blue label">
          <i class="check icon"></i>
          Possède un skin
        </span>

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<em>Dernière connexion il y a 3 heures</em>

      </div>
      <div class="ui vertical divider"></div>
      <div class="ui eight wide column">
        <h2 class="ui header">
          Ses succès
          <div class="sub header">Débloqués au cours de la saison {{ env('APP_VERSION_COUNT') }}</div>
        </h2>

        <span class="ui achievement green label">
          <i class="check icon"></i>
          Marcher 500 blocs
        </span>
        <span class="ui achievement active label">
          <i class="check icon"></i>
          Marcher 1500 blocs
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
          Lier son compte YouTube
        </span>
        <span class="ui achievement grey disabled label">
          <i class="remove icon"></i>
          Lier son compte Twitter
        </span>
      </div>

    </div>
  </div>
@endsection
@section('style')
  <style media="screen">
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
