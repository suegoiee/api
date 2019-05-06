<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Notifications\VerifyMailSend;
use App\Mail\VerifyMail;
use App\Verify_user;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\Request; 
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Uanalyze\Mitake\Facades\Mitake;
use App\Events\UserVerified;

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
        /**
         * Todo: Table user.mail_verified_at 命名變更為 verified_at
         *       Table verify_user.email 命名變更 （配合未來Mobile裝置驗證)
         */
        if(Verify_user::where('email',$request->input('phone'))->where('token', $request->input('token'))->count()>0){
            User::where('phone', $request->input('phone'))->update(['mail_verified_at'=>date('Y-m-d H:i:s')]);

            return $this->successResponse(['message'=>['The phone number has been verified']]);
        }
   
        if(Verify_user::where('email',$request->input('email'))->where('token', $request->input('token'))->count()>0){
            User::where('email', $request->input('email'))->update(['mail_verified_at'=>date('Y-m-d H:i:s')]);
            event(new UserVerified($request->input('email')));
        }
       
        return redirect(env('APP_FRONT_URL'));
    }
    public function confirmByForum(Request $request)
    {
        User::where('email', $request->input('email'))->update(['mail_verified_at'=>date('Y-m-d H:i:s')]);
       
        return $this->sendWasVerifiedResponse();
    }
    public function sendVerifyEmail(Request $request)
    {
        /**
         * Todo: Table user.mail_verified_at 命名變更為 verified_at
         */
        $user = $request->user();
        if($user->mail_verified_at){
            return $this->successResponse(['message'=>['The email was verified']]);
        }
        $verify = Verify_user::create(['email'=>$user->email,'token'=>md5(rand(1, 10) . microtime())]);
        $user->notify((new VerifyMailSend($user, $verify->token))->onQueue("verify"));
        //$response = Mail::to($user->email)->send(new VerifyMail($user, $verify->token));

        return $this->sendVerifyResponse();
    }
    public function sendVerificationCode(Request $request)
    {
        /**
         * Todo: Table user.mail_verified_at 命名變更為 verified_at
         */
        $user = $request->user();
        if ($user->mail_verified_at) {
            return $this->successResponse(['message'=>['The phone number was verified']]);
        }
        $verificationCode = str_pad(rand(0, 9999), 4, "0", STR_PAD_LEFT);
        $verify = Verify_user::create(['email'=>$user->phone,'token'=> $verificationCode]);
        Mitake::send($user->phone, '【優分析】您的驗證碼為:'.$verificationCode);

        return $this->successResponse(['message'=>['The verification code has been send'], 'sent'=>1]);
    }
    protected function sendVerifyResponse()
    {	
	   return $this->successResponse(['message'=>['Verify email has been sent'], 'sent'=>1]);
    }
    protected function sendWasVerifiedResponse()
    {   
       return $this->successResponse(['message'=>['The email has verified'], 'sent'=>2]);
    }

    protected function sendVerifyFailedResponse()
    {
	   return $this->successResponse(['message'=>['Email error'], 'sent'=>0]);
    }
}
