<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;

class VerifyAddMail extends Mailable
{
    use Queueable, SerializesModels;

    public $email_noti;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($email_noti)
    {
        $this->email_noti = $email_noti;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('SSL Check: Xác thực tài khoản')->view('emails.verifyAddMail');
    }
}
