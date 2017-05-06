@extends('layouts.email')

@section('content')
  <p>@lang('user.signup.email.title', ['username' => $user->username])</p>
  <p></p>
  <p>@lang('user.signup.email.content')</p>
  <p style="text-align:center;"><a href="{{ $url }}">{{ $url }}</a></p>
@endsection
