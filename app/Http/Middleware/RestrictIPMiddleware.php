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
        $restrict_ip = env('RESTRIC_IP');
        if ($restrict_ip!='' && $request->ip() != env('RESTRIC_IP')) {
            abort(401, 'Unauthorized.');
        }
        return $next($request);
    }
}
