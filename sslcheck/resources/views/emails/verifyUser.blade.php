<!DOCTYPE html>
<html>
  <head>
    <title>Welcome Email</title>
  </head>
  <body>
    <h2>Chào mừng đến với SSLCheck!</h2>
    <br/>
    Email bạn dùng để đăng kí tài khoản là {{$user['email']}} , Hãy click vào link sau để xác thực tài khoản của bạn
    <br/>
    <a href="{{url('email/verify', $user->verifyUser->token)}}">Xác thực tài khoản</a>
  </body>
</html>