<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Lang;
use Illuminate\Notifications\Messages\MailMessage;

class SendEmailReset extends Notification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $token;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($token)
    {
        $this->token = $token;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        return (new MailMessage)
            ->subject(Lang::getFromJson('Thông báo thay đổi mật khẩu'))
            ->from('thaonv2610@gmail.com','Support Dạo')
            ->line(Lang::getFromJson('Bạn nhận được email này bởi chúng tôi nhận thấy có yêu cầu đổi mật khẩu tới email này'))
            ->action(Lang::getFromJson('Thay đổi mật khẩu'), url(config('app.url').route('password.reset', $this->token, false)))
            ->line(Lang::getFromJson('Link này sẽ có hiệu lực trong vòng :count phút.', ['count' => config('auth.passwords.users.expire')]))
            ->line(Lang::getFromJson('Nếu bạn không yêu cầu thay đổi mật khẩu, vui lòng bỏ qua.'));
    }
}
