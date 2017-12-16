@extends('admin.layouts.app')

@section('title', __('admin.nav.shop'))

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">@lang('admin.nav.shop')</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">@lang('dashboard.title')</a>
                        </li>
                        <li class="breadcrumb-item active">@lang('admin.nav.shop')
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
                            <h4 class="card-title">@lang('admin.shop.items')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a href="{{ url('/admin/shop/item/add') }}" class="btn btn-success">
                                            <i class="fa fa-plus"></i> &nbsp;@lang('admin.shop.item.add')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <table class="table table-striped table-bordered zero-configuration datatable">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.shop.item.name')</th>
                                        <th>@lang('admin.shop.item.price')</th>
                                        <th>@lang('admin.shop.item.displayed')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($items as $item)
                                            <tr>
                                                <td>{{ $item->name }}</td>
                                                <td>{{ $item->price }} points</td>
                                                <td style="text-align: center;">
                                                    @if ($item->displayed)
                                                        <span class="badge badge-success">@lang('global.yes')</span>
                                                    @else
                                                        <span class="badge badge-danger">@lang('global.no')</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <a href="{{ url('/admin/shop/item/edit/' . $item->id) }}" class="btn btn-primary">@lang('global.edit')</a>
                                                    <a href="{{ url('/admin/shop/item/delete/' . $item->id) }}" class="btn btn-danger">@lang('global.delete')</a>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" id="items">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.shop.categories')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a href="{{ url('/admin/shop/category/add') }}" class="btn btn-success">
                                            <i class="fa fa-plus"></i> &nbsp;@lang('admin.shop.category.add')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <table class="table table-striped table-bordered zero-configuration datatable">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.shop.category.name')</th>
                                        <th>@lang('admin.shop.item.displayed')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($categories as $category)
                                        <tr>
                                            <td>{{ $category->name }}</td>
                                            <td style="text-align: center;">
                                                @if ($category->displayed)
                                                    <span class="badge badge-success">@lang('global.yes')</span>
                                                @else
                                                    <span class="badge badge-danger">@lang('global.no')</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ url('/admin/shop/category/edit/' . $category->id) }}" class="btn btn-primary">@lang('global.edit')</a>
                                                <a href="{{ url('/admin/shop/category/delete/' . $category->id) }}" class="btn btn-danger">@lang('global.delete')</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" id="items">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.shop.vouchers')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a href="{{ url('/admin/shop/voucher/add') }}" class="btn btn-success">
                                            <i class="fa fa-plus"></i> &nbsp;@lang('admin.shop.voucher.add')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <table class="table table-striped table-bordered zero-configuration datatable">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.shop.voucher.edit.code')</th>
                                        <th>@lang('admin.shop.voucher.edit.money')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($vouchers as $voucher)
                                        <tr>
                                            <td>
                                                @permission('shop-admin-vouchers')
                                                    {{ $voucher->code }}
                                                @endpermission
                                            </td>
                                            <td>{{ $voucher->money }} points</td>
                                            <td>
                                                <a href="{{ url('/admin/shop/voucher/edit/' . $voucher->id) }}" class="btn btn-primary">@lang('global.edit')</a>
                                                <a href="{{ url('/admin/shop/voucher/delete/' . $voucher->id) }}" class="btn btn-danger">@lang('global.delete')</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card" id="items">
                        <div class="card-header">
                            <h4 class="card-title">@lang('admin.shop.sales')</h4>
                            <a class="heading-elements-toggle"><i class="fa fa-ellipsis-v font-medium-3"></i></a>
                            <div class="heading-elements">
                                <ul class="list-inline mb-0">
                                    <li>
                                        <a href="{{ url('/admin/shop/sale/add') }}" class="btn btn-success">
                                            <i class="fa fa-plus"></i> &nbsp;@lang('admin.shop.sale.add')
                                        </a>
                                    </li>
                                </ul>
                            </div>
                        </div>
                        <div class="card-content collapse show">
                            <div class="card-body card-dashboard">
                                <table class="table table-striped table-bordered zero-configuration datatable">
                                    <thead>
                                    <tr>
                                        <th>@lang('admin.shop.sale.type')</th>
                                        <th>@lang('admin.shop.sale.type_name')</th>
                                        <th>@lang('admin.shop.sale.reduction')</th>
                                        <th></th>
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach($sales as $sale)
                                        <tr>
                                            <td>
                                                @if ($sale->product_type === 'ITEM')
                                                    @lang('admin.shop.sale.item')
                                                @elseif ($sale->product_type === 'CATEGORY')
                                                    @lang('admin.shop.sale.category')
                                                @elseif ($sale->product_type === 'ALL')
                                                    @lang('admin.shop.sale.all')
                                                @endif
                                            </td>
                                            <td>
                                                @if ($sale->product_type !== 'ALL')
                                                    {{ $sale->{($sale->product_type === 'ITEM' ? 'item' : 'category')}->name }}
                                                @else
                                                    <span class="text-muted">N/A</span>
                                                @endif
                                            </td>
                                            <td>-{{ $sale->reduction }}%</td>
                                            <td>
                                                <a href="{{ url('/admin/shop/sale/edit/' . $sale->id) }}" class="btn btn-primary">@lang('global.edit')</a>
                                                <a href="{{ url('/admin/shop/sale/delete/' . $sale->id) }}" class="btn btn-danger">@lang('global.delete')</a>
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
        $('.datatable').DataTable({
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