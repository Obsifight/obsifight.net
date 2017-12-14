@extends('admin.layouts.app')

@section('title', __('admin.users.find'))

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">@lang('global.users')</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">@lang('dashboard.title')</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">@lang('global.users')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('admin.users.find')
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body"><!-- Stats -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-primary bg-darken-2">
                                <i class="icon-user font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-primary white media-body">
                                <h5>@lang('admin.users.stats.count')</h5>
                                <h5 class="text-bold-400 mb-0">
                                    <span id="users_count">&nbsp;&nbsp;<div class="fa fa-refresh fa-spin"></div>&nbsp;&nbsp;</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-danger bg-darken-2">
                                <i class="icon-user font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-danger white media-body">
                                <h5>@lang('admin.users.stats.count.version')</h5>
                                <h5 class="text-bold-400 mb-0">
                                    <span id="users_count_this_version">&nbsp;&nbsp;<div class="fa fa-refresh fa-spin"></div>&nbsp;&nbsp;</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-warning bg-darken-2">
                                <i class="icon-user font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-warning white media-body">
                                <h5>@lang('admin.users.stats.count.online')</h5>
                                <h5 class="text-bold-400 mb-0">
                                    <span id="server_count">&nbsp;&nbsp;<div class="fa fa-refresh fa-spin"></div>&nbsp;&nbsp;</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-success bg-darken-2">
                                <i class="icon-user font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-success white media-body">
                                <h5>@lang('admin.users.stats.count.online.max')</h5>
                                <h5 class="text-bold-400 mb-0">
                                    <span id="server_max">&nbsp;&nbsp;<div class="fa fa-refresh fa-spin"></div>&nbsp;&nbsp;</span>
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body"><!-- Zero configuration table -->
        <section id="configuration">
            <div class="row">
                <div class="col-12">
                    <div class="card" id="find">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.users.find')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <table class="table table-striped table-bordered zero-configuration">
                                    <thead>
                                    <tr>
                                        <th>@lang('user.field.username')</th>
                                        <th>@lang('user.field.email')</th>
                                        <th>@lang('user.profile.login.logs.ip')</th>
                                        <th>@lang('global.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <div class="content-body"><!-- Zero configuration table -->
        <section id="configuration">
            <div class="row">
                <div class="col-12">
                    <div class="card" id="history">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.users.history.usernames')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="reload"><i class="ft-rotate-cw"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <table class="table table-striped table-bordered zero-configuration">
                                    <thead>
                                    <tr>
                                        <th>@lang('user.field.username')</th>
                                        <th>@lang('admin.users.history.usernames.old')</th>
                                        <th>@lang('admin.users.history.usernames.new')</th>
                                        <th>@lang('user.profile.login.logs.ip')</th>
                                        <th>@lang('global.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>

@endsection
@section('style')
    <link rel="stylesheet" type="text/css" href="{{ url('/admin-assets/vendors/css/tables/datatable/datatables.min.css') }}">
    <style>
        table {
            width: 100%!important;
        }
    </style>
@endsection
@section('script')
    <script src="{{ url('/admin-assets/vendors/js/tables/datatable/datatables.min.js') }}" type="text/javascript"></script>
    <script>
        var findTable = $('#find table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ url('/admin/users/find') }}',
            'pageLength': 25,
            'lengthChange': false,
            'columns': [
                {"data": "username"},
                {"data": "email"},
                {"data": "ip"},
                {"data": "created_at"}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('/admin/users/edit') }}/' + data + '">' + data + '</a>';
                    },
                    "targets": 0
                }
            ],
            'language': datatableLang
        });
        $('#find [data-action="reload"]').on('click', function () {
            findTable.ajax.reload();
        });
    </script>
    <script>
        var historyTable = $('#history table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ url('/admin/users/username/history') }}',
            'pageLength': 25,
            'lengthChange': false,
            'columns': [
                {"data": "user.username"},
                {"data": "old_username"},
                {"data": "new_username"},
                {"data": "ip"},
                {"data": "created_at"}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('/admin/users/edit') }}/' + data + '">' + data + '</a>';
                    },
                    "targets": 0
                }
            ],
            'language': datatableLang
        });
        $('#history [data-action="reload"]').on('click', function () {
            historyTable.ajax.reload();
        });
    </script>
    <script type="text/javascript">
        $.get('{{ url('/stats/users/count/version') }}', function (data) {
            if (data.status)
                $('#users_count_this_version').html(nFormatter(data.count, 1))
        })
        $.get('{{ url('/stats/users/count') }}', function (data) {
            if (data.status)
                $('#users_count').html(nFormatter(data.count, 1))
        })
        $.get('{{ url('/stats/server/count') }}', function (data) {
            if (data.status)
                $('#server_count').html(nFormatter(data.count, 1))
        })
        $.get('{{ url('/stats/server/max') }}', function (data) {
            if (data.status)
                $('#server_max').html(nFormatter(data.count, 1))
        })
    </script>
@endsection