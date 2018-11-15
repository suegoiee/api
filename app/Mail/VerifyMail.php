<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyMail extends Mailable
{
    use Queueable, SerializesModels;

    protected $user;
    protected $token;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject(env('APP_NAME').' 信箱驗證')
                ->from(env('APP_EMAIL','no-reply@uanalyze.com.tw'),env('APP_SYSTEM_NAME','Service'))
                ->markdown('emails.verifyUser')
                ->with(['nickname'=>$this->user->profile ? $this->user->profile->nickname:'', 'email'=>urlencode($this->user->email),'token'=>$this->token]);
    }
}
