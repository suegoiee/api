<?php

namespace App\Http\Controllers\Auth;

use Hash;
use Storage;
use App\User;
use App\Socialite;
use Shouwda\Facebook\Facebook;
use App\Traits\OauthToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Events\UserRegistered;
use Illuminate\Support\Facades\Validator;

class FacebookController extends Controller
{
	use OauthToken;
    protected $facebook;
    public function __construct()
    {
        $this->facebook = new Facebook();
    }
    public function email_exist(Request $request)
    {
        $emailvalidator = Validator::make($request->all(), [
            'email' => 'required|string|email|max:255']);
        if($emailvalidator->fails()){
            return $this->validateErrorResponse($emailvalidator->errors()->all()); 
        }
        $user = User::where('email',$request->input('email'))->first();
        if($user){
            return $this->successResponse(['message'=>['The E-mail is exists.'], 'email_exists'=>1]);
        }
        return $this->successResponse(['message'=>['The E-mail is not exists.'], 'email_exists'=>0]);
    }   
    public function login(Request $request)
    {
        $log = ['time'=>date('Y-m-d H:i:s'), 'email'=>$request->input('email',''), 'password'=>$request->input('password',''), 'encoding_password'=>bcrypt($request->input('password','')), 'nickname'=>$request->input('nickname','')];
        Storage::append('login.log', json_encode($log));
    	return $this->loginHandler($request);
    } 
    public function mobileLogin(Request $request)
    {
        return $this->loginHandler($request, true);
    }
    protected function loginHandler($request, $mobile=false)
    {
        $access_token = $request->input('access_token');
        $fb_user = $this->facebook->getUser($access_token);
        if(!$fb_user){
            return $this->validateErrorResponse([trans('auth.facebook_error')]);
        }
        $socialite = Socialite::where('provider', 'facebook')->where('provider_id', $fb_user['id'])->first();

        $socialite_data = [
            'provider'=>'facebook',
            'provider_id'=>$fb_user['id'],
            'name'=>$fb_user['name'],
            'email'=>$fb_user['email'],
            'access_token'=>$access_token,
        ];

        $request->request->add(['email'=>$socialite_data['email'],
            'provider_id'=>$socialite_data['provider_id'],
            'nickname'=>$socialite_data['name']]);

        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }

        if($socialite){
            $user = $socialite->user;
            $socialite->update([
                'email'=>$socialite_data['email'],
                'name'=>$socialite_data['name'],
                'access_token'=>$socialite_data['access_token']
            ]);
            $user->touch();
            return $this->logined($request, $user, $mobile);
        }else{
            $user = User::where('email',$socialite_data['email'])->first();
            if(!$user){
                $user = $this->create($request->all());
                $user->socialite()->create($socialite_data);
                return $this->registered($request, $user, $mobile);
            }
            $user->socialite()->create($socialite_data);
            return $this->logined($request, $user, $mobile);
        }
    }
    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'name' => $data['nickname'],
            'username'=>$data['email'],
            'confirmed'=>1,
            'bio'=>'',
            'password' => bcrypt($data['provider_id']),
            'is_socialite' => 1,
            'confirmation_code'=>'',
            'phone' => isset($data['phone']) ? $data['phone']:NULL,
            'mail_verified_at'=>date('Y-m-d H:i:s'),
            'set_password' => 0,
        ]);
    }

    protected function registered(Request $request, $user, $mobile)
    {
        $this->createProfile($request, $user);
        $adminToken = $this->clientCredentialsGrantToken($request);
        event(new UserRegistered($user, $adminToken, $request->input('password'), false));
        $client = $mobile ? $this->getMobilePasswordGrantClient() : $this->getPasswordGrantClient();
        $user_token = $user->createToken($client->name);
        $token = [
            'token_type'=>'Bearer',
            'expires_in'=>$user_token->token->expires_at,
            'access_token'=>$user_token->accessToken,
            'refresh_token'=>'',
            'verified'=>$user->mail_verified_at ? 1 : 0,
            'is_socialite'=>$user->is_socialite,
            'set_password'=> $user->set_password
        ];
        return $this->successResponse($token);
    }
    protected function logined(Request $request, $user, $mobile)
    {
        $client = $mobile ? $this->getMobilePersonalAccessClient() : $this->getPersonalAccessClient();
        $client = $this->getMobilePersonalAccessClient();
        $user_token = $user->createToken($client->name);
        $token = [
            'token_type'=>'Bearer',
            'expires_in'=>$user_token->token->expires_at,
            'access_token'=>$user_token->accessToken,
            'refresh_token'=>'',
            'verified'=>$user->mail_verified_at ? 1 : 0,
            'is_socialite'=>$user->is_socialite,
            'set_password'=> $user->version == 1 ? 0 : $user->set_password
        ];
        $this->updateProfile($request,$user);
        return $this->successResponse($token);
    }

    protected function createProfile(Request $request,$user){
        $store_data = $request->only(['nickname','name','sex','address','birthday']);
        $profile = $user->profile()->create($store_data);
        return $profile;
    }
    protected function updateProfile(Request $request,$user){
    	$profile = $request->only(['nickname','name','sex','address','birthday']);
        $user->profile()->update($profile);
        return $profile;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'access_token' => 'required|string',
        ]);
    }
}
