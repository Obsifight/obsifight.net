@extends('admin.layouts.app')

@section('title', __('admin.users.emails.update.requests'))

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
                        <li class="breadcrumb-item active">@lang('admin.users.emails.update.requests')
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
                    <div class="card" id="find">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.users.emails.update.requests')</h4>
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
                                        <th>@lang('admin.users.emails.new')</th>
                                        <th>@lang('user.profile.email.edit.reason')</th>
                                        <th>@lang('user.profile.login.logs.ip')</th>
                                        <th>@lang('global.date')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($requests as $request)
                                            <tr>
                                                <td>{{ $request->user->username }}</td>
                                                <td>{{ $request->user->email }}</td>
                                                <td>{{ $request->email }}</td>
                                                <td><i>{{ $request->reason }}</i></td>
                                                <td>{{ $request->ip }}</td>
                                                <td>{{ $request->created_at->diffForHumans() }}</td>
                                                <td>
                                                    <form action="{{ url('/admin/emails/updates/' . $request->id . '/invalid') }}" method="post" data-ajax data-ajax-custom-callback="afterResponse">
                                                        <button type="submit" class="btn btn-danger btn-min-width mr-1 mb-1">@lang('admin.users.emails.update.requests.invalid')</button>
                                                    </form>
                                                    <form action="{{ url('/admin/emails/updates/' . $request->id . '/valid') }}" method="post" data-ajax data-ajax-custom-callback="afterResponse">
                                                        <button type="submit" class="btn btn-success btn-min-width mr-1 mb-1">@lang('admin.users.emails.update.requests.valid')</button>
                                                    </form>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
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
    <script type="text/javascript">
        $('table').DataTable({
            lengthChange: false,
            ordering: false,
            language: datatableLang
        });

        function afterResponse(a, b, form)
        {
            form.parent().parent().remove()
        }
    </script>
@endsection