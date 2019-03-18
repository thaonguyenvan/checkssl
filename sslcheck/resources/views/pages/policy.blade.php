@extends('layout.index')

@section('title')
<title>Chính sách</title>
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
						<a class="nav-link" href="signup">Đăng kí</a>
					</li>
					
				</ul>
			</div>
		</div>
</nav>
@stop
@section('content')
<div class="container">
	<h3>Chính sách bảo mật</h3>
	<p>Khi bạn sử dụng website, chúng tôi sẽ thu thập một số dữ liệu của bạn, các dữ liệu này bao gồm</p>
	<ul>
		<li>Thông tin cá nhân của bạn bao gồm email và mật khẩu dưới dạng mã hóa</li>
		<li>Thông tin về ip và trình duyệt của bạn</li>
		<li>Thông tin về các ssl mà bạn check trên website</li>
	</ul>
	<p class="center">Last Updated: March 7th, 2019</p>
</div>
@stop