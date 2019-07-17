<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Traits\OauthToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Events\UserRegistered;
use Illuminate\Auth\Events\Registered;

class RegisterController extends Controller
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

    public function register(Request $request)
    {
        return $this->registerHandler($request);
    }
    public function mobileRegister(Request $request)
    {
        return $this->registerHandler($request, true);
    }
    protected function registerHandler($request, $mobile=false)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $profileValidator = $this->profileValidator($request->all());
        if ($profileValidator->fails()) {
            return $this->validateErrorResponse($profileValidator->errors()->all());
        }
        event(new Registered($user = $this->create($request->all())));
        return $this->registered($request, $user, $mobile);
    }

    public function registerByForum(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $profileValidator = $this->profileValidator($request->all());
        if ($profileValidator->fails()) {
            return $this->validateErrorResponse($profileValidator->errors()->all());
        }
        event(new Registered($user = $this->createByForum($request->all())));
        return $this->registered($request, $user, false, true);
    }

    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            //'name' => $data['nickname'],
            'password' => bcrypt($data['password']),
            'phone' => isset($data['phone']) ? $data['phone']:NULL,
        ]);
    }
    protected function createByForum(array $data)
    {
        return User::create([
            'email' => $data['email'],
            //'name' => $data['nickname'],
            'password' => bcrypt($data['password']),
            'phone' => isset($data['phone']) ? $data['phone']:NULL,
            'is_socialite' => isset($data['is_socialite']) ? $data['is_socialite'] : 0,
            'mail_verified_at'=> isset($data['mail_verified_at']) ? $data['mail_verified_at'] : NULL
        ]);
    }

    protected function registered(Request $request, $user, $mobile, $byForum=false)
    {
        $this->createProfile($request,$user);
        $adminToken = $this->clientCredentialsGrantToken($request);
        event(new UserRegistered($user, $adminToken, $request->input('password'), $byForum));
        $token = $this->passwordGrantToken($request, $mobile);
        //$token['user'] = $user;
        $token['verified'] = $user->mail_verified_at ? 1 : 0;
        //$token['profile'] = $this->createProfile($request,$user);
        return $this->successResponse($token);
    }


    protected function createProfile(Request $request,$user){
        /**
         * Todo: phone 欄位可能因應Mobile認證關係，須移除 (轉移至ua_pro.users存放)
         */
        $store_data = $request->only(['nickname','name','sex','address','birthday','phone','company_id','invoice_title']);
        $profile = $user->profile()->create($store_data);
        return $profile;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'digits:10|unique:users',
        ]);
    }

    protected function profileValidator(array $data)
    {
        return Validator::make($data, [
            'nickname' => 'required|max:255',
            'name' => 'max:255',
            'sex'=>'in:F,M',
            'address'=>'max:255',
            'birthday'=>'date',
        ]);
    }
}
