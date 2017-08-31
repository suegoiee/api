<?php 
namespace App\Traits;

use Laravel\Passport\PersonalAccessClient;
trait OauthToken
{

    protected function passwordGrantToken($request){
        $http = new \GuzzleHttp\Client;
        $client = PersonalAccessClient::first()->client;
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'username' => $request->input('email'),
                'password' =>  $request->input('password'),
                'scope' =>  $request->input('scope'),
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    protected function refreshing($request){
        $http = new \GuzzleHttp\Client;
        $client = PersonalAccessClient::first()->client;
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'refresh_token' => $request->input('refresh_token'),
                'scope' =>  $request->input('scope'),
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }
}