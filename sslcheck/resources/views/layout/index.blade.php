<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	@yield('title')
	<base href="{{asset('')}}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="shortcut icon" href="public/static/images/logo-shortcut.jpg" />
	<!-- Local bootstrap CSS & JS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- <link rel="stylesheet" media="screen" href="static/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/web.css">
	<script src="static/jquery/jquery-3.3.1.min.js"></script>
	<script src="static/bootstrap/js/bootstrap.min.js"></script> -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="public/static/css/web.css">
</head>
<body>
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
					@if(Auth::check())
					<li class="nav-item">
						<a class="nav-link @if(Route::current()->getName() == 'checkssl') {{'active'}} @endif" href="{{route('checkssl')}}">Check SSL</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if(Route::current()->getName() == 'checkdomain') {{'active'}} @endif" href="{{route('checkdomain')}}">Check Domain</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if(Route::current()->getName() == 'myssl') {{'active'}} @endif" href="{{route('myssl')}}">My SSL</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if(Route::current()->getName() == 'mydomain') {{'active'}} @endif" href="{{route('mydomain')}}">My Domain</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if(Route::current()->getName() == 'setting') {{'active'}} @endif" href="{{route('setting')}}">Cài đặt</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if(Route::current()->getName() == 'profile') {{'active'}} @endif" href="{{route('profile')}}">Trang cá nhân</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if(Route::current()->getName() == 'logout') {{'active'}} @endif" href="{{route('logout')}}">Đăng xuất</a>
					</li>
					@else
					<li class="nav-item">
						<a class="nav-link @if(Route::current()->getName() == 'login') {{'active'}} @endif" href="login">Đăng nhập</a>
					</li>
					<li class="nav-item">
						<a class="nav-link @if(Route::current()->getName() == 'signup') {{'active'}} @endif" href="signup">Đăng kí</a>
					</li>
					@endif
				</ul>
			</div>
		</div>
	</nav>
	
	@yield('content')
	@yield('script')
</body>
</html>