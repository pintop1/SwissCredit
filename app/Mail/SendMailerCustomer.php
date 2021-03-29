<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendMailerCustomer extends Mailable
{
    use Queueable, SerializesModels;
    public $name, $content, $subject;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($name, $content, $subject)
    {
        $this->name = $name;
        $this->content = $content;
        $this->subject = $subject;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.customer')->subject($this->subject);
    }
}
