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
	<h3>Chính sách dịch vụ</h3>
	<p>Đây là website giúp bạn check ssl và nhận cảnh báo hoàn toàn miễn phí, nó được tạo ra bởi <a href="https://github.com/thaonguyenvan">ThaoNV</a></p>
	<p class="center">Last Updated: March 7th, 2019</p>
</div>
@stop