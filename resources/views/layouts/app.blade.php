<html lang="{{ config('app.locale') }}">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="{{ __('global.meta.description') }}">
      <meta name="csrf-token" content="{{ csrf_token() }}">

      <title>@yield('title') - ObsiFight</title>

      <link rel="stylesheet" href="{{ url('/css/semantic.min.css') }}">
      <link rel="stylesheet" href="{{ url('/css/custom-responsive.css') }}">
      <link rel="stylesheet" href="{{ url('/css/app.css') }}">
      @yield('style')

      <link rel="stylesheet" href="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.css">
      <script type="text/javascript" src="{{ url('/js/jquery-3.2.1.min.js') }}"></script>

      <link rel="icon" type="image/png" href="{{ url('/img/favicon.png') }}" />
    </head>
    <body class="front">
      <div class="pusher">
        @include('layouts.header')

        <div class="container">
          @yield('content')
        </div>

        @include('layouts.footer')
      </div>
        <script type="text/javascript" src="{{ url('/js/semantic.min.js') }}"></script>
        <script type="text/javascript">
          var localization = {
            loading: "@lang('form.loading')",
            error: {
              title: "@lang('form.error.title')",
              internal: "@lang('form.error.internal')",
              notfound: "@lang('form.error.notfound')",
              forbidden: "@lang('form.error.forbidden')",
              badrequest: "@lang('form.error.badrequest')",
              methodnotallowed: "@lang('form.error.methodnotallowed')"
            },
            success: {
              title: "@lang('form.success.title')"
            }
          }
        </script>
        <script type="text/javascript" src="//cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>
        <script type="text/javascript">
          @if (session('flash.success'))
            toastr.success("{!! addslashes(session('flash.success')) !!}")
          @endif
          @if (session('flash.error'))
            toastr.error("{!! addslashes(session('flash.error')) !!}")
          @endif
          @if (session('flash.warning'))
            toastr.warning("{!! addslashes(session('flash.warning')) !!}")
          @endif
          @if (session('flash.info'))
            toastr.info("{!! addslashes(session('flash.info')) !!}")
          @endif
        </script>
        <script type="text/javascript" src="{{ url('/js/app.js') }}"></script>
        <script type="text/javascript" src="{{ url('/js/form.js') }}"></script>
        @yield('script')
    </body>
</html>
