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
                    <th>Claims</th>
                    <th>Kills</th>
                    <th>Deaths</th>
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
            'language': datatableLang
        });
    </script>
@endsection