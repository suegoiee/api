<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Http\Request; 
use Illuminate\Support\Facades\Route;

class UserRegisteredListener
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(UserRegistered $event)
    {
        $user = $event->user; 
        $token = $event->token;
        $tokenRequest = Request::create(
            url('/user/products'),
            'post'
        );
        $tokenRequest->request->add([
            'products' => [['id'=>42,'quantity'=>0]],
            'user_id' => $user['id'],
        ]);
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.$token['access_token']);
        $instance = Route::dispatch($tokenRequest);

        return json_decode($instance->getContent(), true);
    }
}
