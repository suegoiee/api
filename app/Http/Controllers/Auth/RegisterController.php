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
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }
        $profileValidator = $this->profileValidator($request->all());
        if ($profileValidator->fails()) {
            return $this->validateErrorResponse($profileValidator->errors()->all());
        }
        event(new Registered($user = $this->create($request->all())));
        return $this->registered($request,$user);
    }

    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'phone' => $data['phone'],
        ]);
    }

    protected function registered(Request $request, $user)
    {
        $adminToken = $this->clientCredentialsGrantToken($request);
        event(new UserRegistered($user, $adminToken));
        $token = $this->passwordGrantToken($request);
        //$token['user'] = $user;
        $token['verified'] = $user->mail_verified_at ? 1 : 0;
        $this->createProfile($request,$user);
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
            'phone' => 'digits:10',
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
