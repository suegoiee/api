<?php

namespace App\Listeners;
use Storage;
use App\Events\UserVerified;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
class UserVerifiedListener
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
    public function handle(UserVerified $event)
    {
        $email = $event->email;

        $http = new \GuzzleHttp\Client;
        if(env('UA_FORUM_USER_VERIFIED_API_URL','')!=''){
            $data = [
                'email' => $email
            ];
            $response = $http->request('post',env('UA_FORUM_USER_VERIFIED_API_URL'),[
                'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer ',
                ],
                'form_params' => $data
            ]);
        }
    }
}
