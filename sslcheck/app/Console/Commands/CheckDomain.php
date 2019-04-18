<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\SslCertificate\SslCertificate;
use App\Domain;
use App\User;
use App\Email_noti;
use App\Tele_noti;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\SendNotiDomain;
use Telegram\Bot\Laravel\Facades\Telegram;
use App\Jobs\SendEmailDomain;

class CheckDomain extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'check:domain';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check domain expiration and send noti';

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
            $domains = $user->domain->all();
            $tele_notis = $user->tele_noti->all();
            $email_notis = $user->email_noti->all();
            $domain_expired = [];
            $tele_sent = 0;
            $email_sent = 0;
            foreach ($domains as $domain) {
                if($domain->notification == 1){
                    if($domain->send_noti_before >= $domain->dayleft){
                        if(empty($domain->last_send_noti)){
                            $domain_expire = ['id'=>$domain->id,'domain'=> $domain->domain,'dayleft'=>$domain->dayleft];
                            array_push($domain_expired, $domain_expire);
                        } else {
                            // $last_sent_noti = Carbon::parse($ssl->last_sent_noti);
                            // $time_to_send_noti = $last_sent_noti->addDays($ssl->send_noti_after);
                            // $time_now = Carbon::now();
                            // if($time_now->gte($time_to_send_noti)){
                            //     $ssl_expire = ['id'=>$ssl->id,'domain'=> $ssl->domain,'dayleft'=>$certificate->daysUntilExpirationDate()];
                            //     array_push($ssl_expired, $ssl_expire);
                            // }
                            $expire_at = Carbon::parse($domain->expire_at);
                            $time_now = Carbon::now();
                            if(($expire_at->subDays($domain->send_noti_before - $domain->send_noti_after))->lte($time_now)){
                                $last_send_noti = Carbon::parse($domain->last_send_noti);
                                $time_to_send_noti = $last_send_noti->addDays(1);
                                if($time_now->gte($time_to_send_noti)){
                                    $domain_expire = ['id'=>$domain->id,'domain'=> $domain->domain,'dayleft'=>$domain->dayleft];
                                    array_push($domain_expired, $domain_expire);
                                }
                            }
                        }
                    }
                }
            }
            if(!empty($domain_expired)){
                foreach ($tele_notis as $tele_noti) {
                    if($tele_noti->status == 1){
                        $text = "Những domain sau đây của bạn sắp hết hạn:\n";
                        foreach($domain_expired as $domain_expires) {
                            $text = $text.$domain_expires['domain'] .' - Số ngày còn lại: <b>'.$domain_expires['dayleft']."</b>\n";
                            $time_now = Carbon::now()->format('Y-m-d H:i:s');
                            \DB::table('domain')->where('id',$domain_expires['id'])->update(['last_send_noti'=>$time_now]);
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
                        dispatch(new SendEmailDomain($domain_expired,$email->email));
                        foreach ($domain_expired as $domain) {
                            $time_now = Carbon::now()->format('Y-m-d H:i:s');
                            \DB::table('domain')->where('id',$domain['id'])->update(['last_send_noti'=>$time_now]);
                        }
                    }
                }
            }
        }
    }
}
