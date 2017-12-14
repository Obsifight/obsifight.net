@extends('admin.layouts.app')

@section('title', __('admin.shop.history'))

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">@lang('admin.shop.history')</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">@lang('dashboard.title')</a>
                        </li>
                        <li class="breadcrumb-item"><a href="#">@lang('admin.nav.shop')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('admin.shop.history.subtitle')
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <div class="content-body"><!-- Zero configuration table -->
        <section id="configuration">
            <div class="row">
                <div class="col-md-6">
                    <div class="card" id="items">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.shop.history.items')</h4>
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
                                        <th>@lang('admin.shop.history.user')</th>
                                        <th>@lang('admin.shop.history.item.name')</th>
                                        <th>@lang('admin.shop.history.item.price')</th>
                                        <th>@lang('admin.shop.history.ip')</th>
                                        <th>@lang('admin.shop.history.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" id="credits">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.shop.history.credits')</h4>
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
                                        <th>@lang('admin.shop.history.user')</th>
                                        <th>@lang('admin.shop.history.transaction.type')</th>
                                        <th>@lang('admin.shop.history.transaction.money')</th>
                                        <th>@lang('admin.shop.history.transaction.amount')</th>
                                        <th>@lang('admin.shop.history.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="paypal">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.shop.history.paypal')</h4>
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
                                        <th>@lang('admin.shop.history.user')</th>
                                        <th>@lang('admin.shop.history.transaction.points')</th>
                                        <th>@lang('admin.shop.history.paypal.amount')</th>
                                        <th>@lang('admin.shop.history.paypal.tax')</th>
                                        <th>@lang('admin.shop.history.paypal.id')</th>
                                        <th>@lang('admin.shop.history.paypal.email')</th>
                                        <th>@lang('admin.shop.history.paypal.status')</th>
                                        <th>@lang('admin.shop.history.paypal.date')</th>
                                        <th>@lang('admin.shop.history.paypal.case_date')</th>
                                        <th>@lang('admin.shop.history.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="card" id="dedipass">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.shop.history.dedipass')</h4>
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
                                        <th>@lang('admin.shop.history.user')</th>
                                        <th>@lang('admin.shop.history.transaction.points')</th>
                                        <th>@lang('admin.shop.history.transaction.amount_net')</th>
                                        <th>@lang('admin.shop.history.dedipass.code')</th>
                                        <th>@lang('admin.shop.history.dedipass.rate')</th>
                                        <th>@lang('admin.shop.history.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" id="hipay">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.shop.history.hipay')</h4>
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
                                        <th>@lang('admin.shop.history.user')</th>
                                        <th>@lang('admin.shop.history.transaction.points')</th>
                                        <th>@lang('admin.shop.history.transaction.amount')</th>
                                        <th>@lang('admin.shop.history.transaction.id')</th>
                                        <th>@lang('admin.shop.history.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-12">
                    <div class="card" id="paysafecard">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.shop.history.paysafecard')</h4>
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
                                        <th>@lang('admin.shop.history.user')</th>
                                        <th>@lang('admin.shop.history.transaction.points')</th>
                                        <th>@lang('admin.shop.history.transaction.amount_net')</th>
                                        <th>@lang('admin.shop.history.transaction.id')</th>
                                        <th>@lang('admin.shop.history.date')</th>
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
        var itemsTable = $('#items table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ url('/admin/shop/history/data/items') }}',
            'pageLength': 10,
            'lengthChange': false,
            'columns': [
                {"data": "user.username"},
                {"data": "item.name"},
                {"data": "item.price"},
                {"data": "ip"},
                {"data": "created_at"}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('/users/edit') }}/' + data + '">' + data + '</a>';
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return 'x' + row.quantity + ' ' + data;
                    },
                    "targets": 1
                },
                {
                    "render": function ( data, type, row ) {
                        return data + ' points';
                    },
                    "targets": 2
                }
            ],
            'language': datatableLang
        });
        $('#items [data-action="reload"]').on('click', function () {
            itemsTable.ajax.reload();
        });
    </script>
    <script>
        var creditsTable = $('#credits table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ url('/admin/shop/history/data/credits') }}',
            'pageLength': 10,
            'lengthChange': false,
            'columns': [
                {"data": "user.username"},
                {"data": "transaction_type"},
                {"data": "money"},
                {"data": "amount"},
                {"data": "created_at"}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('/users/edit') }}/' + data + '">' + data + '</a>';
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return data + '€';
                    },
                    "targets": 2
                },
                {
                    "render": function ( data, type, row ) {
                        return data + ' points';
                    },
                    "targets": 3
                }
            ],
            'language': datatableLang
        });
        $('#credits [data-action="reload"]').on('click', function () {
            creditsTable.ajax.reload();
        });
    </script>
    <script>
        var paypalTable = $('#paypal table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ url('/admin/shop/history/data/paypal') }}',
            'pageLength': 10,
            'lengthChange': false,
            'columns': [
                {"data": "history.user.username"},
                {"data": "history.money"},
                {"data": "payment_amount"},
                {"data": "payment_tax"},
                {"data": "payment_id"},
                {"data": "buyer_email"},
                {"data": "status"},
                {"data": "payment_date"},
                {"data": "case_date"},
                {"data": "created_at"}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('/users/edit') }}/' + data + '">' + data + '</a>';
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
                        return data + '€';
                    },
                    "targets": 2
                },
                {
                    "render": function ( data, type, row ) {
                        return '-' + data + '€';
                    },
                    "targets": 3
                },
                {
                    "render": function ( data, type, row ) {
                        return '<a href="https://www.paypal.com/fr/cgi-bin/webscr?cmd=_view-a-trans&id=' + data + '">' + data + '</a>';
                    },
                    "targets": 4
                },
                {
                    "render": function ( data, type, row ) {
                        if (data === 'COMPLETED')
                            return '<span class="badge badge-success">' + data + '</span>';
                        else if (data === 'REVERSED')
                            return '<span class="badge badge-error">' + data + '</span>';
                        else if (data === 'REFUNDED')
                            return '<span class="badge badge-warning">' + data + '</span>';
                        else if (data === 'CANCELED_REVERSAL')
                            return '<span class="badge badge-info">' + data + '</span>';
                    },
                    "targets": 6
                },
            ],
            'language': datatableLang
        });
        $('#paypal [data-action="reload"]').on('click', function () {
            paypalTable.ajax.reload();
        });
    </script>
    <script>
        var dedipassTable = $('#dedipass table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ url('/admin/shop/history/data/dedipass') }}',
            'pageLength': 10,
            'lengthChange': false,
            'columns': [
                {"data": "history.user.username"},
                {"data": "history.money"},
                {"data": "payout"},
                {"data": "code"},
                {"data": "rate"},
                {"data": "created_at"}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('/users/edit') }}/' + data + '">' + data + '</a>';
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return data + '€';
                    },
                    "targets": 2
                },
                {
                    "render": function ( data, type, row ) {
                        return data + ' points';
                    },
                    "targets": 1
                }
            ],
            'language': datatableLang
        });
        $('#dedipass [data-action="reload"]').on('click', function () {
            dedipassTable.ajax.reload();
        });
    </script>
    <script>
        var hipayTable = $('#hipay table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ url('/admin/shop/history/data/hipay') }}',
            'pageLength': 10,
            'lengthChange': false,
            'columns': [
                {"data": "history.user.username"},
                {"data": "history.money"},
                {"data": "payment_amount"},
                {"data": "payment_id"},
                {"data": "created_at"}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('/users/edit') }}/' + data + '">' + data + '</a>';
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return data + '€';
                    },
                    "targets": 2
                },
                {
                    "render": function ( data, type, row ) {
                        return data + ' points';
                    },
                    "targets": 1
                }
            ],
            'language': datatableLang
        });
        $('#hipay [data-action="reload"]').on('click', function () {
            hipayTable.ajax.reload();
        });
    </script>
    <script>
        var paysafecardTable = $('#paysafecard table').DataTable({
            'processing': true,
            'serverSide': true,
            'ajax': '{{ url('/admin/shop/history/data/paysafecard') }}',
            'pageLength': 10,
            'lengthChange': false,
            'columns': [
                {"data": "history.user.username"},
                {"data": "history.money"},
                {"data": "payment_amount"},
                {"data": "payment_id"},
                {"data": "created_at"}
            ],
            "columnDefs": [
                {
                    "render": function ( data, type, row ) {
                        return '<a href="{{ url('/users/edit') }}/' + data + '">' + data + '</a>';
                    },
                    "targets": 0
                },
                {
                    "render": function ( data, type, row ) {
                        return data + '€';
                    },
                    "targets": 2
                },
                {
                    "render": function ( data, type, row ) {
                        return data + ' points';
                    },
                    "targets": 1
                }
            ],
            'language': datatableLang
        });
        $('#paysafecard [data-action="reload"]').on('click', function () {
            paysafecardTable.ajax.reload();
        });
    </script>
@endsection