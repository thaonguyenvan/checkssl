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
use App\Jobs\SendEmailNoti;

class SendNoti extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:noti';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check ssl and send noti';

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
        $users = User::all();

        foreach ($users as $user){
            $ssls = $user->ssl->all();
            $tele_notis = $user->tele_noti->all();
            $email_notis = $user->email_noti->all();
            $ssl_expired = [];
            $tele_sent = 0;
            $email_sent = 0;
            foreach ($ssls as $ssl) {
                if($ssl->notification == 1){
                    if($ssl->send_noti_before >= $ssl->dayleft){
                        if(empty($ssl->last_sent_noti)){
                            $ssl_expire = ['id'=>$ssl->id,'domain'=> $ssl->domain,'dayleft'=>$ssl->dayleft];
                            array_push($ssl_expired, $ssl_expire);
                        } else {
                            // $last_sent_noti = Carbon::parse($ssl->last_sent_noti);
                            // $time_to_send_noti = $last_sent_noti->addDays($ssl->send_noti_after);
                            // $time_now = Carbon::now();
                            // if($time_now->gte($time_to_send_noti)){
                            //     $ssl_expire = ['id'=>$ssl->id,'domain'=> $ssl->domain,'dayleft'=>$certificate->daysUntilExpirationDate()];
                            //     array_push($ssl_expired, $ssl_expire);
                            // }
                            $expire_at = Carbon::parse($ssl->expire_at);
                            $time_now = Carbon::now();
                            if(($expire_at->subDays($ssl->send_noti_before - $ssl->send_noti_after))->lte($time_now)){
                                $last_sent_noti = Carbon::parse($ssl->last_sent_noti);
                                $time_to_send_noti = $last_sent_noti->addDays(1);
                                if($time_now->gte($time_to_send_noti)){
                                    $ssl_expire = ['id'=>$ssl->id,'domain'=> $ssl->domain,'dayleft'=>$ssl->dayleft];
                                    array_push($ssl_expired, $ssl_expire);
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($ssl_expired)){
                foreach ($tele_notis as $tele_noti) {
                    if($tele_noti->status == 1){
                        $text = "Những domain sau đây của bạn sắp hết hạn:\n";
                        foreach($ssl_expired as $ssl_expires) {
                            $text = $text.$ssl_expires['domain'] .' - Số ngày còn lại: <b>'.$ssl_expires['dayleft']."</b>\n";
                            $time_now = Carbon::now()->format('Y-m-d H:i:s');
                            \DB::table('ssl')->where('id',$ssl_expires['id'])->update(['last_sent_noti'=>$time_now]);
                        }
                        Telegram::sendMessage([
                            'chat_id' => $tele_noti->chat_id,
                            'parse_mode' => 'HTML',
                            'text' => $text
                        ]);
                    }

                }
                foreach ($email_notis as $email) {
                    if($email->status == 1 && $email->verified == 1){
                        // Mail::to($email->email)->send(new SendNotiMail($ssl_expired));
                        dispatch(new SendEmailNoti($ssl_expired,$email->email));
                        foreach ($ssl_expired as $ssl) {
                            $time_now = Carbon::now()->format('Y-m-d H:i:s');
                            \DB::table('ssl')->where('id',$ssl['id'])->update(['last_sent_noti'=>$time_now]);
                        }
                    }
                }
            }
        }
    }
}
