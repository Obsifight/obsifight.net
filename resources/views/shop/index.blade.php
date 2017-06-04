@extends('layouts.app')

@section('title', __('shop.title'))

@section('content')
  <div class="white-block">
    <div class="ui container page-content">

      <div class="text-center">
        <h2 class="ui header" style="display:inline-block">
          <i class="shop icon"></i>
          <div class="content">
            Nos meilleures ventes
            <div class="sub header">Voyez les articles préférés</div>
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
                      <div class="ui label"><i class="trophy icon" style="color:#ffd700"></i> 1<sup>ère</sup> place</div>
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
  <div class="colored-block divider-shop">
    <div class="ui container page-content">
      <h1 class="ui header">
        <i class="shopping basket icon"></i>
        <div class="content">
          Nos articles
        </div>
      </h1>
    </div>
  </div>
  <div class="ui container page-content">
    <div class="ui stackable grid">

      <div class="ui three wide column">
        <div class="ui vertical menu">
          <a data-menu="ranks" class="active yellow item">
            Grades
            <div class="ui yellow left pointing label">{{ $ranks->count() }}</div>
          </a>
          @foreach ($categories as $category)
            <a data-menu="{{ $category->id }}" class="yellow item">
              {{ $category->name }}
              <div class="ui label">{{ $category->items->count() }}</div>
            </a>
          @endforeach
        </div>
      </div>

      <div class="ui thirteen wide column">

        <div data-menu="ranks" class="active">
          <table class="ui celled table ranks">
            <thead>
              <tr class="center aligned">
                @foreach ($ranks as $rank)
                  <th>
                    {{ $rank->item->name }}
                    <span>{{ $rank->item->price }} points / mois</span>
                  </th>
                @endforeach
              </tr>
            </thead>
            <tbody>
              @for ($i=0; $i < count($ranks[0]->advantages); $i++)
                <tr class="center aligned">
                  @foreach ($ranks as $rank)
                    <td>
                      @if ($rank->advantages[$i]['value'] === true)
                        <i class="check circle icon" style="font-size:20px;color:#14ab61"></i>
                      @elseif ($rank->advantages[$i]['value'] === false)
                        <i class="check circle icon" style="font-size:20px;color:#777"></i>
                      @else
                        {{ $rank->advantages[$i]['value'] }}
                      @endif
                      <span>{{ $rank->advantages[$i]['name'] }}</span>
                    </td>
                  @endforeach
                </tr>
              @endfor
              <tr class="center aligned">
                @foreach ($ranks as $rank)
                  <td>
                    <button class="ui yellow button">
                      Acheter
                    </button>
                  </td>
                @endforeach
              </tr>
            </tbody>
          </table>
        </div>

        @foreach ($categories as $category)
          <div data-menu="{{ $category->id }}">

            <div class="ui special cards">
              @foreach ($category->items as $item)
                <div class="card">
                  <div class="blurring dimmable image">
                    <div class="ui inverted dimmer">
                      <div class="content">
                        <div class="center">
                          <div class="ui yellow button">Voir les détails</div>
                        </div>
                      </div>
                    </div>
                    <img src="{{ $item->image_path }}">
                  </div>
                  <div class="content text-center">
                    <span class="header">{{ $item->name }}</span>
                  </div>
                  <div class="extra content">
                    @if ($mostPurchasedItems[0]->item->id === $item->id)
                      <span class="right floated">
                        <i class="trophy icon" style="color:#ffd700"></i> 1<sup>ère</sup> meilleure vente
                      </span>
                    @elseif ($mostPurchasedItems[1]->item->id === $item->id)
                      <span class="right floated">
                        <i class="trophy icon" style="color:#cd7f32"></i> 2<sup>ème</sup> meilleure vente
                      </span>
                    @elseif ($mostPurchasedItems[2]->item->id === $item->id)
                      <span class="right floated">
                        <i class="trophy icon" style="color:#C0C0C0"></i> 3<sup>ème</sup> meilleure vente
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
                      {{ $item->price }} points
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
@endsection
@section('script')
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
