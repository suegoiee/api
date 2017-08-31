<?php

namespace App\Http\Controllers\Auth;

use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
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
    public function sendResetLinkEmail(Request $request)
    {
        $this->validator($request);
        $redirect = $request->only(['redirect']);
        $response = $this->broker()->sendResetLink(
            $request->only(['email'])
        );

        return $response == Password::RESET_LINK_SENT
                    ? $this->sendResetLinkResponse($request, $response)
                    : $this->sendResetLinkFailedResponse($request, $response);
    }
    protected function sendResetLinkResponse(Request $request, $response)
    {	
	   return $this->successResponse([],trans($response));
    }

    protected function sendResetLinkFailedResponse(Request $request, $response)
    {
	   return $this->failedResponse(trans($response));
    }

    protected function validator(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email', 
            //'callback'=>'required|url'
            ]);
    }

    public function broker()
    {
        return Password::broker();
    }

}
