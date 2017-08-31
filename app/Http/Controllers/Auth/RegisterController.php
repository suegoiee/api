<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Traits\OauthToken;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
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
            return $this->failedResponse(['message'=>$validator->errors()->all()]);
        }
        $profileValidator = $this->profileValidator($request->all());
        if ($profileValidator->fails()) {
            return $this->failedResponse(['message'=>$profileValidator->errors()->all()]);
        }
        event(new Registered($user = $this->create($request->all())));
        
        return $this->registered($request,$user);
    }

    protected function create(array $data)
    {
        return User::create([
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }

    protected function registered(Request $request,$user)
    {
        $token = $this->passwordGrantToken($request);
        $token['user'] = $user;
        $token['profile'] = $this->createProfile($request,$user);
        return $this->successResponse($token);
    }

    protected function createProfile(Request $request,$user){
        $store_data = $request->only(['nike_name','name','sex','address','birthday']);
        $profile = $user->profile()->create($store_data);
        return $profile;
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            //'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    protected function profileValidator(array $data)
    {
        return Validator::make($data, [
            'nike_name' => 'required|max:255',
            'name' => 'max:255',
            'sex'=>'in:F,M',
            'address'=>'max:255',
            'birthday'=>'date',
        ]);
    }
}
