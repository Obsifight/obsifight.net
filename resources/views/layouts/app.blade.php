<html lang="{{ config('app.locale') }}">
    <head>
      <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1">
      <meta name="description" content="{{ __('global.meta.description') }}">

      <title>@yield('title') - ObsiFight</title>

      <link rel="stylesheet" href="{{ url('/css/semantic.min.css') }}">
      <link rel="stylesheet" href="{{ url('/css/app.css') }}">

      <script type="text/javascript" src="{{ url('/js/jquery-3.2.1.min.js') }}"></script>
    </head>
    <body>
        @include('layouts.header')

        <div class="container">
          @yield('content')
        </div>

        @include('layouts.footer')
        <script type="text/javascript" src="{{ url('/js/semantic.min.js') }}"></script>
        <script type="text/javascript" src="{{ url('/js/app.js') }}"></script>
    </body>
</html>
