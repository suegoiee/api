<?php

namespace App\Listeners;

use App\Events\UserRegistered;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
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
        $password = $event->password;
        $token = $event->token;
        $byForum = $event->byForum;
        $http = new \GuzzleHttp\Client;
        $response = $http->request('post',url('/user/products'),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$token['access_token'],
                ],
                'form_params' => [
                    'products' => [['id'=>42,'quantity'=>0]],
                    'user_id' => $user['id'],
                ],
            ]);
        /*
        if(!$byForum && env('UA_FORUM_REGISTER_API_URL','')!=''){
            $data = [
                'name' => $user->profile->nickname,
                'email' => $user->email,
                'username' => $user->profile->nickname,
                'github_id' => 0,
                'github_username' => 'ua',
                'confirmation_code' => null,
                'is_socialite'=>$user->is_socialite,
                'confirmed'=>$user->mail_verified_at ? 1 : 0,
                'password'=> $password,
                'type' => 1,
                'remember_token' => 'Y8LWuIcjee'
            ];
            $response = $http->request('post',env('UA_FORUM_REGISTER_API_URL'),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ',
                ],
                'form_params' => $data
            ]);
        }*/
    }
}
