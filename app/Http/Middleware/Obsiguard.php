<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;

class Obsiguard
{
    /**
     * Check ObsiGuard security code
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      if (!$request->session()->has('user.obsiguard.security.code')) {
        return $this->__stopRequest($request); // generate new token & send mail
      } else {
        // Check if valid
        $token = \App\UsersToken::where('user_id', Auth::user()->id)->where('token', $request->session()->get('user.obsiguard.security.code'))->first();
        if (count($token) <= 0)
          return $this->__stopRequest($request); // generate new token & send mail
      }

      return $next($request);
    }

    private function __stopRequest($request)
    {
      // Generate token and save it
      $code = str_random(5);
      $token = \App\UsersToken::generate('OBSIGUARD', Auth::user()->id, $code);

      // send mail
      Mail::to(Auth::user()->email)->send(new \App\Mail\ObsiguardToken(Auth::user(), $code));

      // Stop request
      return response()->json([
        'status' => true,
        'success' => '',
        'obsiguard' => false
      ]);
    }
}
