<!DOCTYPE html>
<html>
  <head>
    <title>Bạn vừa thêm email này</title>
  </head>
  <body>
    <h2>Để có thể nhận thông báo từ SSLCheck</h2>
    <br/>
    Hãy click vào link sau để xác thực tài khoản
    <br/>
    <a href="{{url('user/addemail/verify', $email_noti->verifyAddEmail->token)}}">Xác thực tài khoản</a>
  </body>
</html>