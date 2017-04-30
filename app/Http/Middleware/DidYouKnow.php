<?php

namespace App\Http\Middleware;

use Closure;

class DidYouKnow
{
    /**
     * Generate a did you know sentence for display on page
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
      $findDidYouKnow = \App\DidYouKnow::get();
      if (!$findDidYouKnow || empty($findDidYouKnow) || count($findDidYouKnow) === 0) return $next($request);

      $randomNumber = rand(0, count($findDidYouKnow)-1);
      \Illuminate\Support\Facades\View::share('didYouKnow', $findDidYouKnow[$randomNumber]->text);

      return $next($request);
    }
}
