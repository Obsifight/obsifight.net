@extends('layouts.email')

@section('content')
  <p>@lang('user.obsiguard.email.title', ['username' => $user->username])</p>
  <p></p>
  <p>@lang('user.obsiguard.email.content')</p>
  <p style="text-align:center;"><strong>{{ $code }}</strong></p>
@endsection
