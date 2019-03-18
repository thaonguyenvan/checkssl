<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Auth;
use App\Email_noti;
use App\Ssl;
use App\Ssl_all;
use App\Tele_noti;
use App\VerifyUser;
use Illuminate\Support\Facades\Hash;

class AdminController extends Controller
{
    public function getHome(){
        $num_user = count(User::all());
        $num_ssl_saved = count(Ssl::all());
        $num_ssl_checked = count(Ssl_all::all());
        $num_emails = count(Email_noti::all());
        $num_teles = count(Tele_noti::all());
    	return view('admin.pages.dashboard',['num_user'=>$num_user,'num_ssl_saved'=>$num_ssl_saved,'num_ssl_checked'=>$num_ssl_checked,'num_emails'=>$num_emails,'num_teles'=>$num_teles]);
    }

    public function getUserList(){
    	$users = User::all();
    	$email_noti = Email_noti::all();
    	$tele_noti = Tele_noti::all();
    	return view('admin.user.userlist',['users'=>$users,'emails'=>$email_noti,'teles'=>$tele_noti]);
    }

    public function getUserAdd(){
    	return view('admin.user.adduser');
    }

    public function editUser(Request $request,$id){
    	$this->validate($request,
    		[
    			'email'=>'required|email|max:255',
    			'limit_ssl'=>'required|integer',
    			'limit_email'=>'required|integer',
    			'limit_tele'=>'required|integer'
    		],
    		[
    			'email.required'=>'Bạn chưa nhập email',
    			'email.email'=>'Sai định dạng email',
    			'email.max'=>'Email quá dài',
    			'limit_ssl.required'=>'Bạn chưa nhập limit ssl',
    			'limit_ssl.integer'=>'Sai định dạng',
    			'limit_email.required'=>'Bạn chưa nhập limit email',
    			'limit_email.integer'=>'Sai định dạng',
    			'limit_tele.required'=>'Bạn chưa nhập limit telegram',
    			'limit_tele.integer'=>'Sai định dạng'
    		]
    	);

    	$user = User::find($id);
    	if($request->email !== $user->email){
    		$email_test = User::where('email','=',$request->email)->first();
    		if($email_test->first()){
    			return redirect('admin/user/list')->with('warning','Email đã tồn tại');
    		} else {
    			$user->email = $request->email;
    			if(empty($request->password)){
    			$user->role = $request->role;
    			$user->limit_ssl = $request->limit_ssl;
    			$user->limit_email = $request->limit_email;
    			$user->limit_tele = $request->limit_tele;

    			$user->save();
    			return redirect('admin/user/list')->with('status','Cập nhật thành công');
	    		} else {
	    			if($request->password == $request->password_confirmation){
	    				if(count($request->password >= 6)){
	    					$user->password = Hash::make($request->password);
		    				$user->role = $request->role;
		    				$user->limit_ssl = $request->limit_ssl;
		    				$user->limit_email = $request->limit_email;
		    				$user->limit_tele = $request->limit_tele;

		    				$user->save();
		    				return redirect('admin/user/list')->with('status','Cập nhật thành công');
	    				} else {
	    					return redirect('admin/user/list')->with('error','Mật khẩu phải có ít nhất 6 kí tự');
	    				}
	    			} else {
	    				return redirect('admin/user/list')->with('error','Mật khẩu không trùng khớp');
	    			}
	    		}
    		}	
    	} else {
    		if(empty($request->password)){
    			$user->role = $request->role;
    			$user->limit_ssl = $request->limit_ssl;
    			$user->limit_email = $request->limit_email;
    			$user->limit_tele = $request->limit_tele;

    			$user->save();
    			return redirect('admin/user/list')->with('status','Cập nhật thành công');
    		} else {
    			if($request->password == $request->password_confirmation){
    				if(count($request->password >= 6)){
	    				$user->password = Hash::make($request->password);
	    				$user->role = $request->role;
	    				$user->limit_ssl = $request->limit_ssl;
	    				$user->limit_email = $request->limit_email;
	    				$user->limit_tele = $request->limit_tele;

	    				$user->save();
	    				return redirect('admin/user/list')->with('status','Cập nhật thành công');
	    			} else {
	    				return redirect('admin/user/list')->with('error','Mật khẩu phải có ít nhất 6 kí tự');
	    			}
    			} else {
    				return redirect('admin/user/list')->with('error','Mật khẩu không trùng khớp');
    			}
    		}
    	}

    }

    public function addUser(Request $request){
    	$this->validate($request,
    		[
    			'email'=>'required|email|max:255|string|unique:users|unique:email_noti',
    			'password'=>'required|string|confirmed|min:6',
    		],
    		[
    			'email.required'=>'Bạn chưa nhập email',
    			'email.email'=>'Sai định dạng',
    			'email.max'=>'Email quá dài',
    			'email.string'=>'Sai định dạng',
    			'email.unique'=>'Email đã tồn tại',
    			'password.required'=>'Bạn chưa nhập password',
    			'password.string'=>'Sai định dạng',
    			'password.confirmed'=>'Mật khẩu chưa khớp',
    			'password.min'=>'Mật khẩu phải có it nhất 6 kí tự'
    		]
    	);

    	$user = new User();

    	$user->email = $request->email;
    	$user->password = Hash::make($request->password);
    	$user->role = $request->role;
    	$user->limit_ssl = $request->limit_ssl;
    	$user->limit_tele = $request->limit_tele;
    	$user->limit_email = $request->limit_email;

    	$user->save();

    	if(empty($request->verify)){
    		$user->verified = 0;
    	} else {
    		$user->verified = 1;
    		$verifyUser = VerifyUser::create([
	            'user_id' => $user->id,
	            'token' => sha1(time())
        	]);
    	}

    	if(!empty($request->email_noti)){
    		$email_noti = new Email_noti();
            $email_noti->user_id = $user->id;
            $email_noti->email = $user->email;
            $email_noti->status = 1;
            $email_noti->verified = 1;

            $email_noti->save();
    	}

    	$user->save();

    	return redirect('admin/user/add')->with('notify','Thêm thành công');
    }

    public function deleteUser($id){
    	$user = User::find($id);

    	$user->delete();

    	return redirect('admin/user/list')->with('status','Xóa thành công');
    }

    public function findSslList($id){
    	$ssl_save = Ssl::where('user_id',$id)->get();

    	return view('admin.ssl.listssl',['ssl_save'=>$ssl_save]);
    }

    public function getSslAll($id){
    	$ssl_all = Ssl_all::where('user_id',$id)->get();

    	return view('admin.ssl.listsslall',['ssl_all'=>$ssl_all]);
    }

    public function getSslList(){
    	$ssl_save = Ssl::orderBy('created_at','DESC')->get();

    	return view('admin.ssl.listssl',['ssl_save'=>$ssl_save]);
    }

    public function listAllSsl(){
    	$ssl_all = Ssl_all::orderBy('created_at','DESC')->get();

    	return view('admin.ssl.listsslall',['ssl_all'=>$ssl_all]);
    }

}
