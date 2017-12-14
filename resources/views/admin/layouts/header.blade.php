<nav class="header-navbar navbar-expand-md navbar navbar-with-menu fixed-top navbar-semi-dark navbar-shadow">
    <div class="navbar-wrapper">
        <div class="navbar-header">
            <ul class="nav navbar-nav flex-row">
                <li class="nav-item mobile-menu d-md-none mr-auto">
                    <a href="#" class="nav-link nav-menu-main menu-toggle hidden-xs"><i class="ft-menu font-large-1"></i></a>
                </li>
                <li class="nav-item mr-auto">
                    <a href="{{ url('/') }}" class="navbar-brand">
                        <img alt="stack admin logo" src="{{ url('/img/logo-min.png') }}" class="brand-logo" width="32">
                        <h2 class="brand-text">ObsiFight</h2>
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<div data-scroll-to-active="true" class="main-menu menu-fixed menu-dark menu-accordion menu-shadow">
    <div class="main-menu-content">
        <ul id="main-menu-navigation" data-menu="menu-navigation" class="navigation navigation-main">

            <li class=" navigation-header">
                <span>@lang('dashboard.general')</span><i data-toggle="tooltip" data-placement="right" data-original-title="@lang('dashboard.general')" class=" ft-minus"></i>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin') }}"><i class="fa fa-th"></i><span data-i18n="" class="menu-title">@lang('dashboard.title')</span></a>
            </li>

            <li class=" navigation-header">
                <span>@lang('global.users')</span><i data-toggle="tooltip" data-placement="right" data-original-title="@lang('global.users')" class=" ft-minus"></i>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/users') }}"><i class="fa fa-users"></i><span data-i18n="" class="menu-title">@lang('admin.nav.users.list')</span></a>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/emails/updates') }}"><i class="ft-mail"></i><span data-i18n="" class="menu-title">@lang('admin.nav.users.emails.waiting')</span></a>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/users/transfers') }}"><i class="fa fa-arrows-h"></i><span data-i18n="" class="menu-title">@lang('admin.nav.users.transfer.history')</span></a>
            </li>

            <li class=" navigation-header">
                <span>@lang('admin.nav.shop')</span><i data-toggle="tooltip" data-placement="right" data-original-title="@lang('admin.nav.shop')" class=" ft-minus"></i>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/shop/items') }}"><i class="fa fa-shopping-basket"></i><span data-i18n="" class="menu-title">@lang('admin.nav.shop.config')</span></a>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/shop/history') }}"><i class="fa fa-history"></i><span data-i18n="" class="menu-title">@lang('admin.nav.shop.history')</span></a>
            </li>

            <li class=" navigation-header">
                <span>@lang('admin.nav.stats')</span><i data-toggle="tooltip" data-placement="right" data-original-title="@lang('admin.nav.stats')" class=" ft-minus"></i>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/stats/shop') }}"><i class="fa fa-line-chart"></i><span data-i18n="" class="menu-title">@lang('admin.nav.stats.shop')</span></a>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/stats/players') }}"><i class="fa fa-area-chart"></i><span data-i18n="" class="menu-title">@lang('admin.nav.stats.players')</span></a>
            </li>

            <li class=" navigation-header">
                <span>@lang('admin.nav.infos')</span><i data-toggle="tooltip" data-placement="right" data-original-title="@lang('admin.nav.infos')" class=" ft-minus"></i>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/faq') }}"><i class="fa fa-question"></i><span data-i18n="" class="menu-title">@lang('admin.nav.faq')</span></a>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/wiki') }}"><i class="fa fa-wikipedia-w"></i><span data-i18n="" class="menu-title">@lang('admin.nav.wiki')</span></a>
            </li>

            <li class=" navigation-header">
                <span>@lang('admin.nav.utilities')</span><i data-toggle="tooltip" data-placement="right" data-original-title="@lang('admin.nav.utilities')" class=" ft-minus"></i>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/maintenance') }}"><i class="ft-alert-octagon"></i><span data-i18n="" class="menu-title">@lang('admin.nav.maintenance')</span></a>
            </li>
            <li class=" nav-item">
                <a href="{{ url('/admin/users/permissions') }}"><i class="fa fa-user-plus"></i><span data-i18n="" class="menu-title">@lang('admin.nav.users.permissions')</span></a>
            </li>

        </ul>
    </div>
</div>