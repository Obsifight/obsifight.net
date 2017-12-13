@extends('layouts.app')

@section('title', __('shop.title'))

@section('content')
  @if ($mostPurchasedItems->count() > 0)
    <div class="white-block rotate mobile-hide">
      <div class="ui container page-content">

        <div class="text-center">
          <h2 class="ui header" style="display:inline-block">
            <i class="shop icon"></i>
            <div class="content">
              @lang('shop.purchases.best.title')
              <div class="sub header">@lang('shop.purchases.best.subtitle')</div>
            </div>
          </h2>
        </div>

        <div class="ui stackable centered grid">
          @foreach ($mostPurchasedItems as $item)
            <div class="ui five wide column">
              <div class="ui very relaxed items best-sales">
                <div class="item">
                  <div class="image">
                    <img src="{{ $item->item->image_path ? $item->item->image_path : url('/img/logo.png') }}">
                  </div>
                  <div class="middle aligned content">
                    <span class="header">{{ $item->item->name }}</span>
                    <div class="extra">
                      @if ($loop->first)
                        <div class="ui label"><i class="trophy icon" style="color:#ffd700"></i> @lang('shop.purchases.best')</div>
                      @endif
                      <div class="ui label">{{ $item->item->category->name }}</div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          @endforeach
        </div>

      </div>
    </div>
  @endif
  <div class="colored-block divider-shop">
    <div class="ui container page-content">
      <h1 class="ui header">
        <i class="shopping basket icon"></i>
        <div class="content">
          @lang('shop.items')
        </div>
      </h1>
    </div>
  </div>
  <div class="ui container page-content">

    @foreach ($sales as $sale)
      <div class="ui info message">
        <div class="header">
          @lang('shop.voucher.title')
        </div>
        <p>{!! $sale !!}</p>
      </div>
    @endforeach

    @if ($categories->count() == 0)
      <div class="ui icon error message">
        <i class="shopping basket circle icon"></i>
        <div class="content">
          <div class="header">
            @lang('shop.no_items.title')
          </div>
          <p>@lang('shop.no_items.subtitle')</p>
        </div>
      </div>
    @endif

    <div class="ui stackable grid">

      <div class="ui three wide column">
        @if (Auth::user())
          <a href="{{ url('/shop/credit/add') }}" class="ui yellow fluid button">
            <i class="shop icon"></i>
            @lang('shop.credit.add')
          </a>
        @endif
        <div class="ui vertical menu">
          @if ($ranks->count() > 0)
            <a data-menu="ranks" class="{{ (!$categorySelected ? 'active' : '') }} yellow item">
              @lang('shop.ranks')
              <div class="ui {{ (!$categorySelected ? 'yellow left pointing' : '') }} label">{{ $ranks->count() }}</div>
            </a>
          @endif
          @foreach ($categories as $category)
            <a data-menu="{{ $category->id }}" class="{{ ($categorySelected && $categorySelected == $category->id ? 'active' : '') }} yellow item">
              {{ $category->name }}
              <div class="ui {{ ($categorySelected && $categorySelected == $category->id ? 'yellow left pointing' : '') }} label">{{ $category->items->count() }}</div>
            </a>
          @endforeach
        </div>
      </div>

      <div class="ui thirteen wide column">

        <div data-menu="ranks" class="{{ (!$categorySelected ? 'active' : '') }}">
          <table class="ui celled table ranks">
            <thead>
              <tr class="center aligned">
                @foreach ($ranks as $rank)
                  <th>
                    {{ $rank->item->name }}
                    <span>@lang('shop.rank.price', ['price' => $rank->item->price])</span>
                  </th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @for ($i=0; $i < (!empty($ranks) && isset($ranks[0]) ? count($ranks[0]->advantages) : 0); $i++)
                <tr class="center aligned">
                  @foreach ($ranks as $rank)
                    <td>
                      @if ($rank->advantages[$i]['value'] === true)
                        <i class="check circle icon" style="font-size:20px;color:#14ab61"></i>
                      @elseif ($rank->advantages[$i]['value'] === false)
                        <i class="check circle icon" style="font-size:20px;color:#777"></i>
                      @else
                        {!! $rank->advantages[$i]['value'] !!}
                      @endif
                      <span>{{ $rank->advantages[$i]['name'] }}</span>
                    </td>
                  @endforeach
                </tr>
              @endfor
              <tr class="center aligned">
                @foreach ($ranks as $rank)
                  <td>
                    <button data-rank-slug="{{ $rank->slug }}" data-item="{{ json_encode($rank->toArray()) }}" class="ui yellow button rank-buy">
                      @lang('shop.buy')
                    </button>
                  </td>
                @endforeach
              </tr>
            </tbody>
          </table>
        </div>

        @foreach ($categories as $category)
          <div data-menu="{{ $category->id }}" {{ ($categorySelected && $categorySelected == $category->id ? 'class=active' : '') }}>

            <div class="ui special cards">
              @foreach ($category->items as $item)
                @php
                  $item->category = $category->toArray();
                @endphp
                <div class="card" data-item-id="{{ $item->id }}">
                  <div class="blurring dimmable image">
                    <div class="ui inverted dimmer">
                      <div class="content">
                        <div class="center">
                          <div data-item="{{ json_encode($item->toArray()) }}" class="ui yellow button item-infos">@lang('shop.item.show')</div>
                        </div>
                      </div>
                    </div>
                    <img src="{{ $item->image_path }}">
                  </div>
                  <div class="content text-center">
                    <span class="header">{{ $item->name }}</span>
                  </div>
                  <div class="extra content">
                    @if (!empty($mostPurchasedItems) && isset($mostPurchasedItems[0]) && $mostPurchasedItems[0]->item->id === $item->id)
                      <span class="right floated">
                        <i class="trophy icon" style="color:#ffd700"></i> @lang('shop.purchases.first')
                      </span>
                    @elseif (!empty($mostPurchasedItems) && isset($mostPurchasedItems[1]) && $mostPurchasedItems[1]->item->id === $item->id)
                      <span class="right floated">
                        <i class="trophy icon" style="color:#cd7f32"></i> @lang('shop.purchases.second')
                      </span>
                    @elseif (!empty($mostPurchasedItems) && isset($mostPurchasedItems[2]) && $mostPurchasedItems[2]->item->id === $item->id)
                      <span class="right floated">
                        <i class="trophy icon" style="color:#C0C0C0"></i> @lang('shop.purchases.third')
                      </span>
                    @endif
                    <span>
                      <i class="dollar icon"></i>
                      @if ($item->price >= 95)
                        <i class="dollar icon" style="margin-left: -14px;"></i>
                      @endif
                      @if ($item->price >= 250)
                        <i class="dollar icon" style="margin-left: -14px;"></i>
                      @endif
                      <span class="price">{{ $item->price }}</span> points
                    </span>
                  </div>
                </div>
              @endforeach
            </div>

          </div>
        @endforeach

      </div>

    </div>

  </div>

<div class="ui basic modal" id="rankInfosModal">
  <div class="ui stackable grid">
    <div class="ui four wide column mobile-hide" id="rankTableContent">
    </div>
    <div class="ui twelve wide column">
      <div class="ui card" style="width:100%">
        <div class="content">
          <div class="header">
          </div>
          <div class="meta">
            @lang('shop.ranks')
          </div>
          <div class="description">
          </div>
          <div class="ui divider"></div>
          <div class="ui info message">
            <div class="header">
              @lang('global.info')
            </div>
            <p>@lang('shop.rank.info')</p>
          </div>
        </div>
        <div class="extra content">
          <div class="ui stackable grid">
            <div class="ui five wide column">
              <div class="ui basic green animated fluid button buy">
                <div class="visible content">
                  <i class="shopping basket icon"></i>
                  @lang('shop.buy.more')
                </div>
                <div class="hidden content">
                  <i class="shopping basket icon"></i>
                  @lang('shop.buy.action')
                </div>
              </div>
            </div>
            <div class="ui eleven wide column buy-message">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
<div class="ui basic modal" id="itemInfosModal">
  <div class="ui stackable grid">
    <div class="ui five wide column" style="padding-right:0px;">
      <div class="ui cards" id="itemContent">

      </div>
    </div>
    <div class="ui eleven wide column" style="padding-left:0px;">
      <div class="ui card" style="width:100%;border-top-left-radius:0px;border-bottom-left-radius:0px;">
        <div class="content">
          <div class="header">
          </div>
          <div class="meta">
          </div>
          <div class="description">
          </div>
          <div class="ui divider"></div>
          <div class="ui form segment">
            <div class="field">
              <label for="number">@lang('shop.item.how')</label>
              <div class="ui spinner input" id="quantity">
                <input type="text" name="number" value="1" />
                <div class="ui vertical buttons">
                  <button type="button" class="ui spinner up icon button">
                    <i class="chevron up icon"></i>
                  </button>
                  <button type="button" class="ui spinner down icon button">
                    <i class="chevron down icon"></i>
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="extra content">
          <div class="ui stackable grid">
            <div class="ui five wide column">
              <div class="ui basic green animated fluid button buy">
                <div class="visible content">
                  <i class="shopping basket icon"></i>
                  @lang('shop.buy.more')
                </div>
                <div class="hidden content">
                  <i class="shopping basket icon"></i>
                  @lang('shop.buy.action')
                </div>
              </div>
            </div>
            <div class="ui eleven wide column buy-message">
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
@section('script')
  <script type="text/javascript" src="{{ url('/js/spinner.jquery.js') }}"></script>
  <script type="text/javascript">
    $('a[data-menu]').on('click', function () {
      var btn = $(this)
      var menuName = btn.attr('data-menu')
      var menuContent = $('div[data-menu="' + menuName + '"]')

      $('div[data-menu].active').transition({
        animation: 'fade left',
        onComplete: function() {
          menuContent.transition('fade right').addClass('active')
        }
      }).removeClass('active')
      $('a[data-menu].active').find('.label').removeClass('yellow left pointing')
      $('a[data-menu].active').removeClass('active')

      btn.addClass('active')
      btn.find('.label').addClass('yellow left pointing')
    })
    $(document).ready(function () {
      $('.special.cards .image').dimmer({
        on: 'hover'
      })
      @if ($itemSelected)
        $('.card[data-item-id="{{ $itemSelected }}"] .item-infos').click()
      @endif
      @if ($rankSelected)
        $('.rank-buy[data-rank-slug="{{ $rankSelected }}"]').click()
      @endif
    })

    var itemData = {}
    $('.rank-buy').on('click', function () {
      var btn = $(this)
      data = JSON.parse(btn.attr('data-item'))
      itemData = data.item
      itemData.quantity = 1
      itemData.type = 'rank'
      itemData.rank_slug = data.slug

      // Add table
      var table = ''
      table += '<table class="ui celled table ranks" style="border:none;">'
        table += '<thead>'
          table += '<tr class="center aligned">'
            table += '<th>'
              table += itemData.name
              table += '<span>' + '{{ __('shop.rank.price') }}'.replace(':price', itemData.price) + '</span>'
            table += '</th>'
          table += '</tr>'
        table += '</thead>'
        table += '<tbody>'
          for (var i = 0; i < data.advantages.length; i++) {
            table += '<tr class="center aligned">'
              table += '<td>'
                if (data.advantages[i].value === true)
                  table += '<i class="check circle icon" style="font-size:20px;color:#14ab61"></i>'
                else if (data.advantages[i].value === false)
                  table += '<i class="check circle icon" style="font-size:20px;color:#777"></i>'
                else
                  table += data.advantages[i].value
                table += '<span>' + data.advantages[i].name + '</span>'
              table += '</td>'
            table += '</tr>'
          }
        table += '</tbody>'
      table += '</table>'
      $('#rankTableContent').html(table)

      // data
      $('#rankInfosModal .ui.card .content>.header').html(itemData.name)
      $('#rankInfosModal .ui.card .description').html('<b>{{ __('shop.item.description') }}</b><br><br>' + itemData.description)
      $('#rankInfosModal .ui.card .extra.content .animated.button .hidden.content').html($('#rankInfosModal .ui.card .extra.content .animated.button .hidden.content').html().replace(':amount', itemData.price))
      $('#rankInfosModal .ui.card .extra.content .animated.button .hidden.content .price').html(itemData.price)

      // Remove ajax
      $('.buy-message').html('')
      $('.buy').removeClass('loading disabled')

      // Toggle modal
      $('#rankInfosModal').modal({blurring: true}).modal('show')
    })

    $('.item-infos').on('click', function () {
      var btn = $(this)
      itemData = JSON.parse(btn.attr('data-item'))
      itemData.quantity = 1
      itemData.type = 'item'

      // Add card
      $('#itemContent').html('<div class="card" style="box-shadow: none;">' + $('.card[data-item-id="' + itemData.id + '"]').html() + '</div>')
      $('#itemContent .card .ui.inverted.dimmer').remove()
      $('#itemContent .card .blurring.dimmable.image').removeClass('blurring dimmable')
      $('#itemContent .card>.content.text-center').remove()

      // Data
      $('#itemInfosModal .ui.card .content>.header').html(itemData.name)
      $('#itemInfosModal .ui.card .content>.meta').html(itemData.category.name)
      $('#itemInfosModal .ui.card .description').html('<b>{{ __('shop.item.description') }}</b><br><br>' + itemData.description)
      $('#itemInfosModal .ui.card .extra.content .animated.button .hidden.content').html($('#itemInfosModal .ui.card .extra.content .animated.button .hidden.content').html().replace(':amount', itemData.price))

      // Remove ajax
      $('.buy-message').html('')
      $('.buy').removeClass('loading disabled')

      // Toggle modal
      $('#itemInfosModal').modal({blurring: true}).modal('show')
    })
    $('#itemInfosModal #quantity').on('changed.fu.spinbox', function () {
      var input = $(this).find('input')
      var quantity = parseInt(input.val())
      var price = itemData.price
      itemData.quantity = quantity

      $('#itemInfosModal .price').html(quantity * price)
    })

    $('.buy').on('click', function (e) {
      e.preventDefault()
      var btn = $(this)
      var item = {
        id: itemData.id,
        quantity: itemData.quantity
      }
      btn.addClass('loading disabled')

      $.ajax({
        url: '{{ url('/shop/buy') }}',
        method: 'post',
        data: JSON.stringify({item: item}),
        dataType: 'json',
        contentType: 'application/json',
        success: function (data) {
          if (data.status)
            display('success', data.success)
          else
            display('error', data.error)
        },
        statusCode: {
          400: function () {
            display('error', '{{ __('form.error.badrequest') }}')
          },
          403: function () {
            display('error', '{{ __('form.error.forbidden') }}')
          },
          401: function () {
            window.location = '{{ url('/login') }}?from=' + ((itemData.type === 'rank') ? ('/shop/rank/' + itemData.rank_slug) : ('/shop/item/' + itemData.id))
          }
        },
        error: function () {
          display('error', '{{ __('form.error.internal') }}')
        }
      })

      function display(type, message) {
        btn.parent().parent().find('.buy-message').html('<div class="ui ' + type +' message"><div class="header">' + localization[type].title + '</div><div class="content">' + message +'</div></div>')
        btn.removeClass('loading disabled')
      }
    })
  </script>
@endsection
@section('style')
  <style media="screen">
    table.table.ranks thead tr th {
      background: #ec9422;

      font-size: 25px;
      color: #fefefe;
      text-transform: uppercase;
    }
    table.table.ranks thead tr th span {
      display: block;

      font-size: 16px;
      color: #e0dede;
      font-weight: 300;
    }
    table.table.ranks tbody tr td span {
      text-align: center;

      display: block;
    }

    .ui.vertical.menu {
      width: 100%;
    }

    .colored-block.divider-shop {
      padding-top: 5px;
      padding-bottom: 5px;
      margin-bottom: 50px;
      margin-top: 5px;
    }
    .colored-block.divider-shop .page-content {
      text-align: center;
    }
    .colored-block.divider-shop h1.ui.header {
      display: inline-block;
    }

    .items.best-sales .item .image img {
      width: 100px;
    }
    .items.best-sales .item .image {
      width: auto!important;
    }

    .white-block {
      padding-bottom: 0px;
    }
    .white-block .page-content {
      margin-bottom: 0px;
    }

    div[data-menu]:not(.active) {
      display: none;
    }
  </style>
@endsection
