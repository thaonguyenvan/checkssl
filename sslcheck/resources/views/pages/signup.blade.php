@extends('layout.index')

@section('title')
<title>Đăng kí</title>
@stop
@section('navbar')
<nav class="navbar navbar-expand-md navbar-dark bg-primary">
		<div class="container">
			<a class="navbar-brand" href="{{route('home')}}">
				<img src="public/static/images/logo-white.png" alt="" width="130px" height="40px">
			</a>
			<button class="navbar-toggler" data-target="#collapsing-navbar" data-toggle="collapse" type="button">
				<span class="navbar-toggler-icon"></span>
			</button>
			<div class="collapse navbar-collapse" id="collapsing-navbar">
				<ul class="navbar-nav mr-auto">
					<li class="nav-item">
						<a class="nav-link" href="login">Đăng nhập</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="signup">Đăng kí</a>
					</li>
				</ul>
			</div>
		</div>
</nav>
@stop
@section('content')
<div class="container mt-1">
		<div class="row">
			<div class="col-md-6 m-auto">
				<div class="card">
					<h4 class="card-header">Đăng kí tài khoản</h4>
					<div class="card-body">
						@if(count($errors) > 0)
	                        <div class="alert alert-danger">
	                            @foreach($errors->all() as $err)
	                                {{$err}} <br>
	                            @endforeach
	                        </div>
	                    @endif
						<form class="new_user" id="new_user" action="signup" accept-charset="UTF-8" method="post">
							<input name="utf8" type="hidden" value="✓">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<div class="form-group">
								<label for="email">Email</label>
								<input class="form-control" type="email" value="" name="email" id="email">
							</div>
							<div class="form-group">
								<label for="user_password">Mật khẩu</label>
								<input autocomplete="off" class="form-control" type="password" name="password" id="password" aria-autocomplete="list">
								<small class="form-text text-muted">
									(tối thiểu 6 kí tự)
								</small>
							</div>
							<div class="form-group">
								<label for="user_password_confirmation">Nhập lại mật khẩu</label>
								<input autocomplete="off" class="form-control" type="password" name="password_confirmation" id="password-confirm">
							</div>
							<input type="submit" name="commit" value="Sign up" class="btn btn-primary" data-disable-with="Sign up">
						</form><hr>
						<p>Bằng việc đăng kí tài khoản, bạn đã đồng ý với <a href="terms">Điều khoản</a> và <a href="privacy">Chính sách bảo mật</a> của chúng tôi</p>
						<hr>
						<a href="login">Đăng nhập</a>
						<br>
						<a href="email/resend">Không nhận được mail xác thực?</a>
						<br>

					</div>
				</div>
			</div>
		</div>
	</div>
@stop