<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SslCertificate\SslCertificate;
use App\Ssl;
use App\Ssl_all;
use App\Limit;
use App\User;
use App\Email_noti;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Exports\SslExport;
use App\Exports\ExportCustom;
use Maatwebsite\Excel\Facades\Excel;

class SslController extends Controller
{
	public function getCheckSsl(){
		return view('pages.checkssl');
	}

	public function getMySsl(){
		try{
			$user_id = Auth::user()->id;
		} catch (\Exception $e){
			return redirect()->route('checkssl')->withErrors('Your session is expired');
		}

		$limit_ssl = Auth::user()->limit_ssl;
		$limit_default = Limit::first();
		$ssl = Ssl::where('user_id',$user_id)->orderBy('dayleft', 'ASC')->get();
		$link_export = 'user/ssl/export/'.$user_id;

		return view('pages.myssl',['ssl'=>$ssl,'limit_ssl'=>$limit_ssl,'limit_default'=>$limit_default,'link_export'=>$link_export]);
	}

	public function getResult(){
		return view('pages.result');
	}

	public function checkSsl(Request $request){
		$this->validate($request,
			[
				'domain'=>'required|min:4'
			],
			[
				'domain.required'=>"Bạn chưa nhập tên domain",
				'domain.min'=>"Tên domain cần có ít nhất 4 kí tự"
			]
		);

		$domain = $request->domain;
		$ssl_all = new Ssl_all();
		$limit_default = Limit::first();
		$ssl_all->user_id = Auth::user()->id; 
		$ssl_all->domain = $request->domain;
		$ssl_id = rand(10,100000);
		$ssl_all->ssl_id = $ssl_id;
		try{
			$timeout = 5;
			$certificate = SslCertificate::createForHostName($domain,$timeout);
			$ssl_all->has_ssl = 1;
			$ssl_all->save();
		} catch(\Exception $e){
			$ssl_all->has_ssl = 0;
			$ssl_all->save();
			return redirect()->route('checkssl')->withErrors('Xin lỗi, chúng tôi không tìm thấy thông tin về SSL cho domain '.$domain);
		}
		
		$server_type = '';
		$url ='';
		if (! starts_with($domain, ['http://', 'https://', 'ssl://'])) {
            $url = "https://{$domain}";
        } else {
        	$url = $domain;
        }
		try{
			$headers = get_headers($url, 1);
		    if(array_key_exists('Server',$headers)){
				if(is_array($headers['Server']))
				{
					$server_type = $headers['Server'][0];
				}
				else{
					$server_type = explode('/', $headers['Server'])[0];
				}
			}
		} catch(\Exception $e){
			return redirect()->route('checkssl')->withErrors('Vui lòng nhập theo định dạng được hướng dẫn bên dưới '.$domain);
		}

		$information = ['ssl_id'=>$ssl_id,'send_noti_before'=>$limit_default->send_noti_before,'send_noti_after'=>$limit_default->send_noti_after,'domain'=>$domain,'expire_at'=>$certificate->expirationDate()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s'), 'issue_by'=>$certificate->getIssuer(),'sign_algo'=>$certificate->getSignatureAlgorithm(),'more_domain'=>$certificate->getAdditionalDomains(),'server_type'=>$server_type,'brand'=>$certificate->getIssuer(),'dayleft'=>$certificate->daysUntilExpirationDate(),'organization'=>$certificate->getOrga(),'state'=>$certificate->getState(),'address'=>$certificate->getAddress(),'locality'=>$certificate->getLocality(),'country'=>$certificate->getCountry(),'issuer_infor'=>['organization'=>$certificate->getIssuerOrga(),'state'=>$certificate->getIssuerState(),'locality'=>$certificate->getIssuerLocality(),'country'=>$certificate->getIssuerCountry()]];

		return view('pages.result',['information'=>$information]);
	}

	public function postMySsl(Request $request){
		$this->validate($request,
			[
				'noti_before'=>'integer|min:0',
				'noti_after'=>'integer|min:0'
			],
			[
				'noti_before.integer'=>'Bạn chưa nhập số ngày thông báo trước khi hết hạn',
				'noti_before.min'=>'Số ngày phải là số dương',
				'noti_after.integer'=>'Bạn chưa nhập số ngày thông báo lại',
				'noti_after.min'=>'Số ngày phải là số dương'
			]
		);
		if($request->noti_before > $request->noti_after){
			$current_ssl = Ssl::where('user_id',Auth::user()->id)->get();
			if(count($current_ssl) < Auth::user()->limit_ssl){
				$ssl = new Ssl();

				$ssl->user_id = Auth::user()->id;
				$ssl->domain = $request->domain_name;
				$ssl->expire_at = $request->expire_at;
				$ssl->dayleft = $request->dayleft;
				$ssl->issue_by = $request->issue_by;
				$ssl->notification = 1;
				$ssl->send_noti_before = $request->noti_before;
				$ssl->send_noti_after = $request->noti_after;

				$ssl->save();

				$ssl_id = $request->ssl_id;
				$ssl_all = Ssl_all::where('domain',$ssl->domain)->where('ssl_id',$ssl_id)->first();
				if($ssl_all){
					$ssl_all->is_stored = 1;
					$ssl_all->save();
				}
				return redirect('user/myssl')->with('status','Thêm thành công');
			} else {
				return redirect('user/myssl')->with('warning','Bạn đã dùng quá giới hạn của mình, vui lòng liên hệ với admin để được hỗ trợ');
			}
		} else {
			return redirect('user/myssl')->withErrors('Số ngày cảnh báo trở lại phải nhỏ hơn số ngày cảnh báo trước khi hết hạn');
		}
	}

	public function addSsl(Request $request){
		$this->validate($request,
			[
				'domain'=>'required|min:4'
			],
			[
				'domain.required'=>"Bạn chưa nhập tên domain",
				'domain.min'=>"Tên domain cần có ít nhất 4 kí tự"
			]
		);

		$domain = $request->domain;
		$ssl_all = new Ssl_all();
		$ssl_all->user_id = Auth::user()->id; 
		$ssl_all->domain = $request->domain;
		$current_ssl = Ssl::where('user_id',Auth::user()->id)->get();
		if(count($current_ssl) < Auth::user()->limit_ssl){
			try{
				$timeout = 4;
				$certificate = SslCertificate::createForHostName($domain,$timeout);
			} catch(\Exception $e){
				$ssl_all->has_ssl = 0;
				$ssl_all->save();
				return redirect()->route('myssl')->withErrors('Xin lỗi, chúng tôi không tìm thấy thông tin về ssl cho '.$domain);
			}

			$ssl = new Ssl();
			$limit_default = Limit::first();

			$ssl->user_id = Auth::user()->id;
			$ssl->domain = $request->domain;
			$ssl->expire_at = $certificate->expirationDate()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
			$ssl->dayleft = $certificate->daysUntilExpirationDate();
			$ssl->issue_by = $certificate->getIssuer();
			$ssl->notification = 1;
			$ssl->send_noti_before = $limit_default->send_noti_before;
			$ssl->send_noti_after = $limit_default->send_noti_after;

			$ssl->save();

			$ssl_all->has_ssl = 1;
			$ssl_all->is_stored = 1;
			$ssl_all->save();

			return redirect('user/myssl');
		} else {
			return redirect('user/myssl')->with('warning','Bạn đã dùng quá giới hạn của mình, vui lòng liên hệ với admin để được hỗ trợ');
		}
	}

	public function getDetail($id){
		$ssl = Ssl::find($id);

		return view('pages.detail',['ssl'=>$ssl]);
	}

	public function getDelete($id){
		$ssl = Ssl::find($id);

		$ssl->delete();

		return redirect('user/myssl')->with('notify','Xóa thành công');
	}

	public function postEdit(Request $request,$id){
		$this->validate($request,
			[
				'noti_before'=>'integer|min:0',
				'noti_after'=>'integer|min:0'
			],
			[
				'noti_before.integer'=>'Sai định dạng',
				'noti_before.min'=>'Số ngày phải là số dương',
				'noti_after.integer'=>'Sai định sạng',
				'noti_after.min'=>'Số ngày phải là số dương'
			]
		);

		if($request->noti_before > $request->noti_after){
			$ssl = Ssl::find($id);

			$ssl->send_noti_before = $request->noti_before;
			$ssl->send_noti_after = $request->noti_after;

			$ssl->save();

			return redirect('user/detail/'.$id)->with('notify','Sửa thành công');
		} else {
			return redirect('user/detail/'.$id)->withErrors('Số ngày cảnh báo trở lại phải nhỏ hơn số ngày cảnh báo trước khi hết hạn');
		}
		
	}

	public function addMultipleSsl(Request $request){
		$this->validate($request,
			[
				'domain_names'=>'required|min:4'
			],
			[
				'domain_names.required'=>"Bạn chưa nhập tên domain",
				'domain_names.min'=>"Tên domain cần có ít nhất 4 kí tự"
			]
		);

		$domains = explode(',',$request->domain_names);

		foreach ($domains as $domain) {
			$current_ssl = Ssl::where('user_id',Auth::user()->id)->get();
			if(count($current_ssl) < Auth::user()->limit_ssl){
				try{
					$certificate = SslCertificate::createForHostName($domain);
					$ssl = new Ssl();
					$limit_default = Limit::first();

					$ssl->user_id = Auth::user()->id;
					$ssl->domain = $domain;
					$ssl->expire_at = $certificate->expirationDate()->setTimezone(new \DateTimeZone('Asia/Ho_Chi_Minh'))->format('Y-m-d H:i:s');
					$ssl->dayleft = $certificate->daysUntilExpirationDate();
					$ssl->issue_by = $certificate->getIssuer();
					$ssl->send_noti_before = $limit_default->send_noti_before;
					$ssl->send_noti_after = $limit_default->send_noti_after;

					$ssl->notification = 1;

					$ssl->save();

					$ssl_all = new Ssl_all();

					$ssl_all->user_id = Auth::user()->id; 
					$ssl_all->domain = $domain;
					$ssl_all->has_ssl = 1;
					$ssl_all->is_stored = 1;

					$ssl_all->save();
				} catch(\Exception $e){
					$ssl_all = new Ssl_all();

					$ssl_all->user_id = Auth::user()->id; 
					$ssl_all->domain = $domain;
					$ssl_all->has_ssl = 0;

					$ssl_all->save();
				}
			} else {
				return redirect('user/myssl')->with('warning','Bạn đã dùng quá giới hạn của mình, vui lòng liên hệ với admin để được hỗ trợ');
			}
		}

		return redirect('user/myssl')->with('notify','Thêm thành công');
	}

	public function exportSsl($id){
		return Excel::download(new SslExport($id), 'domains.xlsx');
	}

	public function exportCustom($expression,$day){
		if($expression == 'lt'){
        	$exp = '<';
        } else if($expression == 'gt'){
        	$exp = '>';
        } else {
        	$exp = "=";
        }
		return Excel::download(new ExportCustom($exp,$day), 'domains.xlsx');
	}

	public function filterSsl(Request $request){
		$this->validate($request,
			[
				'day'=>'required|integer|min:0'
			],
			[
				'day.required'=>'Bạn chưa nhập số ngày',
				'day.integer'=>'Sai định dạng',
				'day.min'=>'Số ngày phải là số dương'
			]
		);

		$column = $request->column;
		$expression = $request->expression;
		$day = $request->day;

		if ($column == 1) {
			if($expression == 1){
				try{
					$user_id = Auth::user()->id;
				} catch (\Exception $e){
					return redirect()->route('login')->withErrors('Your session is expired');
				}

				$limit_ssl = Auth::user()->limit_ssl;
				$ssl = Ssl::where('user_id',$user_id)->where('dayleft','<',$day)->orderBy('dayleft', 'ASC')->get();
				$link_export ="user/ssl/exportcus/lt&".$day;

				return view('pages.myssl',['ssl'=>$ssl,'limit_ssl'=>$limit_ssl,'link_export'=>$link_export]);
			} else if($expression == 2) {
				try{
					$user_id = Auth::user()->id;
				} catch (\Exception $e){
					return redirect()->route('login')->withErrors('Your session is expired');
				}

				$limit_ssl = Auth::user()->limit_ssl;
				$ssl = Ssl::where('user_id',$user_id)->where('dayleft','>',$day)->orderBy('dayleft', 'ASC')->get();
				$link_export ="user/ssl/exportcus/gt&".$day;

				return view('pages.myssl',['ssl'=>$ssl,'limit_ssl'=>$limit_ssl,'link_export'=>$link_export]);
			} else {
				try{
					$user_id = Auth::user()->id;
				} catch (\Exception $e){
					return redirect()->route('login')->withErrors('Your session is expired');
				}

				$limit_ssl = Auth::user()->limit_ssl;
				$ssl = Ssl::where('user_id',$user_id)->where('dayleft','=',$day)->orderBy('dayleft', 'ASC')->get();
				$link_export ="user/ssl/exportcus/eq&".$day;

				return view('pages.myssl',['ssl'=>$ssl,'limit_ssl'=>$limit_ssl,'link_export'=>$link_export]);
			}
		}
	}
	
}
