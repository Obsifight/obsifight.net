@extends('layouts.app')

@section('title', __('stats.factions.ranking'))

@section('content')
    <div class="ui container page-content">

        <div class="text-center head-top">
            <h1 class="ui header">
                <img src="{{ url('/img/logo-min.png') }}" class="ui circular image">
                <div class="content">
                    Classement factions
                    <div class="sub header">Battez-vous pour devenir le meilleur !</div>
                </div>
            </h1>
        </div>

        <div class="ui divider"></div>

        <button class="ui button yellow" onclick="$('#details').modal('show')">
            Voir le détails des points
        </button>

        <table class="ui basic padded table" id="ranking">
            <thead>
            <tr>
                <th>Positon</th>
                <th>Nom</th>
                <th>Territoires</th>
                <th>Tués</th>
                <th>Morts</th>
                <th>Score</th>
            </tr>
            </thead>
        </table>

    </div>

    <div class="ui modal" id="details">
        <div class="header">Détails des points</div>
        <div class="content">

            <table class="ui celled table">
                <thead>
                    <tr>
                        <th>Tués</th>
                        <th>Morts</th>
                        <th>Argent</th>
                        <th>Territoires / Joueurs / Events</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>En dessous de <b>250</b> :<br><span style="color:#016936">+0.5 points</span> / Tués</td>
                        <td>En dessous de <b>250</b> :<br><span style="color:#B03060">-0.8 points</span> / Morts</td>
                        <td>En dessous de <b>25 000</b> :<br><span style="color:#016936">+0.001 points</span> / Dollars</td>
                        <td><b>Territoires :</b><br><span style="color:#016936">+1 points</span> / Claims</td>
                    </tr>
                    <tr>
                        <td>De <b>250</b> à <b>500</b> :<br><span style="color:#016936">+0.8 points</span> / Tués</td>
                        <td>De <b>250</b> à <b>500</b> :<br><span style="color:#B03060">-1 points</span> / Morts</td>
                        <td>De <b>25 000</b> à <b>50 000</b> :<br><span style="color:#016936">+0.002 points</span> / Dollars</td>
                        <td><b>Avant-Postes :</b><br><span style="color:#016936">+350 points</span> / Avant-Poste</td>
                    </tr>
                    <tr>
                        <td>De <b>500</b> à <b>1 000</b> :<br><span style="color:#016936">+1 points</span> / Tués</td>
                        <td>De <b>500</b> à <b>1 000</b> :<br><span style="color:#B03060">-1.2 points</span> / Morts</td>
                        <td>De <b>50 000</b> à <b>100 000</b> :<br><span style="color:#016936">+0.004 points</span> / Dollars</td>
                        <td><b>Joueurs :</b><br><span style="color:#016936">+5 points</span> / Joueur</td>
                    </tr>
                    <tr>
                        <td>Au dessus de <b>1 000</b> :<br><span style="color:#016936">+1.2 points</span> / Tués</td>
                        <td>Au dessus de <b>1 000</b> :<br><span style="color:#B03060">-1.5 points</span> / Morts</td>
                        <td>De <b>100 000</b> à <b>250 000</b> :<br><span style="color:#016936">+0.008 points</span> / Dollars</td>
                        <td><b>Koth :</b><br><span style="color:#016936">+250 points</span> / Victoire</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td>Au dessus de <b>250 000</b> :<br><span style="color:#016936">+0.016 points</span>/Dollars</td>
                        <td></td>
                    </tr>
                </tbody>
            </table>

            <div class="ui divider"></div>

            <h3>Ressources</h3>

            <div class="ui two column grid">
                <div class="column">
                    <table class="ui celled table">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Valeur</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Lingot de Garnet</td>
                            <td><span style="color:#016936">+0.00015 points</span> / Lingot</td>
                        </tr>
                        <tr>
                            <td>Bloc de Garnet</td>
                            <td><span style="color:#016936">+0.00135 points</span> / Bloc</td>
                        </tr>
                        <tr>
                            <td>Lingot de Améthyste</td>
                            <td><span style="color:#016936">+0.002 points</span> / Lingot</td>
                        </tr>
                        <tr>
                            <td>Bloc de Améthyste</td>
                            <td><span style="color:#016936">+0.018 points</span> / Bloc</td>
                        </tr>
                        <tr>
                            <td>Lingot de Titanium</td>
                            <td><span style="color:#016936">+0.003 points</span> / Lingot</td>
                        </tr>
                        <tr>
                            <td>Bloc de Titanium</td>
                            <td><span style="color:#016936">+0.027 points</span> / Bloc</td>
                        </tr>
                        <tr>
                            <td>Lingot de Obsidienne</td>
                            <td><span style="color:#016936">+0.003 points</span> / Lingot</td>
                        </tr>
                        <tr>
                            <td>Bloc de Obsidienne</td>
                            <td><span style="color:#016936">+0.027 points</span> / Bloc</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="column">
                    <table class="ui celled table">
                        <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Valeur</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Lingot de Xénotium</td>
                            <td><span style="color:#016936">+3 points</span> / Lingot</td>
                        </tr>
                        <tr>
                            <td>Bloc de Xénotium</td>
                            <td><span style="color:#016936">+27 points</span> / Bloc</td>
                        </tr>
                        <tr>
                            <td>TNT</td>
                            <td><span style="color:#016936">+0.01 points</span> / TNT</td>
                        </tr>
                        <tr>
                            <td>TNT au Xénotium</td>
                            <td><span style="color:#016936">+9 points</span> / TNT</td>
                        </tr>
                        <tr>
                            <td>Enderpearl</td>
                            <td><span style="color:#016936">+0.005 points</span> / Enderpearl</td>
                        </tr>
                        <tr>
                            <td>Pomme en Or</td>
                            <td><span style="color:#016936">+0.1 points</span> / Pomme</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>

        </div>
    </div>
@endsection
@section('style')
    <link rel="stylesheet" href="{{ url('/css/dataTables.semanticui.min.css') }}">
    <style>
        .table a {
            color: #0f0f10;
        }

        .table a:hover {
            color: #0f0f10;
            text-decoration: underline;
        }

        .head-top {
            margin-top: -6%;
            z-index: 10;
            margin-left: -20px;
            position: relative;
        }
    </style>
@endsection
@section('script')
    <script type="text/javascript" src="{{ url('/js/jquery.dataTables.min.js') }}"></script>
    <script type="text/javascript" src="{{ url('/js/dataTables.semanticui.min.js') }}"></script>
    <script type="text/javascript">
        var round = function (nb) {
            return Math.round(nb * 100) / 100;
        };

        $('#ranking').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ env('DATA_SERVER_ENDPOINT') }}/factions',
            'pageLength': 25,
            'lengthChange': false,
            'columns': [
                {"data": "position", "name": "position"},
                {"data": "name", "name": "name"},
                {"data": "claims_count", "name": "claims_count"},
                {"data": "kills_count", "name": "kills_count"},
                {"data": "deaths_count", "name": "deaths_count"},
                {"data": "score", "name": "score"}
            ],
            "columnDefs": [
                {
                    "render": function (data, type, row) {
                        if (parseInt(data) === 1)
                            return ' <i class="icon trophy"></i>' + data;
                        else
                            return '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + data;
                    },
                    "targets": 0
                },
                {
                    "render": function (data, type, row) {
                        return '<b><a href="{{ url('/stats/faction') }}/' + data + '">' + data + '</a></b>';
                    },
                    "targets": 1
                },
                {
                    "render": function (data, type, row) {
                        var details = JSON.parse(row.details);
                        var detailsString = '';
                        detailsString += '<b>Tués:</b> <span style=\'color: #016936\'>+' + round(details.kills) + ' points</span>';
                        detailsString += '<br><b>Morts:</b> <span style=\'color: #B03060\'>' + round(details.deaths) + ' points</span>';
                        detailsString += '<br><b>Argent:</b> <span style=\'color: #016936\'>+' + round(details.money) + ' points</span>';
                        detailsString += '<br><b>Territoires:</b> <span style=\'color: #016936\'>+' + round(details.claims) + ' points</span>';
                        detailsString += '<br><b>Avant-Postes:</b> <span style=\'color: #016936\'>+' + round(details.outpost) + ' points</span>';
                        detailsString += '<br><b>Joueurs:</b> <span style=\'color: #016936\'>+' + round(details.max_power) + ' points</span>';
                        detailsString += '<br><b>Ressources:</b> <span style=\'color: #016936\'>+' + round(details.materials) + ' points</span>';

                        return data + '<div style="float: right" class="ui yellow button" data-placement="right center" data-toggle="popup"\n' +
                            '        data-content="' + detailsString + '">\n' +
                            '        Détails\n' +
                            '</div>';
                    },
                    "targets": 5
                }
            ],
            'createdRow': function (row, data, dataIndex) {
                if (data.position === 1)
                    $(row).css('background-color', 'rgba(255, 215, 0, 0.3)');
                else if (data.position === 2)
                    $(row).css('background-color', 'rgba(192, 192, 192, 0.3)');
                else if (data.position === 3)
                    $(row).css('background-color', 'rgba(205, 127, 50, 0.3)');
            },
            'drawCallback': function (settings, json) {
                $('[data-toggle="popup"]').each(function (k, el) {
                    $(el).popup({
                        html: $(el).attr('data-content'),
                        position: $(el).attr('data-placement')
                    })
                })
            },
            'language': datatableLang
        });
    </script>
@endsection