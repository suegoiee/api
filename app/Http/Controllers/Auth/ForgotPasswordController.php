<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Support\Facades\Password;

class ForgotPasswordController extends Controller
{
    
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }
    
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    public function sendResetLinkEmail(Request $request)
    {
        $validator = $this->validator($request->all());
        if ($validator->fails()) {
            return $this->validateErrorResponse($validator->errors()->all());
        }
        //$redirect = $request->input(['redirect']);
        $response = $this->broker()->sendResetLink(
            $request->only(['email'])
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
    protected function sendResetLinkResponse(Request $request, $response)
    {	
	   return $this->successResponse(['message'=>[trans($response)],'sent'=>1]);
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
	   return $this->validateErrorResponse([trans($response)]);
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => [
                'required',
                'email',
                Rule::exists('users')->where(function ($query) {
                    $query->where('is_socialite', 0);
                })
            ]);
    }

    public function broker()
    {
        return Password::broker();
    }

}
