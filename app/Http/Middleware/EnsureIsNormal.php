<?php

namespace App\Http\Middleware;

use Closure;
use Auth;
use App;
use URL;

class EnsureIsNormal
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (userIsNormalOrBetter()) {
          return $next($request);
        } else {
          session()->flash('message', "You do not have permission to do that action.\nConsult the network administrator for more information.");
          return redirect(URL::previous());
        }

    }
}
