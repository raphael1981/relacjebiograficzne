<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class RedirectifNotEmployee
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

        if(Auth::guard()->check()) {

            if(Auth::guard()->user()->permission=='employee'){
                return $next($request);
            }else{
                return redirect('/');
            }


        }else{
            return redirect('/');
        }

    }
}
