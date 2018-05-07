<?php

namespace App\Notifications;

use App\Repositories\NotificationMessageRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class ReceiveMessage extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    protected $notificationMessage;
    public function __construct($user, $notificationMessage='')
    {
        $this->user = $user;
        $this->notificationMessage = $notificationMessage;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['database', 'mail'];
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
            'contents' => $this->notificationMessage,
            'nickname' => $this->user->profile ? $this->user->profile->nickname : ''
        ];

        return (new MailMessage)
            ->subject(env('APP_NAME').' 通知')
            ->from(env('APP_EMAIL','no-reply@localhost'),env('APP_SYSTEM_NAME','Service'))
            ->markdown('emails.receiveMessage', $data);
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'content' => $this->notificationMessage,
        ];
    }
}
