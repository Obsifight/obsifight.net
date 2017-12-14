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
@endsection