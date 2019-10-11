<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\URL;

class CustomerIfNoAuthRedirect
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

        if(Auth::guard()->check() || Auth::guard('customer')->check()){
            return $next($request);
        }else{
            return redirect('/autoryzacja')->with('intent_uri', $request->path());
        }

    }
}
