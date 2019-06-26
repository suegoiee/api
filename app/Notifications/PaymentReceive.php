<?php

namespace App\Notifications;

use App\Repositories\ProductRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PaymentReceive extends Notification implements ShouldQueue
{
    use Queueable;

    protected $order_no;
    protected $payment_date;
    protected $user;
    protected $notification_types;
    protected $notificationMessage;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $order_no, $payment_date)
    {
        $this->order_no = $order_no;
        $this->payment_date = $payment_date;
        $this->user = $user;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        
        $data = [
            'header_pic' => 'PaymentReceive_header.jpg',
            'nickname' => $this->user->profile ? $this->user->profile->nickname.' ' : '',
            'payment_date' => $this->payment_date,
            'order_no' => $this->order_no,
            'is_pic'=>true
        ];
        return (new MailMessage)
            ->subject(env('APP_NAME').' 付款確認通知')
            ->from(env('APP_EMAIL','no-reply@localhost'),env('APP_SYSTEM_NAME','Service'))
            ->view('emails.receivePayment', $data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $content = '付款確認通知';
        $content .= '感謝您訂購優分析商品，我們已於('.$this->payment_date.')收到您的付款資料(訂單編號'.$this->order_no.')
系統會自動開通您購買策略，<a href="'.env('APP_FRONT_URL','https://pro.uanalyze.com.tw').'" target="_blank">登入帳號</a>即可立即使用。';
        return [
            'title'=>'付款確認通知',
            'content'=>$content
        ];
    }
}
