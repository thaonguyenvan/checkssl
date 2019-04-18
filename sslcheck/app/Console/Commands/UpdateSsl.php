<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Spatie\SslCertificate\SslCertificate;
use App\Ssl;
use App\Domain;
use App\User;
use App\Email_noti;
use App\Tele_noti;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;
use App\Mail\SendNotiMail;
use Telegram\Bot\Laravel\Facades\Telegram;
use Iodev\Whois\Whois;

class UpdateSsl extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:ssl';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to check and update ssl database';

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
        $ssls = Ssl::all();
        $doms = Domain::all();

        foreach ($ssls as $ssl) {
            try{
                $certificate = SslCertificate::createForHostName($ssl->domain);
            } catch(\Exception $e){
                // Gửi email rồi xóa khỏi hệ thống
                $ssl->delete();
            }
            
            $ssl->expire_at = $certificate->expirationDate()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
            $ssl->dayleft = $certificate->daysUntilExpirationDate();
            $ssl->issue_by = $certificate->getIssuer();
            $ssl->save();
        }

        foreach ($doms as $dom) {
            if(preg_match("/(\.vn)/", $dom->domain) == false){
                try{
                    $whois = Whois::create();

                    $info = $whois->loadDomainInfo($dom->domain);

                    $endDate = Carbon::createFromTimestamp($info->getExpirationDate(),'Asia/Ho_Chi_Minh');
                    $interval = Carbon::now()->diff($endDate);
                    $dayleft = (int) $interval->format('%r%a');

                    $dom->expire_at = date('Y-m-d H:i:s',$info->getExpirationDate());
                    $dom->create_at = date('Y-m-d H:i:s',$info->getCreationDate());
                    $dom->dayleft = $dayleft;
                    $dom->owner = $info->getOwner();
                    $dom->register = $info->getRegistrar();
                    

                    $dom->save();

                } catch(\Exception $e){
                    
                }
            } else {
                // Domain .vn
                $ch = curl_init();

                $pre_domain = explode(".vn", $dom->domain);


                $url = 'https://nhanhoa.com/whois/?domain='.$pre_domain[0].'&ext=.vn&type=1';
                curl_setopt($ch, CURLOPT_URL,$url);
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'GET');

                curl_setopt($ch, CURLOPT_ENCODING, 'gzip, deflate');

                $headers = array();
                // $headers[] = 'Connection: keep-alive';
                // $headers[] = 'Cache-Control: max-age=0';
                // $headers[] = 'Upgrade-Insecure-Requests: 1';
                // $headers[] = 'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,image/apng,*/*;q=0.8,application/signed-exchange;v=b3';
                // $headers[] = 'Accept-Encoding: gzip, deflate, br';
                $headers[] = 'Accept-Language: vi-VN,vi;q=0.9,en;q=0.8';
                curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

                $result = curl_exec($ch);
                if (curl_errno($ch)) {
                    echo 'Error:' . curl_error($ch);
                }
                curl_close ($ch);

                if($result){
                    $DOM = new \DOMDocument();
                    $DOM->loadHTML(mb_convert_encoding($result, 'HTML-ENTITIES', 'UTF-8'));

                    $items = $DOM->getElementsByTagName('div');
                    
                    $endDate = Carbon::createFromTimestamp(strtotime($items->item(19)->nodeValue),'Asia/Ho_Chi_Minh');
                    $interval = Carbon::now()->diff($endDate);
                    $dayleft = (int) $interval->format('%r%a');

                    $dom->expire_at = date('Y-m-d H:i:s',strtotime($items->item(19)->nodeValue));
                    $dom->create_at = date('Y-m-d H:i:s',strtotime($items->item(16)->nodeValue));
                    $dom->dayleft = $dayleft;
                    $dom->owner = $items->item(11)->nodeValue;
                    $dom->register = $items->item(8)->nodeValue;


                    $dom->save();

                }
            }
        }
    }
}
