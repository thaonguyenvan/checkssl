<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Iodev\Whois\Whois;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use App\Domain;
use App\Limit;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DomainExport;
use App\Exports\DomainExportCustom;

class DomainController extends Controller
{
	public function getCheckDomain(){
		return view('pages.checkdomain');
	}
	public function checkDomain(Request $request){
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
		$limit_default = Limit::first();
		if (starts_with($domain, ['http://', 'https://'])) {
		    $domain = str_replace(['http://', 'https://'],'',$domain);
		}
		if(is_valid_domain_name($domain)){
			if(preg_match("/(\.vn)/", $domain) == false){
				try{
					$whois = Whois::create();

			        $info = $whois->loadDomainInfo($domain);

			        $endDate = Carbon::createFromTimestamp($info->getExpirationDate(),'Asia/Ho_Chi_Minh');
			        $interval = Carbon::now()->diff($endDate);
			        $dayleft = (int) $interval->format('%r%a');

			        $domain_info = ['domain'=>$info->getDomainName(),'owner'=>$info->getOwner(),'register'=>$info->getRegistrar(),'created_at'=>$info->getCreationDate(),'expired_at'=>$info->getExpirationDate(),'dayleft'=>$dayleft,'send_noti_before'=>$limit_default->send_noti_before,'send_noti_after'=>$limit_default->send_noti_after];

			        return view('pages.domain-result',['domain_info'=>$domain_info]);
				} catch(\Exception $e){
					return redirect()->route('checkdomain')->withErrors('Xin lỗi, chúng tôi không tìm thấy thông tin cho domain '.$domain);
				}
			} else {
				// Domain .vn
				$ch = curl_init();

				$pre_domain = explode(".vn", $domain);


				$url = 'xxx.com'.$pre_domain[0].'&ext=.vn&type=1';
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

			        $domain_info = ['domain'=>$items->item(2)->nodeValue,'owner'=>$items->item(11)->nodeValue,'register'=>$items->item(8)->nodeValue,'created_at'=>strtotime($items->item(16)->nodeValue),'expired_at'=>strtotime($items->item(19)->nodeValue),'dayleft'=>$dayleft,'send_noti_before'=>$limit_default->send_noti_before,'send_noti_after'=>$limit_default->send_noti_after];

			        return view('pages.domain-result',['domain_info'=>$domain_info]);
		        } else {
		        	return redirect()->route('checkdomain')->withErrors('Xin lỗi, chúng tôi không tìm thấy thông tin cho domain '.$domain);
		        }
			}
			
		} else {
			return redirect()->route('checkdomain')->withErrors('Vui lòng nhập đúng định dạng');
		}
	}

	public function getMyDomain(){
		try{
			$user_id = Auth::user()->id;
		} catch (\Exception $e){
			return redirect()->route('checkdomain')->withErrors('Your session is expired');
		}
		$limit_domain = Auth::user()->limit_domain;
		$limit_default = Limit::first();
		$domains = Domain::where('user_id',$user_id)->orderBy('dayleft', 'ASC')->get();
		$link_export = 'user/domain/export/'.$user_id;

		return view('pages.mydomain',['domains'=>$domains,'limit_domain'=>$limit_domain,'limit_default'=>$limit_default,'link_export'=>$link_export]);
	}

	public function addDomain(Request $request){
		$this->validate($request,
			[
				'domain'=>'required|min:4'
			],
			[
				'domain.required'=>"Bạn chưa nhập tên domain",
				'domain.min'=>"Tên domain cần có ít nhất 4 kí tự"
			]
		);

		$current_domain = Domain::where('user_id',Auth::user()->id)->get();
		if(count($current_domain) < Auth::user()->limit_domain){

			$domain = $request->domain;
			
			if (starts_with($domain, ['http://', 'https://'])) {
			    $domain = str_replace(['http://', 'https://'],'',$domain);
			}
			if(is_valid_domain_name($domain)){
				if(preg_match("/(\.vn)/", $domain) == false){
					try{
						$whois = Whois::create();

				        $info = $whois->loadDomainInfo($domain);

				        $endDate = Carbon::createFromTimestamp($info->getExpirationDate(),'Asia/Ho_Chi_Minh');
				        $interval = Carbon::now()->diff($endDate);
				        $dayleft = (int) $interval->format('%r%a');

				        $dom = new Domain();
				        $limit_default = Limit::first();
				        $dom->domain = $info->getDomainName();
				        $dom->user_id = Auth::user()->id;
				        $dom->expire_at = date('Y-m-d H:i:s',$info->getExpirationDate());
				        $dom->create_at = date('Y-m-d H:i:s',$info->getCreationDate());
				        $dom->dayleft = $dayleft;
				        $dom->owner = $info->getOwner();
				        $dom->register = $info->getRegistrar();
				        
				        $dom->send_noti_before = $limit_default->send_noti_before;
				        $dom->send_noti_after = $limit_default->send_noti_after;
				        $dom->notification = 1;

				        $dom->save();

				        return redirect('user/mydomain');
					} catch(\Exception $e){
						return redirect()->route('mydomain')->withErrors('Xin lỗi, chúng tôi không tìm thấy thông tin cho domain '.$domain);
					}
				} else {
					// Domain .vn
					$ch = curl_init();

					$pre_domain = explode(".vn", $domain);


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

				        $dom = new Domain();
				        $limit_default = Limit::first();
				        $dom->domain = $items->item(2)->nodeValue;
				        $dom->user_id = Auth::user()->id;
				        $dom->expire_at = date('Y-m-d H:i:s',strtotime($items->item(19)->nodeValue));
				        $dom->create_at = date('Y-m-d H:i:s',strtotime($items->item(16)->nodeValue));
				        $dom->dayleft = $dayleft;
				        $dom->owner = $items->item(11)->nodeValue;
				        $dom->register = $items->item(8)->nodeValue;
				        
				        $dom->send_noti_before = $limit_default->send_noti_before;
				        $dom->send_noti_after = $limit_default->send_noti_after;
				        $dom->notification = 1;

				        $dom->save();

				        return redirect('user/mydomain');

			        } else {
			        	return redirect()->route('mydomain')->withErrors('Xin lỗi, chúng tôi không tìm thấy thông tin cho domain '.$domain);
			        }
				}
				
			} else {
				return redirect()->route('mydomain')->withErrors('Vui lòng nhập đúng định dạng');
			}
		} else {
			return redirect('user/mydomain')->with('warning','Bạn đã dùng quá giới hạn của mình, vui lòng liên hệ với admin để được hỗ trợ');
		}
	}

	public function postMyDomain(Request $request){
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
			$current_domain = Domain::where('user_id',Auth::user()->id)->get();
			if(count($current_domain) < Auth::user()->limit_domain){

				$dom = new Domain();

				$dom->user_id = Auth::user()->id;
				$dom->domain = $request->domain_name;
				$dom->dayleft = $request->domain_dayleft;
				$dom->owner = $request->domain_owner;
				$dom->register = $request->domain_register;
				$dom->expire_at = $request->domain_expire;
				$dom->create_at = $request->domain_create;
				$dom->notification = 1;
				$dom->send_noti_before = $request->noti_before;
				$dom->send_noti_after = $request->noti_after;

				$dom->save();

				return redirect()->route('mydomain')->with('status','Thêm thành công');
			} else {
				return redirect('user/mydomain')->with('warning','Bạn đã dùng quá giới hạn của mình, vui lòng liên hệ với admin để được hỗ trợ');
			}
		} else {
			return redirect('user/mydomain')->withErrors('Số ngày cảnh báo trở lại phải nhỏ hơn số ngày cảnh báo trước khi hết hạn');
		}
		

	}

	public function addMultiDomain(Request $request){
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
			$current_domain = Domain::where('user_id',Auth::user()->id)->get();
			if(count($current_domain) < Auth::user()->limit_domain){
				if (starts_with($domain, ['http://', 'https://'])) {
				    $domain = str_replace(['http://', 'https://'],'',$domain);
				}

				if(is_valid_domain_name($domain)){
					if(preg_match("/(\.vn)/", $domain) == false){
						try{
							$whois = Whois::create();

					        $info = $whois->loadDomainInfo($domain);

					        $endDate = Carbon::createFromTimestamp($info->getExpirationDate(),'Asia/Ho_Chi_Minh');
					        $interval = Carbon::now()->diff($endDate);
					        $dayleft = (int) $interval->format('%r%a');

					        $dom = new Domain();
					        $limit_default = Limit::first();
					        $dom->domain = $info->getDomainName();
					        $dom->user_id = Auth::user()->id;
					        $dom->expire_at = date('Y-m-d H:i:s',$info->getExpirationDate());
					        $dom->create_at = date('Y-m-d H:i:s',$info->getCreationDate());
					        $dom->dayleft = $dayleft;
					        $dom->owner = $info->getOwner();
					        $dom->register = $info->getRegistrar();
					        
					        $dom->send_noti_before = $limit_default->send_noti_before;
					        $dom->send_noti_after = $limit_default->send_noti_after;
					        $dom->notification = 1;

					        $dom->save();
						} catch(\Exception $e){
							
						}
					} else {
						// Domain .vn
						$ch = curl_init();

						$pre_domain = explode(".vn", $domain);


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

					        $dom = new Domain();
					        $limit_default = Limit::first();
					        $dom->domain = $items->item(2)->nodeValue;
					        $dom->user_id = Auth::user()->id;
					        $dom->expire_at = date('Y-m-d H:i:s',strtotime($items->item(19)->nodeValue));
					        $dom->create_at = date('Y-m-d H:i:s',strtotime($items->item(16)->nodeValue));
					        $dom->dayleft = $dayleft;
					        $dom->owner = $items->item(11)->nodeValue;
					        $dom->register = $items->item(8)->nodeValue;
					        
					        $dom->send_noti_before = $limit_default->send_noti_before;
					        $dom->send_noti_after = $limit_default->send_noti_after;
					        $dom->notification = 1;

					        $dom->save();

				        }
					}
				}
			} else {
				return redirect('user/mydomain')->with('warning','Bạn đã dùng quá giới hạn của mình, vui lòng liên hệ với admin để được hỗ trợ');
			}
		}
		return redirect()->route('mydomain');
	}

	public function exportDomain($id){
		if(Auth::user()->id == $id){
			return Excel::download(new DomainExport($id), 'domains.xlsx');
		} else {
			return redirect()->route('mydomain');
		}
	}
	public function exportCustom($expression,$day){
		if($expression == 'lt'){
        	$exp = '<';
        } else if($expression == 'gt'){
        	$exp = '>';
        } else {
        	$exp = "=";
        }
		return Excel::download(new DomainExportCustom($exp,$day), 'domains.xlsx');
	}
	public function filterDomain(Request $request){
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

				$domain = Domain::where('user_id',$user_id)->where('dayleft','<',$day)->orderBy('dayleft', 'ASC')->get();
				$link_export ="user/domain/exportcus/lt&".$day;

				return view('pages.mydomain',['domains'=>$domain,'link_export'=>$link_export]);
			} else if($expression == 2) {
				try{
					$user_id = Auth::user()->id;
				} catch (\Exception $e){
					return redirect()->route('login')->withErrors('Your session is expired');
				}

				$domain = Domain::where('user_id',$user_id)->where('dayleft','>',$day)->orderBy('dayleft', 'ASC')->get();
				$link_export ="user/domain/exportcus/gt&".$day;

				return view('pages.mydomain',['domains'=>$domain,'link_export'=>$link_export]);
			} else {
				try{
					$user_id = Auth::user()->id;
				} catch (\Exception $e){
					return redirect()->route('login')->withErrors('Your session is expired');
				}

				$domain = Domain::where('user_id',$user_id)->where('dayleft','=',$day)->orderBy('dayleft', 'ASC')->get();
				$link_export ="user/domain/exportcus/eq&".$day;

				return view('pages.mydomain',['domains'=>$domain,'link_export'=>$link_export]);
			}
		}
	}

	public function getDetail($id){
		$domain = Domain::find($id);

		return view('pages.detail-domain',['domain'=>$domain]);
	}
	public function getDelete($id){
		$domain = Domain::find($id);

		$domain->delete();

		return redirect('user/mydomain')->with('notify','Xóa thành công');
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
			$domain = Domain::find($id);

			$domain->send_noti_before = $request->noti_before;
			$domain->send_noti_after = $request->noti_after;

			$domain->save();

			return redirect('user/detaildomain/'.$id)->with('notify','Sửa thành công');
		} else {
			return redirect('user/detaildomain/'.$id)->withErrors('Số ngày cảnh báo trở lại phải nhỏ hơn số ngày cảnh báo trước khi hết hạn');
		}
		
	}

}
