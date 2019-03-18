<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\SslCertificate\SslCertificate;
use App\Ssl;
use App\User;
use App\Email_noti;
use App\Tele_noti;
use Illuminate\Support\Facades\Auth;
use App\VerifyEmail;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyAddMail;
use Illuminate\Support\Facades\Hash;
use App\Mail\VerifyMail;
use App\Jobs\AddEmailVerify;
use App\Jobs\SendEmailVerifyUser;

class UserController extends Controller
{
    public function getSetting(){
        $email_noti = Email_noti::where('user_id',Auth::user()->id)->get();
		$tele_noti = Tele_noti::where('user_id',Auth::user()->id)->get();
        $limit_email = Auth::user()->limit_email;
        $limit_tele = Auth::user()->limit_tele;
		return view('pages.settings',['email_noti'=>$email_noti,'tele_noti'=>$tele_noti,'limit_email'=>$limit_email,'limit_tele'=>$limit_tele]);
	}

	public function addEmail(Request $request){
		$this->validate($request,
			[
				'email'=>'required|email|unique:email_noti'
			],
			[
				'email.required'=>'Bạn chưa nhập email',
				'email.email'=>'Email sai định dạng',
				'email.unique'=>'Email này đã được sử dụng'
			]
		);

		$email_current = Email_noti::where('user_id',Auth::user()->id)->get();
        $num_email = count($email_current);
        if($num_email < Auth::user()->limit_email){
            $email_noti = $this->createMail($request->all());
            return redirect('user/setting')->with('status','Bạn vừa thêm một email mới, vui lòng kiểm tra mail và xác thực theo hướng dẫn');
        } else {
            return redirect('user/setting')->with('warning','Bạn đã dùng quá giới hạn email của mình, liên lạc với admin để được hỗ trợ');
        }
        

	}

    public function addTele(Request $request){
        $this->validate($request,
            [
                'name'=>'required|min:3|max:20'
            ],
            [
                'name.required'=>'Bạn chưa nhập tên',
                'name.min'=>'Ít nhất 3 kí tự',
                'name.max'=>'Nhiều nhất 20 kí tự'
            ]
        );

        $tele_current = Tele_noti::where('user_id',Auth::user()->id)->get();
        $num_tele = count($tele_current);
        if ($num_tele < Auth::user()->limit_tele) {
            $tele_noti = new Tele_noti();

            $tele_noti->user_id = Auth::user()->id;
            $tele_noti->name = $request->name;
            $tele_noti->status_code = rand(100000,900000);
            $tele_noti->status = 0;

            $tele_noti->save();

            return redirect('user/setting')->with('status','Thêm thành công, click vào "Xác thực" và chat với bot của chúng tôi để bắt đầu nhận thông báo');
        } else {
            return redirect('user/setting')->with('warning','Bạn đã dùng quá giới hạn telegram của mình, liên lạc với admin để được hỗ trợ');
        }
        
    }

    public function delTele($id){
        $tele_noti = Tele_noti::find($id);

        $tele_noti->delete();

        return redirect('user/setting')->with('status','Xóa thành công!');
    }

	public function createMail(array $data){
        $email_noti = new Email_noti();
		$email_noti->user_id = Auth::user()->id;
		$email_noti->email = $data['email'];
		$email_noti->status = 0;

		$email_noti->save();

		$verifyEmail = VerifyEmail::create([
		    'email_id' => $email_noti->id,
		    'token' => sha1(time())
		 ]);
		 // \Mail::to($email_noti->email)->send(new VerifyAddMail($email_noti));

        dispatch(new AddEmailVerify($email_noti));

		return $email_noti;
	}

	public function verifyAddMail($token)
    {
      $verifyEmail = VerifyEmail::where('token', $token)->first();
      if(isset($verifyEmail) ){
        $email_noti = $verifyEmail->email_noti;
        if(!$email_noti->verified) {
          $verifyEmail->email_noti->verified = 1;
          $verifyEmail->email_noti->status = 1;
          $verifyEmail->email_noti->save();
         	$status = "Email của bạn đã được xác thực, bạn đã có thể nhận thông báo thông qua email này.";
        } else {
          $status = "Email của bạn đã được xác thực.";
        }
      } else {
        return redirect()->route('setting')->with('warning', "Xin lỗi, chúng tôi không tìm thấy email của bạn.");
      }
      return redirect()->route('setting')->with('status', $status);
    }

    public function delMail($id){
    	$email_noti = Email_noti::find($id);
    	$verifyEmail = VerifyEmail::where('email_id',$id);

    	$verifyEmail->delete();
    	$email_noti->delete();

    	return redirect('user/setting')->with('status','Xóa thành công!');
    }

    public function getProfile(){
    	return view('pages.profile');
    }

    public function editProfile(Request $request,$id){
    	
        if (strpos($request->email, 'gmail') !== false) {
            $email = explode("@", $request->email);
            if(strpos($email[0], '.') !== false){
              $email[0] =str_replace(".", "", $email[0]);
            }
            $email = implode("@", $email);

            $request->merge([
                'email'=>$email,
            ]);
        }

        $this->validate($request,
    		[
    			'email'=>'required|email|max:255|string|unique:users',
    			'current_password'=>'required'
    		],
    		[
    			'email.required'=>'Bạn chưa nhập email',
    			'email.email'=>'Sai định dạng email',
    			'email.max'=>'Email quá dài',
    			'email.string'=>'Sai định dạng email',
    			'email.unique'=>'Email này đã được sử dụng',
    			'current_password.required'=>'Bạn chưa nhập mật khẩu'
    		]
    	);

    	if(empty($request->password)){
    		if($request->email !== Auth::user()->email){
    			if(Hash::check($request->current_password, Auth::user()->password)){
    				$user = User::find($id);

    				$user->email = $request->email;
    				$user->verified = 0;
    				$user->save();

    				// \Mail::to($user->email)->send(new VerifyMail($user));

                    dispatch(new SendEmailVerifyUser($user));
    				return redirect('user/profile')->with('status','Cập nhật thành công, kiểm tra mail để xác thực tài khoản');
    			} else {
    				return redirect('user/profile')->with('warning','Mật khẩu không đúng');
    			}
    		} else {
                return redirect('user/profile');
            }
    	} else {
    		if($request->password == $request->password_confirmation){
    			if($request->email !== Auth::user()->email){
    				if(Hash::check($request->current_password, Auth::user()->password)){
						if(strlen($request->password) >= 6){
                            $user = User::find($id);

                            $user->email = $request->email;
                            $user->password = Hash::make($request->password);
                            $user->verified = 0;
                            $user->save();

                            // \Mail::to($user->email)->send(new VerifyMail($user));
                            dispatch(new SendEmailVerifyUser($user));
                            return redirect('user/profile')->with('status','Cập nhật thành công, kiểm tra mail để xác thực tài khoản');
                        } else {
                            return redirect('user/profile')->with('warning','Mật khẩu phải có ít nhất 6 kí tự');
                        }
					} else {
						return redirect('user/profile')->with('warning','Mật khẩu không đúng');
					}
    			} else{
    				if(Hash::check($request->current_password, Auth::user()->password)){
						if(strlen($request->password) >= 6){
                            $user = User::find($id);
                            $user->password = Hash::make($request->password);
                            $user->save();
                            return redirect('user/profile')->with('status','Cập nhật thành công');
                        } else {
                            return redirect('user/profile')->with('warning','Mật khẩu phải có ít nhất 6 kí tự');
                        }
					} else {
						return redirect('user/profile')->with('warning','Mật khẩu không đúng');
					}
    			}
    		} else {
    			return redirect('user/profile')->with('warning','Mật khẩu không khớp');
    		}
    	}

    	return redirect('user/profile')->with('status','Cập nhật thành công');
    }
}