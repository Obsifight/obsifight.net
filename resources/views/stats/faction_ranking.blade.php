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

        <table class="ui basic padded table">
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

        $('table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ env('DATA_SERVER_ENDPOINT') }}/factions',
            'pageLength': 25,
            'lengthChange': false,
            'columns': [
                { "data": "position", "name": "position" },
                { "data": "name", "name": "name" },
                { "data": "claims_count", "name": "claims_count" },
                { "data": "kills_count", "name": "kills_count" },
                { "data": "deaths_count", "name": "deaths_count" },
                { "data": "score", "name": "score" }
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        if (parseInt(data) === 1)
                            return ' <i class="icon trophy"></i>' + data;
                        else
                            return '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;' + data;
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return '<b><a href="{{ url('/stats/faction') }}/' + data + '">' + data + '</a></b>';
                    },
                    "targets": 1
                },
                {
                    "render": function ( data, type, row ) {
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
            'createdRow': function( row, data, dataIndex) {
                if (data.position === 1)
                    $(row).css('background-color', 'rgba(255, 215, 0, 0.3)');
                else if (data.position === 2)
                    $(row).css('background-color', 'rgba(192, 192, 192, 0.3)');
                else if (data.position === 3)
                    $(row).css('background-color', 'rgba(205, 127, 50, 0.3)');
            },
            'drawCallback': function(settings, json) {
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