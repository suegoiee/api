<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class VerifyEmailMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $user = $request->user();
        if($user && !$user->mail_verified_at){
            return response()->json(['status'=>'error','error'=>['message'=>'Email is not verified'], 'uri'=>$request->path(), 'method'=>$request->method()]);
        }
        return $next($request);
    }
}
