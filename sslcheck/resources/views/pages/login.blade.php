@extends('layout.index')

@section('title')
<title>Đăng nhập</title>
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
						<a class="nav-link active" href="login">Đăng nhập</a>
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
<div class="container mt-1">
		<div class="row">
			<div class="col-md-6 m-auto">
				<div class="card">
					<h4 class="card-header">Đăng nhập</h4>
					<div class="card-body">
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
						<form class="new_user" id="new_user" action="login" accept-charset="UTF-8" method="post">
							<input name="utf8" type="hidden" value="✓">
							<input type="hidden" name="_token" value="{{csrf_token()}}">
							<div class="form-group">
							<label for="user_email">Email</label>
							<input autofocus="autofocus" class="form-control" type="email" value="" name="email" id="email">
						</div>
						<div class="form-group">
							<label for="user_password">Mật khẩu</label>
							<input autocomplete="off" class="form-control" type="password" name="password" id="password">
						</div>
						<input type="submit" name="commit" value="Log in" class="btn btn-primary col-sm-12" data-disable-with="Log in">
					</form><hr>
					<a href="signup">Đăng kí</a>
					<br>
					<a href="password/reset">Quên mật khẩu?</a>
					<br>
					<a href="email/resend">Không nhận được mail xác thực?</a>
					<br>

				</div>
			</div>
		</div>
	</div>
@stop