<?php

namespace App\Http\Controllers;

use App\User;
use App\Limit;
use App\VerifyUser;
use App\Email_noti;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Foundation\Auth\RedirectsUsers;
use Illuminate\Support\Facades\Mail;
use App\Mail\VerifyMail;
use App\Jobs\SendEmailVerifyUser;

class RegisterController extends Controller
{
	use RegistersUsers;


    public function getSignup()
    {
        return view('pages.signup');
    }

    public function register(Request $request)
    {
        $requestData = $request->all();
        if (strpos($requestData['email'], 'gmail') !== false) {
            $email = explode("@", $requestData['email']);
            if(strpos($email[0], '.') !== false){
              $email[0] =str_replace(".", "", $email[0]);
            }
            $requestData['email'] = implode("@", $email);
        }
        $this->validator($requestData)->validate();
        event(new Registered($user = $this->create($request->all())));

        return $this->registered($request, $user)
                        ?: redirect('login');
    }


    /**
     * Get the guard to be used during registration.
     *
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    protected function guard()
    {
        return Auth::guard();
    }

    /**
     * The user has been registered.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function registered(Request $request, $user)
    {
        $this->guard()->logout();
        return redirect()->route('login')->with('status', 'Chúng tôi vừa gửi tới bạn một mail xác thực. Vui lòng kiếm tra mail và xác thực theo hướng dẫn.');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'password' => ['required', 'string', 'min:6', 'confirmed'],
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    protected function create(array $data)
    {
        // $user = User::create([
        //     'email' => $data['email'],
        //     'password' => Hash::make($data['password']),
        // ]);

        $user = new User();
        $limit_default = Limit::first();

        $user->email = $data['email'];
        $user->password = Hash::make($data['password']);
        $user->limit_ssl = $limit_default->limit_ssl;
        $user->limit_domain = $limit_default->limit_domain;
        $user->limit_email = $limit_default->limit_email;
        $user->limit_tele = $limit_default->limit_tele;

        $user->save();

        $verifyUser = VerifyUser::create([
            'user_id' => $user->id,
            'token' => sha1(time())
        ]);
        // \Mail::to($user->email)->send(new VerifyMail($user));

        dispatch(new SendEmailVerifyUser($user));

        return $user;
    }

    public function verifyUser($token)
    {
      $verifyUser = VerifyUser::where('token', $token)->first();
      if(isset($verifyUser)){
        $user = $verifyUser->user;
        if(!$user->verified) {
          $verifyUser->user->verified = 1;
          $verifyUser->user->save();
          $email_noti = Email_noti::where('user_id', $user->id)->first();
            if(!isset($email_noti)){
                $email_noti = new Email_noti();
                $email_noti->user_id = $user->id;
                $email_noti->email = $user->email;
                $email_noti->status = 1;
                $email_noti->verified = 1;

                $email_noti->save();
            }
          $status = "Email của bạn đã được xác thực. Bạn có thể sử dụng nó để đăng nhập";
        } else {
          $status = "Email của bạn đã được xác thực từ trước. Bạn có thể sử dụng nó để đăng nhập";
        }
      } else {
        return redirect('login')->with('warning', "Xin lỗi, chúng tôi không thể tìm thấy email của bạn.");
      }
      return redirect('login')->with('status', $status);
    }

    public function getResend(){
        return view('pages.resend');
    }

    public function postResend(Request $request){
        $this->validate($request,
            [
                'email'=>'required|string|email',
                'password'=>'required|string|min:6'
            ],
            [
                'email.required'=>'Bạn chưa nhập email',
                'email.string'=>'Email sai định dạng',
                'email.email'=>'Email sai định dạng',
                'password.required'=>'Bạn chưa nhập mật khẩu',
                'password.string'=>'Mật khẩu sai định dạng',
                'password.min'=>'Mật khẩu phải có ít nhất 6 kí tự'
            ]
        );

        $user = User::where('email',$request->email)->first();

        if($user){
            if(Hash::check($request->password, $user->password)) {
                if(!$user->verified){
                    // \Mail::to($user->email)->send(new VerifyMail($user));
                    dispatch(new SendEmailVerifyUser($user));
                    return redirect()->route('login')->with('status', 'Chúng tôi vừa gửi tới bạn một mail xác thực. Vui lòng kiếm tra mail và xác thực theo hướng dẫn.');
                } else {
                    return redirect()->route('login')->with('status','Tài khoản của bạn đã được xác thực rồi');
                }
            } else {
                return redirect('email/resend')->with('warning', "Thông tin không chính xác");
            }
        } else {
            return redirect('email/resend')->with('warning', "Email của bạn không tồn tại");
        }
    }

}

