<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use App\Traits\ResponseFormatter;

class VerifyEmailMiddleware
{
    use ResponseFormatter;
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
            return $this->failedResponse(['message'=>['Email is not verified']]);
        }
        return $next($request);
    }
}
