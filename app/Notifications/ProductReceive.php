<?php

namespace App\Notifications;

use App\Repositories\ProductRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ProductReceive extends Notification implements ShouldQueue
{
    use Queueable;

    protected $product_ids;
    protected $user;
    protected $notification_types;
    protected $notificationMessage;
    protected $quantity;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $quantity = 1, $product_ids = [], $notificationMessage)
    {
        $this->product_ids = $product_ids;
        $this->user = $user;
        $this->quantity = $quantity;
        $this->notificationMessage = $notificationMessage;
        $this->notification_types = [];
        if($this->notificationMessage->send_notice==1){
            array_push($this->notification_types, 'database');
        }
        if($this->notificationMessage->send_email==1){
            array_push($this->notification_types, 'mail');
        }
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return $this->notification_types;
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $products = $this->user->products()->whereIn('id', $this->product_ids)->get()->makeHidden(['price', 'column', 'model','info_short','info_more','expiration','status','faq','created_at', 'updated_at', 'deleted_at', 'avatar_detail','pivot']);
        foreach ($products as $key => $product) {
            $product->deadline = $product->pivot->deadline;
        }
        $data = [
            'header_pic'=> $this->notificationMessage->type.'_header.jpg',
            'products' => $products,
            'nickname' => $this->user->profile ? $this->user->profile->nickname : ''
        ];
        return (new MailMessage)
            ->subject(env('APP_NAME').' 產品贈送')
            ->from(env('APP_EMAIL','no-reply@localhost'),env('APP_SYSTEM_NAME','Service'))
            ->view('emails.receiveProducts', $data);
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
        $content = '優分析贈送了產品';
        foreach ($products as $key => $product) {
            $product->deadline = $product->pivot->deadline;
            $content.= '<a href="'.env('APP_FRONT_URL','https://pro.uanalyze.com.tw/').'e-com/product-detail/'.$product->id.'">'.$product->name.'</a>';
        }
        $content .= '給您，可以從 <a href="'.env('APP_FRONT_URL','https://pro.uanalyze.com.tw/').'profile">個人資訊</a> 的已購模組中確認。';
        return [
            'title'=>'收到贈禮',
            'content'=>$content,
            //'products'=>$products
        ];
    }
}
