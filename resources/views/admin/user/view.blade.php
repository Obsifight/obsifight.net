@extends('admin.layouts.app')

@section('title', $user->username)

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
                        <li class="breadcrumb-item"><a href="{{ url('/admin/users') }}">@lang('admin.users.find')</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $user->username }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="configuration">
            <div class="row">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-colored-form-control">@lang('admin.users.profile')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">

                                <form class="form" data-ajax method="post"
                                      action="{{ url('/admin/users/edit/' . $user->id) }}">
                                    <div class="form-body">
                                        <h4 class="form-section"><i class="icon-eye6"></i> @lang('admin.users.profile.basic')</h4>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('user.field.username')</label>
                                                    <input type="text" value="{{ $user->username }}" class="form-control border-primary" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>UUID</label>
                                                    <input type="text" value="{{ $user->uuid }}" class="form-control border-primary" disabled>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('user.field.password')</label>
                                                    <input type="password" class="form-control border-primary" placeholder="@lang('admin.users.profile.basic.password.edit')" name="password">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>IP</label>
                                                    <input type="text" value="{{ $user->ip }}" class="form-control border-primary" disabled>
                                                </div>
                                            </div>
                                        </div>

                                        <h4 class="form-section"><i class="ft-mail"></i> @lang('admin.users.profile.contact')</h4>

                                        <div class="form-group">
                                            <label>@lang('user.field.email')</label>
                                            <input class="form-control border-primary" name="email" type="email" value="{{ $user->email }}">
                                        </div>

                                        <div class="form-group">
                                            @if ($user->youtubeChannel)
                                                <a href="https://youtube.com/channel/{{ $user->youtubeChannel->channel_id }}"
                                                   class="btn btn-social width-200 mr-1 mb-1 btn-google">
                                                    <span class="fa fa-youtube font-medium-3"></span> @lang('user.field.youtube_channel')
                                                </a>
                                            @endif
                                            @if ($user->twitterAccount)
                                                <a href="https://twitter.com/{{ $user->twitterAccount->screen_name }}"
                                                   class="btn btn-social width-200 mr-1 mb-1 btn-vimeo">
                                                    <span class="fa fa-twitter font-medium-3"></span> @lang('user.field.twitter_account')
                                                </a>
                                            @endif
                                        </div>

                                        @if ($user->usernameHistory->count() > 0)
                                            <h4 class="form-section"><i
                                                        class="fa fa-arrows-h"></i> @lang('admin.users.profile.username.history')
                                            </h4>

                                            <table class="table table-striped table-bordered">
                                                <tbody>
                                                @foreach($user->usernameHistory as $history)
                                                    <tr>
                                                        <td>{{ $history->old_username }}</td>
                                                        <td><i class="fa fa-long-arrow-right"></i></td>
                                                        <td>{{ $history->new_username }}</td>
                                                    </tr>
                                                @endforeach
                                                </tbody>
                                            </table>
                                        @endif

                                        <h4 class="form-section"><i class="ft-mail"></i> @lang('admin.users.profile.money')</h4>

                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('admin.users.profile.money.money')</label>
                                                    <input type="text" value="{{ $user->money }} points" class="form-control border-primary" disabled>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('admin.users.profile.money.refund')</label>
                                                    <input type="text" value="{{ $user->refundHistory ? $user->refundHistory->amount : '0' }} points" class="form-control border-primary" disabled>
                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <div class="form-actions right">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fa fa-check-square-o"></i> @lang('global.save')
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-colored-form-control">@lang('user.obsiguard')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">
                                @if ($user->obsiguardIP->count() === 0)
                                    <div class="alert bg-danger alert-dismissible mb-2" role="alert">
                                        @lang('admin.users.obsiguard.disabled')
                                    </div>
                                @else
                                    <h4 class="form-section">
                                        <i class="fa fa-list"></i> @lang('admin.users.obsiguard.ips')
                                    </h4>

                                    <table class="table table-striped table-bordered">
                                        <tbody>
                                            @foreach($user->obsiguardIP as $ip)
                                                <tr>
                                                    <td>{{ $ip->ip }}</td>
                                                    <td>
                                                        <form action="{{ url('/admin/users/edit/' . $user->id . '/obsiguard/delete/' . $ip->id) }}" method="post" data-ajax data-ajax-custom-callback="afterDeleteObsiGuardIP">
                                                            <button type="submit" class="btn btn-danger btn-min-width mr-1 mb-1">@lang('global.delete')</button>
                                                        </form>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                @endif
                                <hr>
                                <h4 class="form-section"><i class="fa fa-table"></i> @lang('admin.users.obsiguard.history')
                                </h4>

                                <table class="table table-striped table-bordered" id="obsiguardLogs">
                                    <thead>
                                        <tr>
                                            <th>@lang('admin.users.obsiguard.history.type')</th>
                                            <th>@lang('admin.users.obsiguard.history.data')</th>
                                            <th>@lang('admin.users.obsiguard.history.ip')</th>
                                            <th>@lang('admin.users.obsiguard.history.date')</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->obsiguardLog->reverse() as $log)
                                        <tr>
                                            <td>
                                                @if ($log->type === 'ENABLE')
                                                    <span class="badge badge-success">@lang('admin.users.obsiguard.enable')</span>
                                                @elseif ($log->type === 'DISABLE')
                                                    <span class="badge badge-danger">@lang('admin.users.obsiguard.disable')</span>
                                                @elseif ($log->type === 'REMOVE')
                                                    <span class="badge badge-warning">@lang('admin.users.obsiguard.remove_ip')</span>
                                                @elseif ($log->type === 'ADD')
                                                    <span class="badge badge-info">@lang('admin.users.obsiguard.add_ip')</span>
                                                @endif
                                            </td>
                                            <td>{{ $log->data }}</td>
                                            <td>{{ $log->ip }}</td>
                                            <td>{{ $log->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-colored-form-control">@lang('admin.users.logs')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">

                                <table class="table table-striped table-bordered" id="logLogs">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.users.obsiguard.history.type')</th>
                                        <th>@lang('admin.users.obsiguard.history.ip')</th>
                                        <th>@lang('admin.users.obsiguard.history.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->connectionLog->reverse() as $log)
                                        <tr>
                                            <td>
                                                @if ($log->type === 'LAUNCHER')
                                                    <span class="badge badge-success">Launcher</span>
                                                @elseif ($log->type === 'WEB')
                                                    <span class="badge badge-primary">Web</span>
                                                @endif
                                            </td>
                                            <td>{{ $log->ip }}</td>
                                            <td>{{ $log->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-colored-form-control">@lang('admin.users.transfer.history')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">

                                <table class="table table-striped table-bordered" id="transferLogs">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.users.transfer.history.amount')</th>
                                        <th>@lang('admin.users.transfer.history.receiver')</th>
                                        <th>@lang('admin.users.transfer.history.ip')</th>
                                        <th>@lang('admin.users.transfer.history.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->transferMoneyHistory->reverse() as $log)
                                        <tr>
                                            <td>{{ $log->amount }} points</td>
                                            <td>{{ $log->receiver->username }}</td>
                                            <td>{{ $log->ip }}</td>
                                            <td>{{ $log->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-colored-form-control">@lang('admin.users.purchase.items.history')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">

                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.shop.history.item.name')</th>
                                        <th>@lang('admin.shop.history.item.price')</th>
                                        <th>@lang('admin.shop.history.ip')</th>
                                        <th>@lang('admin.shop.history.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->purchaseItemsHistory->reverse() as $log)
                                        <tr>
                                            <td>{{ ($log->item) ? $log->item->name : 'N/A' }}</td>
                                            <td>{{ ($log->item) ? $log->item->price . ' points' : 'N/A' }}</td>
                                            <td>{{ $log->ip }}</td>
                                            <td>{{ $log->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-colored-form-control">@lang('admin.users.purchase.credits.history')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">

                                <table class="table table-striped table-bordered datatable">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.shop.history.transaction.type')</th>
                                        <th>@lang('admin.shop.history.transaction.money')</th>
                                        <th>@lang('admin.shop.history.transaction.amount')</th>
                                        <th>@lang('admin.shop.history.date')</th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($user->purchaseCreditsHistory->reverse() as $log)
                                        <tr>
                                            <td>{{ $log->transaction_type }}</td>
                                            <td>{{ $log->money }} points</td>
                                            <td>{{ $log->amount }} €</td>
                                            <td>{{ $log->created_at->diffForHumans() }}</td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>

                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-colored-form-control">@lang('admin.users.youtube.videos')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li><a data-action="collapse"><i class="ft-minus"></i></a></li>
                                    <li><a data-action="expand"><i class="ft-maximize"></i></a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body">

                                @if (!$user->youtubeChannel)
                                    <div class="alert bg-danger alert-dismissible mb-2" role="alert">
                                        @lang('admin.users.youtube.no_channel')
                                    </div>
                                @else
                                    <table class="table table-striped table-bordered datatable">
                                        <thead>
                                        <tr>
                                            <th>@lang('admin.users.youtube.videos.title')</th>
                                            <th>@lang('admin.users.youtube.videos.views')</th>
                                            <th>@lang('admin.users.youtube.videos.likes')</th>
                                            <th>@lang('admin.users.youtube.videos.publication')</th>
                                            <th>@lang('admin.users.youtube.videos.eligible')</th>
                                            <th>@lang('admin.users.youtube.videos.payed')</th>
                                            <th>@lang('admin.users.youtube.videos.created')</th>
                                            <th>@lang('admin.users.youtube.videos.updated')</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($user->youtubeChannel->videos->reverse() as $video)
                                            <tr>
                                                <td><a href="https://www.youtube.com/watch?v={{ $video->id }}" target="_blank">{{ $video->title }}</a></td>
                                                <td>{{ $video->views_count }} &nbsp;<i class="fa fa-eye"></i></td>
                                                <td>{{ $video->likes_count }} &nbsp;<i class="fa fa-thumbs-up"></i></td>
                                                <td>{{ $video->publication_date->diffForHumans() }}</td>
                                                <td>
                                                    @if ($video->eligible)
                                                        <i class="fa fa-check text-success"></i>
                                                    @else
                                                        <i class="fa fa-remove text-danger"></i>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if ($video->eligible && $video->payed)
                                                        <span class="badge badge-success">{{ $video->remunerationHistory->remuneration }} points</span>
                                                    @elseif ($video->eligible)
                                                        <span class="badge badge-warning">~{{ $video->remuneration }} points</span>
                                                    @else
                                                        <span class="text-muted">N/A</span>
                                                    @endif
                                                </td>
                                                <td>{{ $video->created_at->diffForHumans() }}</td>
                                                <td>{{ $video->updated_at->diffForHumans() }}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                @endif

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
    <script type="text/javascript">
        $('.datatable').DataTable({
            lengthChange: false,
            ordering: false,
            language: datatableLang
        });
        $('#obsiguardLogs').DataTable({
            lengthChange: false,
            ordering: false,
            language: datatableLang
        });
        $('#logLogs').DataTable({
            lengthChange: false,
            ordering: false,
            language: datatableLang
        });
        $('#transferLogs').DataTable({
            lengthChange: false,
            ordering: false,
            language: datatableLang
        });

        function afterDeleteObsiGuardIP(data, response, form)
        {
            form.parent().parent().remove()
        }
    </script>
@endsection