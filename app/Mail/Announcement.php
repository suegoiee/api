<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Announcement extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */

    protected $notificationMessage;
    public function __construct($notificationMessage)
    {
        $this->notificationMessage = $notificationMessage;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'title'=> $this->notificationMessage->title,
            'header_pic'=> 'Announcement_header.jpg',
            'content' => $this->notificationMessage->content,
            'nickname' => ''
        ];

        return $this->subject(env('APP_NAME').' 通知 - '. $this->notificationMessage->title)
                ->from(env('APP_EMAIL','no-reply@uanalyze.com.tw'), env('APP_SYSTEM_NAME','Service'))
               ->view('emails.massiveAnnouncement', $data);
    }
}
