<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Laravel\Passport\PersonalAccessClient;

class AdminToken
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
        $token = $request->session()->get('access_token');
        $tokenRequest = $request->create(
            env('APP_URL').'/auth/login',
            'get'
        );
        $tokenRequest->request->add($request->all());
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.isset($token['access_token'])? $token['access_token']:'');
        $instance = Route::dispatch($tokenRequest);
        $response_data = json_decode($instance->getContent(), true);

        if($response_data['status']!='success'){
            $client = PersonalAccessClient::first()->client;
            $tokenRequest = $request->create(
                env('APP_URL').'/oauth/token',
                'post'
            );
            $tokenRequest->request->add([
                'grant_type' => 'client_credentials',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'scope' => 'user-product product order tag message company article promocode notificationMessage edm',
            ]);
            $instance = Route::dispatch($tokenRequest);
            $token = json_decode($instance->getContent(), true);
            $request->session()->put('access_token', $token);
        }
        return $next($request);
    }
}
