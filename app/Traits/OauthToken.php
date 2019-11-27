<?php 
namespace App\Traits;
use Illuminate\Support\Facades\DB;
use Laravel\Passport\PersonalAccessClient;
use Laravel\Passport\Client;
use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;

trait OauthToken
{
    protected function clientCredentialsGrantToken( $request){
        $http = new \GuzzleHttp\Client;
        $client = PersonalAccessClient::first()->client;
        $response = $http->post(url('oauth/token'), [
            'form_params' => [
                'grant_type' => 'client_credentials',
                'client_id' => $client->id,
                'client_secret' => $client->secret,
                'scope' => 'user-product product order tag message company article promocode notificationMessage edm user',
            ],
        ]);

        return json_decode((string) $response->getBody(), true);
    }

    protected function passwordGrantToken($request, $isMobile=false){
        $client = PersonalAccessClient::first()->client;
        $oauth_client = $isMobile ? $this->getMobilePasswordGrantClient() : $this->getPasswordGrantClient();
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
        $reponseData = json_decode($instance->getContent(), true);
        if(isset($reponseData['expires_in'])){
            $reponseData['expires_in'] = date('Y-m-d H:i:s',time()+$reponseData['expires_in']);
        }
        return $reponseData;
    }

    protected function refreshGrantToken($request, $isMobile=false){
        $client = PersonalAccessClient::first()->client;
        $oauth_client = $isMobile ? $this->getMobilePasswordGrantClient() : $this->getPasswordGrantClient();
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
        $reponseData = json_decode($instance->getContent(), true);
        
        $reponseData['expires_in'] = isset($reponseData['expires_in']) ? date('Y-m-d H:i:s', time() + $reponseData['expires_in']) : 0;
        return $reponseData;
    }
    
    private function getPasswordGrantClient(){
        $client = DB::table('oauth_clients')->where('password_client',1)->first();
        return $client;
    }
    private function getPersonalAccessClient(){
        $client = DB::table('oauth_clients')->where('personal_access_client',1)->first();
        return $client;
    }
    private function getMobilePasswordGrantClient(){
        $client = DB::table('oauth_clients')->where('name','uanalyze_api_mobile')->where('password_client',1)->first();
        return $client;
    }
    private function getMobilePersonalAccessClient(){
        $client = DB::table('oauth_clients')->where('name','uanalyze_api_socialite_mobile')->where('personal_access_client',1)->first();
        return $client;
    }
}

