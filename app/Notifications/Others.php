<?php

namespace App\Notifications;

use App\Repositories\NotificationMessageRepository;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;

class Others extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    protected $user;
    protected $notification_types;
    protected $notificationMessage;
    public function __construct($user, $notificationMessage)
    {
        $this->user = $user;
        $this->notificationMessage = $notificationMessage;
        $this->notification_types = [];
        if($this->notificationMessage->send_notice==1){
            array_push($this->notification_types, 'database');
        }
        if($this->notificationMessage->send_email){
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

        $data = [
            'content' => $this->notificationMessage->content,
            'nickname' => $this->user->profile ? $this->user->profile->nickname : ''
        ];

        return (new MailMessage)
            ->subject(env('APP_NAME').' 通知 - '.$this->notificationMessage->title)
            ->from(env('APP_EMAIL','no-reply@localhost'),env('APP_SYSTEM_NAME','Service'))
            ->view('emails.receiveMessage', $data);
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
            'title'=>$this->notificationMessage->title,
            'content' => $this->notificationMessage->content,
        ];
    }
}
