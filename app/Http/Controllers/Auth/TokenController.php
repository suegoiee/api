<?php

namespace App\Http\Controllers\Auth;

use App\Traits\OauthToken;
use Illuminate\Http\Request;
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
        return $this->successResponse($this->passwordGrantToken($request));
    }
    public function refreshAccessToken(Request $request)
    {
        $validator = $this->refreshValidator($request->all());
        if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }
        return $this->successResponse($this->refreshGrantToken($request));
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
}
