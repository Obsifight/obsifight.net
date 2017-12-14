@extends('admin.layouts.app')

@section('title', __('admin.nav.users.transfer.history'))

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">@lang('admin.nav.users.transfer.history')</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">@lang('dashboard.title')</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">@lang('global.users')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('admin.nav.users.transfer.history')
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Zero configuration table -->
        <section id="configuration">
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.users.transfer.history')</h4>
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
                                        <th>@lang('admin.users.transfer.history.username')</th>
                                        <th>@lang('admin.users.transfer.history.amount')</th>
                                        <th>@lang('admin.users.transfer.history.receiver')</th>
                                        <th>@lang('admin.users.transfer.history.date')</th>
                                        <th>@lang('admin.users.transfer.history.ip')</th>
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
        var table = $('table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ url('/admin/users/transfers/data') }}',
            'pageLength': 25,
            'lengthChange': false,
            'columns': [
                {"data": "user.username"},
                {"data": "amount"},
                {"data": "receiver.username"},
                {"data": "created_at"},
                {"data": "ip"}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('/admin/users/edit') }}/' + data + '">' + data + '</a>';
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return data + ' points';
                    },
                    "targets": 1
                },
                {
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('/admin/users/edit') }}/' + data + '">' + data + '</a>';
                    },
                    "targets": 2
                }
            ],
            'language': datatableLang
        });
        $('[data-action="reload"]').on('click', function () {
            table.ajax.reload();
        });
    </script>
@endsection