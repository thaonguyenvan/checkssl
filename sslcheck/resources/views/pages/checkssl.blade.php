@extends('layout.index')

@section('title')
<title>Check SSL</title>
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
						<a class="nav-link active" href="{{route('checkssl')}}">Check SSL</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('myssl')}}">My SSL</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('setting')}}">Cài đặt</a>
					</li>
					<li class="nav-item">
						<a class="nav-link" href="{{route('profile')}}">Trang cá nhân</a>
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
		<div class="row">
			<div class="col">
				<h2 style="margin-top: 30px;">SSL Check</h2><span><a href="">Tool này có thể làm gì?</a></span>
			</div>
		</div>
		<div class="down-10"></div>
		<div class="col-md-6" style="margin-left: 0px;padding-left: 0px;">
			<div id="add-domain-form" style="margin-top: 30px;">
				<div class="down-10">
					<form class="form-inline" action="user/checkssl" accept-charset="UTF-8" data-remote="true" method="post">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<input name="utf8" type="hidden" value="✓">
						<label class="sr-only" for="domain_name">Tên Domain</label>
						<div class="input-group mr-sm-2">
							<div class="input-group-prepend">
								<div class="input-group-text">https://</div>
							</div>
							<input class="form-control input-focus" placeholder="Nhập tên domain ..." type="text" name="domain">
						</div>
						<input type="submit" name="commit" value="Check" class="btn btn-primary" data-disable-with="Adding...">
					</form>
				</div>
			</div>	
		</div>
	</div>
	<div class="container content-check">
		<div class="row row-custom">
			<div class="col-md-4"><p>1. Nhập tên domain</p></div>
			<div class="col-md-4"><p>2. Click check button</p></div>
		</div>
		<div class="row" style="margin-left: 0px;">
			<div class="col-md-4 col-md-4-custom">
				<p>Nhập tên domain dưới một trong những dạng sau:</p>
				<ul>
					<li>cloud365.vn</li>
					<li>https://cloud365.vn</li>
				</ul>
			</div>
			<div class="col-md-4 col-md-4-custom">
				<p>Chúng tôi sẽ hiển thị cho bạn:</p>
				<ul>
					<li>Trang web đã có SSL hay chưa</li>
					<li>Ngày hết hạn</li>
					<li>Đơn vị cung cấp</li>
					<li>Một vài thông tin khác</li>
				</ul>
			</div>
		</div>
	</div>
@stop