<?php 
namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\PersonalAccessClient;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

trait OauthToken
{
    protected function clientCredentialsGrantToken($request){
        $client = PersonalAccessClient::first()->client;
        $request->request->add([
            'grant_type' => 'client_credentials',
            'client_id' => $client->id,
            'client_secret' => $client->secret,
            'scope' => 'user-product product order tag message company article promocode notificationMessage edm',
        ]);
        $tokenRequest = $request->create(
            url('/oauth/token'),
            'post'
        );
        $instance = Route::dispatch($tokenRequest);
        return json_decode($instance->getContent(), true);
    }

    protected function passwordGrantToken($request){
        /*
        $http = new \GuzzleHttp\Client;
        $client = PersonalAccessClient::first()->client;
        $oauth_client = $this->getPasswordGrantClient();
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'password',
                'client_id' => $oauth_client->id,
                'client_secret' => $oauth_client->secret,
                'username' => $request->input('email'),
                'password' =>  $request->input('password'),
                'scope' =>  '',//$request->input('scope'),
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
        */
        $client = PersonalAccessClient::first()->client;
        $oauth_client = $this->getPasswordGrantClient();
        $request->request->add([
            "grant_type" => "password",
            "username" => $request->input('email'),
            "password" => $request->input('password'),
            "client_id"     => $oauth_client->id,
            "client_secret" => $oauth_client->secret,
        ]);
        $tokenRequest = $request->create(
            env('APP_URL').'/oauth/token',
            'post'
        );
        $instance = Route::dispatch($tokenRequest);

        return json_decode($instance->getContent(), true);
    }

    protected function refreshGrantToken($request){
        /*
        $http = new \GuzzleHttp\Client;
        $client = PersonalAccessClient::first()->client;
        $oauth_client = $this->getPasswordGrantClient();
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'refresh_token',
                'client_id' =>  $oauth_client->id,
                'client_secret' => $oauth_client->secret,
                'refresh_token' => $request->input('refresh_token'),
                'scope' =>  '',//$request->input('scope'),
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
        */
        $client = PersonalAccessClient::first()->client;
        $oauth_client = $this->getPasswordGrantClient();
        $request->request->add([
            'grant_type' => 'refresh_token',
            'client_id' =>  $oauth_client->id,
            'client_secret' => $oauth_client->secret,
            'refresh_token' => $request->input('refresh_token'),
            'scope' =>  '',
        ]);
        $tokenRequest = $request->create(
            env('APP_URL').'/oauth/token',
            'post'
        );
        $instance = Route::dispatch($tokenRequest);

        return json_decode($instance->getContent(), true);
    }
    
    private function getPasswordGrantClient(){
        $client = DB::table('oauth_clients')->where('password_client',1)->first();
        return $client;
    }
}

