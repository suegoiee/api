<?php

namespace App\Notifications;

use App\Repositories\ProductRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class VerifyMailSend extends Notification implements ShouldQueue
{
    use Queueable;

    protected $user;
    protected $token;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $token)
    {
        $this->user = $user;
        $this->token = $token;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $data =['nickname'=>$this->user->profile ? $this->user->profile->nickname:'', 'email'=>urlencode($this->user->email),'token'=>$this->token];
        return (new MailMessage)->subject(env('APP_NAME').' 信箱驗證')
                ->from(env('APP_EMAIL','no-reply@uanalyze.com.tw'),env('APP_SYSTEM_NAME','Service'))
                ->markdown('emails.verifyUser', $data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $products = $this->user->products()->whereIn('id', $this->product_ids)->get()->makeHidden(['price', 'column', 'model','info_short','info_more','expiration','status','faq','created_at', 'updated_at', 'deleted_at', 'avatar_detail','pivot']);
        foreach ($products as $key => $product) {
            $product->deadline = $product->pivot->deadline;
        }
        return [
            'products'=>$products
        ];
    }
}
