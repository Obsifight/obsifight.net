@extends('layouts.app')

@section('title', __('stats.factions.ranking'))

@section('content')
    <div class="ui container page-content">

        <table class="ui celled table">
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
    <link rel="stylesheet" href="https://cdn.datatables.net/1.10.16/css/dataTables.semanticui.min.css">
@endsection
@section('script')
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/jquery.dataTables.min.js"></script>
    <script type="text/javascript" src="https://cdn.datatables.net/1.10.16/js/dataTables.semanticui.min.js"></script>
    <script type="text/javascript">
        $('table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ env('DATA_SERVER_ENDPOINT') }}/factions',
            "columns": [
                { "data": "position", "name": "position" },
                { "data": "name", "name": "name" },
                { "data": "claims_count", "name": "claims_count" },
                { "data": "kills_count", "name": "kills_count" },
                { "data": "deaths_count", "name": "deaths_count" },
                { "data": "score", "name": "score" }
            ]
        });
    </script>
@endsection