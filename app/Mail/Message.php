<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class Message extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    protected $message;
    public function __construct($message)
    {
        $this->message = $message;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $data = [
            'type'=>'message',
            'header_pic'=> 'message_header.jpg',
            'category'=> $this->message->category,
            'name' => $this->message->name,
            'nickname' => $this->message->name,
            'email' => $this->message->email,
            'content' => $this->message->content,
            'created_at'=>$this->message->created_at,
        ];
        return $this->from('service@uanalyze.com.tw')->view('emails.message', $data);
    }
}
