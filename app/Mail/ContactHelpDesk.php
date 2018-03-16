<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class ContactHelpDesk extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $senderName;

    public $senderEmail;

    public $inquiry;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($data)
    {
        $this->senderName = $data['name'];

        $this->senderEmail = $data['email'];

        $this->inquiry = $data['inquiry'];

        $this->to(setting('contact_help_desk_email', 'email@example.com'));

        $this->replyTo($this->senderEmail, $this->senderName);

        $this->subject('Inquiry from - ' . $this->senderEmail);
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->markdown('emails.inquiry');
    }
}
