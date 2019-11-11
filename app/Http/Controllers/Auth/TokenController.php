<?php

namespace App\Http\Controllers\Auth;
use Carbon\Carbon;
use Hash;
use App\User;
use App\Traits\OauthToken;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class TokenController extends Controller
{
    use OauthToken;
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }
    public function token(){
        return $this->clientCredentialsGrantToken();
    }

    public function accessToken(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $email = $request->input('email');
        $password = $request->input('password');
        $user = User::where('email',$email)->first();
        if($user){
            if($user->version==0 ){
                if(md5($password) == $user->getAuthPassword()){
                    User::where('id',$user->id)->update(['password'=>bcrypt($password),'version'=>2]);
                }else{
                   return $this->validateErrorResponse([trans('auth.invalid_credential')]);
                }
            }
            if(Hash::check($request->input('password'), $user->getAuthPassword())){
                $user->touch();
            }
        }
        $start_time = microtime();
        $response = $this->passwordGrantToken($request);
        $end_time = microtime();
        if(isset($response['error'])){
            return $this->failedResponse(['message'=>[trans('auth.invalid_credential')]]);
        }
        $response['verified']= $user && $user->mail_verified_at ? 1 : 0;
        $response['is_socialite'] = $user ? $user->is_socialite : 0;
        return $this->successResponse($response);
    }
    public function refreshAccessToken(Request $request)
    {
        $user = $request->user();
        $validator = $this->refreshValidator($request->all());
        if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $verified_request = clone $request;
        $response = $this->refreshGrantToken($request);

        if(isset($response['error'])){
            return $this->failedResponse(['message'=>[trans('auth.refresh_token_invalid')]]);
        }
        
        $isVerified = $this->isVerified($verified_request, $response['access_token']);
        $response['verified']= $isVerified;

        return $this->successResponse($response);
    }
    public function isLogin(Request $request)
    {
        $user = $request->user();
        if($user){
            return $this->successResponse(['login success']);
        }
        return $this->successResponse(['login failed']);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|exists:users',
            'password' => 'required|string|min:6',
        ]);
    }
    protected function refreshValidator(array $data)
    {
        return Validator::make($data, [
            'refresh_token' => 'required',
        ]);
    }

    protected function isVerified($request, $access_token){
       /* $tokenRequest = $request->create(
            env('APP_URL').'/auth/verified/check',
            'get'
        );
        $tokenRequest->headers->set('Accept','application/json');
        $tokenRequest->headers->set('Authorization','Bearer '.$access_token);
        $instance = Route::dispatch($tokenRequest);

        $response_data = json_decode($instance->getContent(), true);*/
        $http = new \GuzzleHttp\Client;
        $instance = $http->get(url('auth/verified/check'), [
            'headers'=>[
                    'Accept' => 'application/json',
                    'Authorization' => 'Bearer '.$access_token
                ],
        ]);

        $response_data = json_decode((string) $instance->getBody(), true);
        if(isset($response_data['error'])){
            return '0';
        }
        return $response_data['data']['verified'];
    }
}
