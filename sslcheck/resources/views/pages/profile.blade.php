@extends('layout.index')

@section('title')
<title>Trang cá nhân</title>
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
						<a class="nav-link" href="{{route('checkssl')}}">Check SSL</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('myssl')}}">My SSL</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('setting')}}">Cài đặt</a>
					</li>
					<li class="nav-item">
						<a class="nav-link active" href="{{route('profile')}}">Trang cá nhân</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('logout')}}">Đăng xuất</a>
					</li>
				</ul>
			</div>
		</div>
	</nav>
@stop
@section('content')
<div class="container mt-1">
		@if (session('status'))
		  <div class="alert alert-success">
		    {{ session('status') }}
		  </div>
		@endif
		@if (session('warning'))
		  <div class="alert alert-warning">
		    {{ session('warning') }}
		  </div>
		@endif
		@if(count($errors) > 0)
	        <div class="alert alert-danger">
	            @foreach($errors->all() as $err)
	                {{$err}} <br>
	            @endforeach
	        </div>
	    @endif
		<h1>Profile</h1>
		<div class="white-bg p-4">
			<div class="row">
				<div class="col-md-6">
					<form class="edit_user" id="edit_user" action="user/edituser/{{Auth::user()->id}}" accept-charset="UTF-8" method="post">
						<input name="utf8" type="hidden" value="✓">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<div class="form-group">
							<label for="user_email">Email</label>
							<input class="form-control" type="email" value="{{Auth::user()->email}}" name="email" id="user_email">
						</div>
						<div class="form-group">
							<label for="user_password">Mật khẩu</label>
							<i class="small">(để trông nếu bạn không muốn thay đổi)</i>
							<input class="form-control" autocomplete="off" type="password" name="password" id="user_password">
							<em class="small">
								ít nhất 6 kí tự
							</em>
						</div>
						<div class="form-group">
							<label for="user_password_confirmation">Nhập lại mật khẩu</label>
							<input class="form-control" autocomplete="off" type="password" name="password_confirmation" id="password-confirm">
						</div>
						<div class="form-group">
							<label for="user_current_password">Mật khẩu hiện tại</label>
							<i class="small font-weight-bold">(chúng tôi cần mật khẩu để xác thực)</i>
							<input class="form-control" autocomplete="off" type="password" name="current_password" id="user_current_password">
						</div>
						<div class="actions">
							<input type="submit" name="commit" value="Cập nhật" class="btn btn-primary" data-disable-with="Update">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop