@extends('admin.layouts.app')

@section('title', __('dashboard.title'))

@section('content')

    <div class="content-header row">
    </div>
    <div class="content-body"><!-- Stats -->
        <div class="row">
            <div class="col-xl-3 col-lg-6 col-12">
                <div class="card">
                    <div class="card-content">
                        <div class="media align-items-stretch">
                            <div class="p-2 text-center bg-primary bg-darken-2">
                                <i class="ft-users font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-primary white media-body">
                                <h5>@lang('dashboard.stats.players.online')</h5>
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
                            <div class="p-2 text-center bg-danger bg-darken-2">
                                <i class="icon-user font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-danger white media-body">
                                <h5>@lang('dashboard.stats.users.count')</h5>
                                <h5 class="text-bold-400 mb-0"><i class="ft-arrow-{{ $usersCount > 0 ? 'up' : 'down' }}"></i>
                                    {{ number_format(abs($usersCount)) }}
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
                                <i class="icon-basket-loaded font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-warning white media-body">
                                <h5>@lang('dashboard.stats.purchases.count')</h5>
                                <h5 class="text-bold-400 mb-0"><i class="ft-arrow-{{ $purchaseCount > 0 ? 'up' : 'down' }}"></i>
                                    {{ number_format(abs($purchaseCount)) }}
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
                                <i class="fa fa-eye font-large-2 white"></i>
                            </div>
                            <div class="p-2 bg-gradient-x-success white media-body">
                                <h5>@lang('dashboard.stats.visits.count')</h5>
                                <h5 class="text-bold-400 mb-0">
                                    <span id="visits_count">&nbsp;&nbsp;<div class="fa fa-refresh fa-spin"></div>&nbsp;&nbsp;</span>
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
                        <h4 class="card-title">@lang('dashboard.stats.users.graph')</h4>
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
                            <canvas id="users-graph" height="500"></canvas>
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
    <script type="text/javascript">
        moment.locale('fr');
        // Users and visits graphs
        // ------------------------------
        async.parallel([
            function (next) {
                $.get('{{ url('/stats/users/graph/register') }}', function (data) {
                    next(undefined, data.graph);
                });
            },
            function (next) {
                $.get('{{ url('/stats/users/graph/visits') }}', function (data) {
                    next(undefined, data.graph);
                });
            }
        ], function (err, results) {
            usersData = results[0];
            visitsData = results[1];

            new Chart($("#users-graph"), {
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
                        moment().subtract(7, 'day').format('dddd'),
                        moment().subtract(6, 'day').format('dddd'),
                        moment().subtract(5, 'day').format('dddd'),
                        moment().subtract(4, 'day').format('dddd'),
                        moment().subtract(3, 'day').format('dddd'),
                        moment().subtract(2, 'day').format('dddd'),
                        moment().subtract(1, 'day').format('dddd')
                    ],
                    datasets: [{
                        label: "@lang('dashboard.stats.users.graph.visits')",
                        data: visitsData,
                        backgroundColor: "rgba(209,212,219,.4)",
                        borderColor: "transparent",
                        pointBorderColor: "#D1D4DB",
                        pointBackgroundColor: "#FFF",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }, {
                        label: "@lang('dashboard.stats.users.graph.users')",
                        data: usersData,
                        backgroundColor: "rgba(81,117,224,.7)",
                        borderColor: "transparent",
                        pointBorderColor: "#5175E0",
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
                        moment().subtract(7, 'day').format('dddd'),
                        moment().subtract(6, 'day').format('dddd'),
                        moment().subtract(5, 'day').format('dddd'),
                        moment().subtract(4, 'day').format('dddd'),
                        moment().subtract(3, 'day').format('dddd'),
                        moment().subtract(2, 'day').format('dddd'),
                        moment().subtract(1, 'day').format('dddd')
                    ],
                    datasets: [{
                        label: "@lang('dashboard.stats.credits.graph.credits')",
                        data: creditsData,
                        backgroundColor: "rgba(255,82,82,.7)",
                        borderColor: "transparent",
                        pointBorderColor: "#FF5252",
                        pointBackgroundColor: "#FFF",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }, {
                        label: "@lang('dashboard.stats.credits.graph.items')",
                        data: itemsData,
                        backgroundColor: "rgba(255,160,0,.7)",
                        borderColor: "transparent",
                        pointBorderColor: "#FFA000",
                        pointBackgroundColor: "#FFF",
                        pointBorderWidth: 2,
                        pointHoverBorderWidth: 2,
                        pointRadius: 4
                    }, {
                        label: "@lang('dashboard.stats.credits.graph.transfer')",
                        data: transfersData,
                        backgroundColor: "rgba(0,191,165,.7)",
                        borderColor: "transparent",
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
@endsection