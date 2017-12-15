@extends('admin.layouts.app')

@section('title', $title)

@section('content')

    <div class="content-header row">
        <div class="content-header-left col-md-6 col-12 mb-2">
            <h3 class="content-header-title mb-0">@lang('admin.nav.shop')</h3>
            <div class="row breadcrumbs-top">
                <div class="breadcrumb-wrapper col-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ url('/admin') }}">@lang('dashboard.title')</a>
                        </li>
                        <li class="breadcrumb-item active">{{ $title }}
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content-body">
        <section id="configuration">
            <div class="row">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-header">
                            <h4 class="card-title" id="basic-layout-colored-form-control">{{ $title }}</h4>
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

                                <form class="form" data-ajax method="post" data-ajax-custom-data="parseForm" action="{{ ($sale->id) ? url('/admin/shop/sale/edit/' . $sale->id) : url('/admin/shop/sale/add') }}">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('admin.shop.sale.edit.type')</label>
                                                    <select name="product_type" class="form-control">
                                                        @foreach(['ALL', 'ITEM', 'CATEGORY'] as $type)
                                                            <option value="{{ $type }}"{{ $sale->product_type === $type ? ' selected' : '' }}>{{ __('admin.shop.sale.edit.type.' . strtolower($type)) }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12" id="saleProductItem" style="display: none;">
                                                <div class="form-group">
                                                    <label>@lang('admin.shop.sale.edit.product')</label>
                                                    <select name="product_id_item" class="form-control">
                                                        @foreach($items as $item)
                                                            <option value="{{ $item->id }}"{{ $sale->product_id === $item->id ? ' selected' : '' }}>{{ $item->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12" id="saleProductCategory" style="display: none;">
                                                <div class="form-group">
                                                    <label>@lang('admin.shop.sale.edit.product')</label>
                                                    <select name="product_id_category" class="form-control">
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}"{{ $sale->product_id === $category->id ? ' selected' : '' }}>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('admin.shop.sale.edit.reduction')</label>
                                                    <div class="input-group">
                                                        <input type="text" value="{{ $sale->reduction }}" name="reduction" class="form-control border-primary">
                                                        <span class="input-group-addon">%</span>
                                                    </div>
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
            </div>
        </section>
    </div>

@endsection
@section('script')
    <script type="text/javascript">
        function initSelect(value)
        {
            if (value === 'ALL') {
                $('#saleProductItem').slideUp();
                $('#saleProductCategory').slideUp();
            } else if (value === 'ITEM') {
                $('#saleProductItem').slideDown();
                $('#saleProductCategory').slideUp();
            } else if (value === 'CATEGORY') {
                $('#saleProductItem').slideUp();
                $('#saleProductCategory').slideDown();
            }
        }
        $('select[name="product_type"]').on('change', function (e) {
            var select = $(this)
            var value = select.val();

            initSelect(value);
        });
        initSelect($('select[name="product_type"]').val());
        function parseForm(form)
        {
            var product_type = form.find('select[name="product_type"]').val();
            return {
                reduction: form.find('input[name="reduction"]').val(),
                product_type: product_type,
                product_id: (product_type === 'ALL') ? null : (product_type === 'CATEGORY' ? form.find('select[name="product_id_category"]').val() : form.find('select[name="product_id_item"]').val())
            };
        }
    </script>
@endsection