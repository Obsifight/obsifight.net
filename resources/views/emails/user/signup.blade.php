@extends('layouts.email')

@section('content')
  <p>Bienvenue à toi <strong>{{ $user->username }}</strong> !</p>
  <p></p>
  <p>Nous te remercions de rejoindre notre serveur ! Avant de commencer à jouer il est préférable que tu confirmes cet email. Il te suffit pour cela de cliquer sur le lien ci-dessous</p>
  <p style="text-align:center;"><a href="{{ $url }}">{{ $url }}</a></p>
@endsection
