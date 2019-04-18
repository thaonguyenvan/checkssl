<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class SendNotiDomain extends Mailable
{
    use Queueable, SerializesModels;
    public $domain_expired;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($domain_expired)
    {
        $this->domain_expired = $domain_expired;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SupportDao: Domain của bạn sắp hết hạn!')->view('emails.emailnotidomain')->with('domain_expired', $this->domain_expired);
    }
}
