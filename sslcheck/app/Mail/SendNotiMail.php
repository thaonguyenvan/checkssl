<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotiMail extends Mailable
{
    use Queueable, SerializesModels;

    public $ssl_expired;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($ssl_expired)
    {
        $this->ssl_expired = $ssl_expired;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SupportDao: SSL của bạn sắp hết hạn!')->view('emails.emailnoti')->with('ssl_expired', $this->ssl_expired);
    }
}
