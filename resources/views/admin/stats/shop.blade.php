@extends('admin.layouts.app')

@section('title', __('admin.stats.shop.title'))

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">@lang('admin.stats.shop.title')</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">@lang('dashboard.title')</a>
                        </li>
                        <li class="breadcrumb-item"><a href="">@lang('shop.title')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('admin.stats.title')
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
                                <i class="fa fa-shopping-basket font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-primary white media-body">
                                <h5>@lang('admin.stats.shop.purchases.items.count')</h5>
                                <h5 class="text-bold-400 mb-0">
                                    {{ $purchasesItemsCount }}
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
                                <i class="fa fa-credit-card font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-danger white media-body">
                                <h5>@lang('admin.stats.shop.purchases.credits.count')</h5>
                                <h5 class="text-bold-400 mb-0">
                                    {{ $purchasesCreditsCount }}
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
                                <i class="fa fa-money font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-warning white media-body">
                                <h5>@lang('admin.stats.shop.profit.total')</h5>
                                <h5 class="text-bold-400 mb-0">
                                    {{ number_format($profitTotal, 2) }}€
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
                                <i class="fa fa-euro font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-success white media-body">
                                <h5>@lang('admin.stats.shop.profit.month')</h5>
                                <h5 class="text-bold-400 mb-0">
                                    {{ number_format($profitThisMonth, 2) }}€
                                </h5>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <div class="row">
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">@lang('admin.stats.shop.graph.payments_modes')</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body chartjs">
                            <canvas id="payments-modes-graph" height="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">@lang('dashboard.stats.credits.graph')</h4>
                        <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                        <div class="heading-elements">
                            <ul class="list-inline mb-0">
                                <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                            </ul>
                        </div>
                    </div>
                    <div class="card-content collapse show">
                        <div class="card-body chartjs">
                            <canvas id="credits-graph" height="500"></canvas>
                        </div>
                    </div>
                </div>
            </div>

            <div class="col-md-12">
                <div class="card">
                    <div class="card-content">
                        <div class="card-body sales-growth-chart">
                            <div id="items-sales" class="height-250"></div>
                        </div>
                    </div>
                    <div class="card-footer">
                        <div class="chart-title mb-1 text-center">
                            <h6>@lang('admin.stats.shop.items.sales.total').</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        $.get('{{ url('/stats/server/count') }}', function (data) {
            if (data.status)
                $('#server_count').html(nFormatter(data.count, 1))
        })
        $.get('{{ url('/stats/visits/count') }}', function (data) {
            if (data.status)
                $('#visits_count').html(nFormatter(data.count, 1))
        })
    </script>
    <script src="{{ url('/admin-assets/vendors/js/charts/chart.min.js') }}" type="text/javascript"></script>
    <script src="{{ url('/js/moment.js') }}"></script>
    <script src="{{ url('/js/async.min.js') }}"></script>
    <script>
        moment.locale('fr');
        // Purchase credits, purchase items, transfers credits graphs
        // -----------------------------------------------------------
        async.parallel([
            function (next) {
                $.get('{{ url('/admin/stats/shop/graph/purchases/credits') }}', function (data) {
                    next(undefined, data.graph);
                });
            },
            function (next) {
                $.get('{{ url('/admin/stats/shop/graph/purchases/items') }}', function (data) {
                    next(undefined, data.graph);
                });
            },
            function (next) {
                $.get('{{ url('/admin/stats/shop/graph/transfers') }}', function (data) {
                    next(undefined, data.graph);
                });
            }
        ], function (err, results) {
            creditsData = results[0];
            itemsData = results[1];
            transfersData = results[2];

            new Chart($("#credits-graph"), {
                type: 'line',

                // Chart Options
                options : {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom'
                    },
                    hover: {
                        mode: 'label'
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            gridLines: {
                                color: "#f3f3f3",
                                drawTicks: false
                            },
                            scaleLabel: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: "#f3f3f3",
                                drawTicks: false
                            },
                            scaleLabel: {
                                display: false
                            }
                        }]
                    },
                    title: {
                        display: false
                    }
                },

                // Chart Data
                data : {
                    labels: [
                        moment().subtract(6, 'day').format('dddd'),
                        moment().subtract(5, 'day').format('dddd'),
                        moment().subtract(4, 'day').format('dddd'),
                        moment().subtract(3, 'day').format('dddd'),
                        moment().subtract(2, 'day').format('dddd'),
                        moment().subtract(1, 'day').format('dddd'),
                        moment().format('dddd')
                    ],
                    datasets: [{
                        label: "@lang('dashboard.stats.credits.graph.credits')",
                        data: creditsData,
                        backgroundColor: "rgba(255,82,82,.2)",
                        borderColor: "rgba(255,82,82,1)",
                        pointBorderColor: "#FF5252",
                        pointBackgroundColor: "#FFF",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }, {
                        label: "@lang('dashboard.stats.credits.graph.items')",
                        data: itemsData,
                        backgroundColor: "rgba(255,160,0,.2)",
                        borderColor: "rgba(255,160,0,1)",
                        pointBorderColor: "#FFA000",
                        pointBackgroundColor: "#FFF",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }, {
                        label: "@lang('dashboard.stats.credits.graph.transfer')",
                        data: transfersData,
                        backgroundColor: "rgba(0,191,165,.2)",
                        borderColor: "rgba(0,191,165,1)",
                        pointBorderColor: "#00BFA5",
                        pointBackgroundColor: "#FFF",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }]
                }
            });
        });
    </script>
    <script>
        // Purchase credits modes
        // ----------------------
        $.get('{{ url('/admin/stats/shop/graph/purchases/credits/modes') }}', function (data) {
            data = data.graph;

            new Chart($("#payments-modes-graph"), {
                type: 'line',

                // Chart Options
                options : {
                    responsive: true,
                    maintainAspectRatio: false,
                    legend: {
                        position: 'bottom'
                    },
                    hover: {
                        mode: 'label'
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            gridLines: {
                                color: "#f3f3f3",
                                drawTicks: false
                            },
                            scaleLabel: {
                                display: false
                            }
                        }],
                        yAxes: [{
                            display: true,
                            gridLines: {
                                color: "#f3f3f3",
                                drawTicks: false
                            },
                            scaleLabel: {
                                display: false
                            }
                        }]
                    },
                    title: {
                        display: false
                    }
                },

                // Chart Data
                data : {
                    labels: [
                        moment().subtract(5, 'month').format('MMMM'),
                        moment().subtract(4, 'month').format('MMMM'),
                        moment().subtract(3, 'month').format('MMMM'),
                        moment().subtract(2, 'month').format('MMMM'),
                        moment().subtract(1, 'month').format('MMMM'),
                        moment().format('MMMM')
                    ],
                    datasets: [{
                        label: "@lang('admin.stats.shop.graph.payments_modes.paypal')",
                        data: data.PAYPAL,
                        backgroundColor: "transparent",
                        borderColor: "rgba(255,82,82,.7)",
                        pointBorderColor: "#FF5252",
                        pointBackgroundColor: "#FFF",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }, {
                        label: "@lang('admin.stats.shop.graph.payments_modes.dedipass')",
                        data: data.DEDIPASS,
                        backgroundColor: "transparent",
                        borderColor: "rgba(255,160,0,.7)",
                        pointBorderColor: "#FFA000",
                        pointBackgroundColor: "#FFF",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }, {
                        label: "@lang('admin.stats.shop.graph.payments_modes.hipay')",
                        data: data.HIPAY,
                        backgroundColor: "transparent",
                        borderColor: "rgba(12,194,126,.7)",
                        pointBorderColor: "#00BFA5",
                        pointBackgroundColor: "#FFF",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }, {
                        label: "@lang('admin.stats.shop.graph.payments_modes.paysafecard')",
                        data: data.PAYSAFECARD,
                        backgroundColor: "transparent",
                        borderColor: "rgba(77,203,205,.7)",
                        pointBorderColor: "#00BFA5",
                        pointBackgroundColor: "#FFF",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }]
                }
            });
        });
    </script>
    <script>
        // Purchase credits modes
        // ----------------------
        $.get('{{ url('/admin/stats/shop/graph/purchases/items/total') }}', function (data) {
            data = data.graph;

            Morris.Bar.prototype.fillForSeries = function(i) {
                return "0-#fff-#f00:20-#000";
            };

            Morris.Bar({
                element: 'items-sales',
                data: data,
                xkey: 'name',
                ykeys: ['count'],
                labels: ['{{ __('admin.stats.shop.items.sales') }}'],
                barGap: 4,
                barSizeRatio: 0.3,
                gridTextColor: '#bfbfbf',
                gridLineColor: '#E4E7ED',
                numLines: 5,
                gridtextSize: 14,
                resize: true,
                barColors: ['#00B5B8'],
                hideHover: 'auto'
            });
        });
    </script>
@endsection