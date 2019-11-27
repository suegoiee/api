<?php

namespace App\Listeners;

use Laravel\Passport\Events\AccessTokenCreated;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use DB;
use Storage;
use App\Traits\OauthToken;
class RevokeOldTokens
{

    use OauthToken;
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
     * @param  AccessTokenCreated  $event
     * @return void
     */
    public function handle(AccessTokenCreated $event)
    {
        $mobile_client = $this->getMobilePasswordGrantClient();
        $mobile_personal_client = $this->getMobilePersonalAccessClient();
        $password_client = $this->getPasswordGrantClient();
        $personal_client = $this->getPersonalAccessClient();
        $clientIds = [$event->clientId];
        if($personal_client->id == $event->clientId || $password_client->id == $event->clientId){
            $clientIds=[$personal_client->id, $password_client->id];
        }
        DB::table('oauth_access_tokens')
            ->where('id', '<>', $event->tokenId)
            ->where('user_id', $event->userId)
            ->whereIn('client_id', $clientIds)
            ->update(['revoked' => true]);
    }
}
