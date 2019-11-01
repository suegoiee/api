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

class ResetPasswordController extends Controller
{
    use OauthToken;
    public function __construct()
    {
        
    }

    public function reset(Request $request)
    {
    	
    	$validator = $this->forgetPasswordValidator($request->all());
    	if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }

        $response = $this->broker()->reset(
            $this->credentials($request), function ($user, $password) {
                $this->resetPassword($user, $password);
            }
        );
        return $response == Password::PASSWORD_RESET
                    ? $this->sendResetResponse($request, $response)
                    : $this->sendResetFailedResponse($request, $response);
    }
    public function update(Request $request)
    {
    	$user = $request->user();

    	$validator = $this->resetValidator($request->all());
    	if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }
        if($user->is_socialite != 0){
            return $this->validateErrorResponse([trans('auth.can_not_reset_password')]);
        }
        if(Hash::check($request->input('old_password'), $user->getAuthPassword())){
    		$this->resetPassword($user, $request->input('password'));
        	$request->merge(['email' => $user->email]);
        	return $this->sendResetResponse($request);
    	}else{
    		return $this->validateErrorResponse([trans('auth.old_password_error')]);
    	}
    }

    protected function forgetPasswordValidator(array $data)
    {
        return Validator::make($data, [
        		'token' => 'required',
            	'email' => 'required|email',
            	'password' => 'required|min:6',
            ]);
    }
    protected function resetValidator(array $data)
    {
        return Validator::make($data, [
        		'old_password' => 'required|min:6',
            	'password' => 'required|min:6',
            ]);
    }

    protected function validationErrorMessages()
    {
        return [];
    }

    protected function credentials(Request $request)
    {
        $check_data=$request->only(
            'email', 'password', 'token'
        );
        $check_data['password_confirmation'] = $check_data['password'];
        return $check_data;
    }

    protected function resetPassword($user, $password)
    {
        if($user->is_socialite != 0){
            return false;
        }
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();
    }

    protected function sendResetResponse(Request $request, $response='')
    {
        //$token = $this->passwordGrantToken($request);
        return $this->successResponse(['message'=>[trans('auth.password_reset_success')],'reset'=>1], trans($response));
    }

    protected function sendResetFailedResponse(Request $request, $response='')
    {
        return $this->failedResponse(['email'=>$request->only('email'),'message'=>[trans($response)]]);
    }

    public function broker()
    {
        return Password::broker();
    }
}
