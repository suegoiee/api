<?php

namespace App\Http\Controllers\Auth;

use Hash;
use App\User;
use Facebook;
use App\Traits\OauthToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class FacebookController extends Controller
{
	use OauthToken;
    public function __construct()
    {
    }

    public function login(Request $request)
    {

    	$validator = $this->validator($request->all());
        if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $user = User::where('is_socialite',1)->where('email',$request->input('email'))->first();
        if( $user ){
        	if(Hash::check($request->input('password'), $user->getAuthPassword())){
        		
        		return $this->logined($request,$user);
        	}else{
        		return $this->validateErrorResponse([trans('auth.facebook_error')]);
        	}
        }else{
        	$user = $this->create($request->all());
        	return $this->registered($request,$user);
        }
    }
    public function register(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }

        event(new Registered($user = $this->create($request->all())));
        
        return $this->registered($request,$user);
    }

    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'is_socialite' => '1',
        ]);
    }

    protected function registered(Request $request,$user)
    {
        $adminToken = $this->clientCredentialsGrantToken();
        event(new UserRegistered($user, $adminToken));
        $token = $this->passwordGrantToken($request);
        $token['user'] = $user;
        $token['profile'] = $this->createProfile($request,$user);
        return $this->successResponse($token);
    }
    protected function logined(Request $request,$user)
    {
        $token = $this->passwordGrantToken($request);
        $token['user'] = $user;
        $token['profile'] = $this->updateProfile($request,$user);
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
            'email' => 'required|string|email|max:255',
            'password' => 'required|string|min:6',
            'nickname' => 'required|max:255',
            'name' => 'max:255',
            'sex'=>'in:F,M',
        ]);
    }
}
