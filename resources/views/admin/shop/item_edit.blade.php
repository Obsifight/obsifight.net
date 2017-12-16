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

                                <form class="form" data-ajax method="post" data-ajax-custom-data="parseForm" action="{{ ($item->id) ? url('/admin/shop/item/edit/' . $item->id) : url('/admin/shop/item/add') }}">
                                    <div class="form-body">
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('admin.shop.item.edit.name')</label>
                                                    <input type="text" value="{{ $item->name }}" name="name" class="form-control border-primary">
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('admin.shop.item.edit.price')</label>
                                                    <div class="input-group">
                                                        <input type="number" step="0.01" value="{{ $item->price }}" name="price" class="form-control border-primary">
                                                        <span class="input-group-addon">points</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label>@lang('admin.shop.item.edit.category')</label>
                                                    <select class="form-control" name="category_id">
                                                        @foreach($categories as $category)
                                                            <option value="{{ $category->id }}"{{ $item->category_id == $category->id ? ' selected' : '' }}>{{ $category->name }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-md-6">
                                                <div class="form-group">
                                                    <label class="inline custom-control custom-checkbox block">
                                                        <input name="displayed" type="checkbox" {{ $item->displayed ? 'checked' : '' }} class="custom-control-input">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description ml-0">@lang('admin.shop.item.edit.displayed')</span>
                                                    </label>
                                                </div>
                                                <div class="form-group">
                                                    <label class="inline custom-control custom-checkbox block">
                                                        <input name="need_connected" type="checkbox" {{ $item->need_connected ? 'checked' : '' }} class="custom-control-input">
                                                        <span class="custom-control-indicator"></span>
                                                        <span class="custom-control-description ml-0">@lang('admin.shop.item.edit.need_connected')</span>
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('admin.shop.item.edit.description')</label>
                                                    <textarea name="description" class="form-control" cols="30" rows="10">{{ $item->description }}</textarea>
                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <div class="form-group">
                                                    <label>@lang('admin.shop.item.edit.image_path')</label>
                                                    <input type="text" value="{{ $item->image_path }}" name="image_path" class="form-control border-primary">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-md-12">
                                                @php $i = 0; @endphp
                                                @if ($item->commands)
                                                    @foreach($item->commands as $command)
                                                        <div class="form-group" data-cmd-i="{{ $i }}">
                                                            <label>@lang('admin.shop.item.edit.command')</label>
                                                            <div class="input-group">
                                                                <input type="text" value="{{ $command }}" name="commands" class="form-control border-primary">
                                                                <span class="input-group-btn">
									                                <button class="btn btn-danger deleteCommand" data-cmd-i="{{ $i++ }}" type="button">
                                                                        <i class="fa fa-remove"></i>
                                                                    </button>
								                                </span>
                                                            </div>
                                                        </div>
                                                    @endforeach
                                                @endif
                                                <div id="commands"></div>
                                                <button class="btn btn-success" id="addCommand" data-cmd-i="{{ $i }}">
                                                    <i class="fa fa-plus"></i> &nbsp;@lang('admin.shop.item.edit.command.add')
                                                </button>
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
        function initDeleteCommandEvent()
        {
            $('.deleteCommand').unbind('click');
            $('.deleteCommand').on('click', function (e) {
                e.preventDefault();
                var btn = $(this);
                $('.form-group[data-cmd-i="' + btn.attr('data-cmd-i') + '"]').slideUp(150, function () {
                    $(this).remove()
                });
            })
        }
        initDeleteCommandEvent()
        $('#addCommand').on('click', function (e) {
            e.preventDefault();
            var btn = $(this);
            btn.attr('data-cmd-i', parseInt(btn.attr('data-cmd-i')) + 1);
            var cmd = '<div class="form-group" data-cmd-i="' + btn.attr('data-cmd-i') + '">' +
                    "<label>@lang('admin.shop.item.edit.command')</label>" +
                    '<div class="input-group">\n' +
                        '<input type="text" name="commands" class="form-control border-primary">\n' +
                        '<span class="input-group-btn">\n' +
                            '<button class="btn btn-danger deleteCommand" data-cmd-i="' + btn.attr('data-cmd-i') + '" type="button"><i class="fa fa-remove"></i></button>\n' +
                        '</span>\n' +
                    '</div>' +
                '</div>';
            $('#commands').append(cmd);
            initDeleteCommandEvent()
        });

        function parseForm(form)
        {
            return {
                name: form.find('input[name="name"]').val(),
                price: form.find('input[name="price"]').val(),
                description: form.find('textarea[name="description"]').val(),
                category_id: form.find('select[name="category_id"]').val(),
                displayed: form.find('input[name="displayed"]:checked').length,
                commands: $("input[name='commands']").map(function() {
                    return $(this).val();
                }).get(),
                image_path: form.find('input[name="image_path"]').val(),
                need_connected: form.find('input[name="need_connected"]:checked').length
            };
        }
    </script>
@endsection