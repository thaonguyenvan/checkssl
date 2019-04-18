<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Support\Facades\Mail;
use App\Mail\SendNotiMail;

class SendEmailNoti implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $ssl_expired;
    public $email;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($ssl_expired,$email)
    {
        $this->ssl_expired = $ssl_expired;
        $this->email = $email;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Mail::to($this->email)->send(new SendNotiMail($this->ssl_expired));
    }
}
