<?php

namespace App\Http\Middleware;

use Closure;

class RestrictIPMiddleware
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
        if(env('RESTRICT_IP','')==''){
            return $next($request);
        }
        $restrict_ips = explode(',', env('RESTRICT_IP'));
        foreach ($restrict_ips as $key => $restrict_ip) {
            if ($request->ip() == $restrict_ip) {
                return $next($request);
            }
        }
        abort(401, 'Unauthorized.');
    }
}
