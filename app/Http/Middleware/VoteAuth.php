<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class VoteAuth
{
    /**
     * Check if user is auth for vote
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if (!$request->session()->has('vote.user.id'))
        return response()->json(['status' => false, 'error' => __('vote.step.error.unauthorized')], 403);
      else {
        // Check user
        $user = \App\User::find($request->session()->get('vote.user.id'));
        if (!$user || empty($user))
          return response()->json(['status' => false, 'error' => __('vote.step.error.unauthorized')], 403);
        $request->user = $user;
      }
      return $next($request);
    }
}
