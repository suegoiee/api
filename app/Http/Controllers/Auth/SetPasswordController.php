<?php

namespace App\Http\Controllers\Auth;

use Hash;
use App\Traits\OauthToken;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Password;
use App\Http\Controllers\Controller;

class SetPasswordController extends Controller
{
    use OauthToken;
    public function __construct()
    {
        
    }

    public function set(Request $request)
    {
    	$user = $request->user();

    	$validator = $this->setValidator($request->all());
    	if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }

        if($user->is_socialite == 0){
            return $this->validateErrorResponse([trans('auth.can_not_set_password')]);
        }

        $this->setPassword($user, $request->input('password'));

        $request->merge(['email' => $user->email]);

        return $this->sendResetResponse($request);
    	
    }

    protected function setValidator(array $data)
    {
        return Validator::make($data, [
        		'password' => 'required|min:6',
            ]);
    }

    protected function setPassword($user, $password)
    {
        if($user->is_socialite == 0){
            return false;
        }
        $user->forceFill([
            'password' => bcrypt($password),
            'set_password'=>1
        ])->save();
    }

    protected function sendResetResponse(Request $request, $response='')
    {
        return $this->successResponse(['message'=>[trans('auth.password_set_success')],'set'=>1], trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response='')
    {
        return $this->failedResponse(['email'=>$request->only('email'),'message'=>[trans($response)]]);
    }
}
