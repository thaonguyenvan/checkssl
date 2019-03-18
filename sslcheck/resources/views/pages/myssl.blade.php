@extends('layout.index')

@section('title')
<title>My SSL</title>
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
						<a class="nav-link active" href="{{route('myssl')}}">My SSL</a>
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
		@if (session('notify'))
		  <div class="alert alert-success">
		    {{ session('notify') }}
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
				<h1>Domains</h1>
			</div>
		</div>
		<p style="color: red;">
			Giới hạn domain của bạn: {{$limit_ssl}}
		</p>
		<small>
			Lưu ý: Khi thêm doamin, mặc định thời gian cảnh báo trước khi hết hạn và thời gian cảnh báo lại là lần lượt 60 và 30 ngày. Click vào từng domain để chỉnh sửa.
		</small>
		<div class="down-10"></div>
		<div class="white-bg p-2">
			<div class="row">
				<div class="col-md-4">
					<a class="btn btn-primary mb-1 domain-btn" data-toggle="collapse" href="#show-add-domain" role="button" aria-expanded="false" aria-controls="show-add-domain" href="#">Thêm Domain</a>
					<a class="btn btn-primary mb-1 domain-btn" data-toggle="collapse" href="#show-bulk-add" role="button" aria-expanded="false" aria-controls="show-bulk-add" href="#">Thêm nhiều Domains</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-6">
					<div class="collapse" id="show-add-domain">
						<div class="down-10">
							<form class="form-inline" action="user/addssl" accept-charset="UTF-8" data-remote="true" method="post">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<input name="utf8" type="hidden" value="✓">
								<label class="sr-only" for="domain_name">Tên Domain</label>
								<div class="input-group mr-sm-2">
									<div class="input-group-prepend">
										<div class="input-group-text">https://</div>
									</div>
									<input class="form-control input-focus" placeholder="Enter domain name here..." type="text" name="domain">
								</div>
								<input type="submit" name="commit" value="Thêm" class="btn btn-primary" data-disable-with="Adding...">
							</form>
						</div>
					</div>
					<div class="collapse" id="show-bulk-add">
						<div class="down-10">
							<form class="form" action="user/addmultiplessl" accept-charset="UTF-8" method="post">
								<input name="utf8" type="hidden" value="✓">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="form-group">
									<label class="sr-only" for="names">Thêm</label>
									<textarea class="form-control input-focus" name="domain_names"></textarea>
									<small class="help-text text-muted">Các domains cách nhau 1 dấu phẩy (',')</small>
								</div>
								<input type="submit" name="commit" value="Thêm domain" class="btn btn-primary">
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row down-10">
				<div class="col">
					<div class="table-responsive table-sm">
						<table class="table">
							<thead>
								<tr>
									<th>Domain</th>
									<th>Ngày hết hạn</th>
									<th>Thời gian còn lại</th>
									<th>Cung cấp bởi</th>
									<th>Thông báo trước khi hết hạn</th>
									<th>Thông báo lại sau</th>
								</tr>
							</thead>
							<tbody>
								@foreach($ssl as $s)
								<tr>
									<td><a href="user/detail/{{$s->id}}">{{$s->domain}}</a></td>
									<td>{{$s->expire_at}}</td>
									<td>{{$s->dayleft." ngày"}}</td>
									<td>{{$s->issue_by}}</td>
									<td>{{$s->send_noti_before." ngày"}}</td>
									<td>{{$s->send_noti_after." ngày"}}</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
					<div class="row">
						<div class="col">
							@if(!$ssl->first())
							<p>
								Bạn chưa có domain nào.
								Click vào
								<a class="btn btn-info mb-1 domain-btn" data-toggle="collapse" href="#show-add-domain" role="button" aria-expanded="false" href="#">Thêm Domain</a>
								để thêm domain.
							</p>
							@endif
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
@stop