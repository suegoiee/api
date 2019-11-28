<?php

namespace Shouwda\Google;

use Google_Client as GoogleSDK;
use Google_Service_Oauth2 as GoogleServiceOauth2;
use Illuminate\Support\ServiceProvider;

class Google
{
 	protected $google;

    public function __construct()
    {
        $this->google = new GoogleSDK();
        $this->google->setClientId(config('google.GoogleClientId'));
        $this->google->setClientSecret(config('google.GoogleAppSecret'));
        //$this->google->addScope([Google_Service_Oauth2::USERINFO_EMAIL, Google_Service_Oauth2::USERINFO_PROFILE]);
    }
    public function getUser($id_token, $access_token)
    {
        try {
            $payload = $this->google->verifyIdToken($id_token);
            if ($payload) {
                $this->google->setAccessToken($access_token);
                $oauth = new GoogleServiceOauth2($this->google);
                $profile = $oauth->userinfo->get();
                return ['id'=>$profile->id, 'name'=> $profile->name, 'email'=>$profile->email];
            } else {
                return false;
            }
        }catch (Exception $e) {
            return false;
        }
    }
}