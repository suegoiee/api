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

        // If the password was successfully reset, we will redirect the user back to
        // the application's home authenticated view. If there is an error we can
        // redirect them back to where they came from with their error message.
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
        return $request->only(
            'email', 'password', 'token'
        );
    }

    protected function resetPassword($user, $password)
    {
        $user->forceFill([
            'password' => bcrypt($password),
            'remember_token' => Str::random(60),
        ])->save();
    }

    protected function sendResetResponse(Request $request, $response='')
    {
        $token = $this->passwordGrantToken($request);
        return $this->successResponse($token, trans($response));
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
