<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class PreventBackHistory
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
/*        if(Auth::guard('customer')->guest()) {
            if ($request->ajax() || $request->wantsJson()) {
                return response('Unauthorized.', 401);
            } else {
                return redirect()->guest('/');
            }

        }*/
        Cache::flush();
        if ($request->ajax() || $request->wantsJson()) {
            return response('Unauthorized.', 401);
        }

        $response = $next($request);
        /*      if (Storage::disk('data')->exists('test.txt')){
                    Storage::disk('data')->append('test.txt', json_encode(Auth::guard('customer')));
                }else{
                    Storage::disk('data')->put('test.txt', json_encode(Auth::guard('customer')));
                }*/
        return $response->header('Cache-Control','nocache, no-store, max-age=0, must-revalidate')
            ->header('Pragma','no-cache')
            ->header('Expires','Sun, 02 Jan 1990 00:00:00 GMT');
    }
}
