<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\SslCertificate\SslCertificate;
use App\Ssl;
use App\User;
use App\Email_noti;
use App\Tele_noti;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\SendNotiMail;
use Telegram\Bot\Laravel\Facades\Telegram;

class CheckSsl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:ssl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check ssl and send notification';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $ssl = Ssl::all();

        foreach ($ssl as $s) {
            $certificate = SslCertificate::createForHostName($s->domain);
            $s->expire_at = $certificate->expirationDate()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
            $s->dayleft = $certificate->daysUntilExpirationDate();
            $s->issue_by = $certificate->getIssuer();
            $s->save();

            if($s->send_noti_before >= $certificate->daysUntilExpirationDate()){
                $id = $s->users->id;
                $data = ['domain'=>$s->domain,'dayleft'=>$s->dayleft];
                $tele_noti = Tele_noti::where('user_id',$id)->get();
                $email_noti = Email_noti::where('user_id',$id)->get();
                $tele_sent = 0;
                if($tele_noti->first()){
                    foreach ($tele_noti as $tele) {
                        if($tele->status == 1 && empty($s->last_sent_noti)){
                            Telegram::sendMessage([
                                'chat_id' => $tele->chat_id,
                                'text' => 'SSL cho domain '.$s->domain.' sắp hết hạn. Số ngày còn lại: '.$s->dayleft
                            ]);
                            $tele_sent = 1;
                        } else if($tele->status == 1 && !empty($s->last_sent_noti)){
                            $last_sent_noti = Carbon::parse($s->last_sent_noti);

                            $time_to_send_noti = $last_sent_noti->addDays($s->send_noti_after);
                            $time_now = Carbon::now();

                            if($time_now->gte($time_to_send_noti)){
                                Telegram::sendMessage([
                                    'chat_id' => $tele->chat_id,
                                    'text' => 'SSL cho domain '.$s->domain.' sắp hết hạn. Số ngày còn lại: '.$s->dayleft
                                ]);
                                $tele_sent = 1;
                            }
                        }
                    }
                }
                
                if($email_noti->first()){
                    foreach ($email_noti as $email) {
                        if($email->status == 1 && $email->verified == 1 && empty($s->last_sent_noti)){
                            Mail::to($email->email)->send(new SendNotiMail($data));
                            $s->last_sent_noti = Carbon::now()->format('Y-m-d H:i:s');
                            $s->save();
                        } elseif ($email->status == 1 && $email->verified == 1 && !empty($s->last_sent_noti)) {
                            $last_sent_noti = Carbon::parse($s->last_sent_noti);

                            $time_to_send_noti = $last_sent_noti->addDays($s->send_noti_after);
                            $time_now = Carbon::now();

                            if($time_now->gte($time_to_send_noti)){
                                Mail::to($email->email)->send(new SendNotiMail($data));
                                $s->last_sent_noti = $time_now->format('Y-m-d H:i:s');
                                $s->save();
                            }
                        } else {
                            if($tele_sent == 1){
                                $s->last_sent_noti = Carbon::now()->format('Y-m-d H:i:s');
                                $s->save();
                            }
                        }
                    }
                } else {
                    if($tele_sent == 1){
                        $s->last_sent_noti = Carbon::now()->format('Y-m-d H:i:s');
                        $s->save();
                    }
                }
            }
        }
    }
}
