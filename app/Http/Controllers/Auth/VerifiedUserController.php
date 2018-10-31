<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Mail\VerifyMail;
use App\Verify_user;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VerifiedUserController extends Controller
{

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
       
    }
    public function verified(Request $request)
    {
        if(Verify_user::where('email',$request->input('email'))->where('token', $request->input('token'))->count()>0){
            User::where('email', $request->input('email'))->update(['mail_verified_at'=>date('Y-m-d H:i:s')]);
        }
        return redirect(env('APP_FRONT_URL'));
    }
    public function sendVerifyEmail(Request $request)
    {
        
        $user = $request->user();
        if($user->mail_verified_at){
            return $this->successResponse(['message'=>['The email was verified']]);
        }
        $verify = Verify_user::create(['email'=>$user->email,'token'=>md5(rand(1, 10) . microtime())]);
        $response = Mail::to($user->email)->send(new VerifyMail($user, $verify->token));
        return $this->sendVerifyResponse();
    }
    protected function sendVerifyResponse()
    {	
	   return $this->successResponse(['message'=>['Verify email has been sent']]);
    }
    protected function sendWasVerifiedResponse()
    {   
       return $this->successResponse(['message'=>['The email has verified']]);
    }

    protected function sendVerifyFailedResponse()
    {
	   return $this->validateErrorResponse(['Email error']);
    }
}
