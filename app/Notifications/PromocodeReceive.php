<?php

namespace App\Notifications;

use App\Repositories\PromocodeRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class PromocodeReceive extends Notification implements ShouldQueue
{
    use Queueable;

    protected $promocode_ids;
    protected $user;
    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($user, $promocode_ids = [])
    {
        $this->promocode_ids = $promocode_ids;
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
        return ['database','mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $promocodes = $this->user->promocodes()->whereIn('id', $this->promocode_ids)->get()->makeHidden(['created_at', 'updated_at']);
        $data = [
            'promocodes' => $promocodes,
            'nickname' => $this->user->profile ? $this->user->profile->nickname : ''
        ];
        return (new MailMessage)
            ->subject(env('APP_NAME').' 優惠券贈送')
            ->from(env('APP_EMAIL','no-reply@localhost'),env('APP_SYSTEM_NAME','Service'))
            ->view('emails.receivePromocode', $data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        $promocodes = $this->user->promocodes()->whereIn('id', $this->promocode_ids)->get()->makeHidden(['user_id','offer','order_id','send','created_at', 'updated_at','user']);
        return [
            'promocodes'=>$promocodes
        ];
    }
}
