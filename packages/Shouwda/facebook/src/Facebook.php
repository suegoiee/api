<?php

namespace Shouwda\Facebook;

use Facebook\Facebook as FacebookSDK;
use Illuminate\Support\ServiceProvider;

class Facebook
{
 	protected $fb;

    public function __construct()
    {
        $this->fb = new FacebookSDK([
            'app_id' => config('facebook.FacebookAppId'),
            'app_secret' => config('facebook.FacebookAppSecret'),
            'default_graph_version' => config('facebook.FacebookGraphVersion'),
        ]);
    }
    public function login()
    {
        $helper = $this->fb->getJavaScriptHelper();

        try {
            $accessToken = $helper->getAccessToken();
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
        }

        if (! isset($accessToken)) {
          return 'No cookie set or no OAuth data could be obtained from cookie.';
        }

        return $accessToken;
    }
    public function getUser($accessToken)
    {
        try {
            $response = $this->fb->get('/me?fields=id,name,first_name,last_name,locale,gender,timezone,verified,email,link', $accessToken);
        } catch(Facebook\Exceptions\FacebookResponseException $e) {
            return 'Graph returned an error: ' . $e->getMessage();
        } catch(Facebook\Exceptions\FacebookSDKException $e) {
            return 'Facebook SDK returned an error: ' . $e->getMessage();
        }
        $user = $response->getGraphUser();
        return $user;
    }
}