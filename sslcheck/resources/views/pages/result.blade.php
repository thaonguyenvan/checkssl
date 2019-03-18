@extends('layout.index')

@section('title')
<title>Kết quả check</title>
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
<div class="modal" id="myModal">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Thêm vào My SSL</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal body -->
				<div class="modal-body">
					<form action="user/myssl" method="post">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="hidden" name="domain_name" value="{{$information['domain']}}">
					<input type="hidden" name="expire_at" value="{{$information['expire_at']}}">
					<input type="hidden" name="dayleft" value="{{$information['dayleft']}}">
					<input type="hidden" name="issue_by" value="{{$information['issue_by']}}">
					<input type="hidden" name="ssl_id" value="{{$information['ssl_id']}}">
					  <div class="form-group">
					    <label for="noti_before">Gửi thông báo trước khi hết hạn ... ngày?</label>
					    <input type="text" value="60" class="form-control" name="noti_before" id="noti_before">
					  </div>
					  <div class="form-group">
					    <label for="noti_after">Gửi lại thông báo sau ... ngày?</label>
					    <input type="text" value="30" class="form-control" name="noti_after" id="noti_after">
					  </div>
					  <button type="submit" class="btn btn-primary">Thêm</button>
					  <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
					</form>
				</div>
			</div>
		</div>
	</div>
	<div class="container mt-1">
		<div class="row">
			<div class="col">
				<h2 style="margin-top: 30px;">SSL Check</h2><span><a href="">Tool này có thể làm gì?</a></span>
			</div>
		</div>
		<div class="down-10"></div>
		<div class="col-md-6" style="margin-left: 0px;padding-left: 0px;">
			<div id="add-domain-form" style="margin-top: 30px;">
				<div class="down-10">
					@if(count($errors) > 0)
	                        <div class="alert alert-danger">
	                            @foreach($errors->all() as $err)
	                                {{$err}} <br>
	                            @endforeach
	                        </div>
	                    @endif
					<form class="form-inline" action="user/checkssl" accept-charset="UTF-8" data-remote="true" method="post">
						<input name="utf8" type="hidden" value="✓">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<label class="sr-only" for="domain">Tên Domain</label>
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
			<div class="col-md-6"><p>1. Thông tin chung</p></div>
			<div class="col-md-6"><p>2. Certificate của bạn</p></div>
		</div>
		<div class="row" style="margin-left: 0px;">
			<div class="col-md-6 col-md-6-custom">
				<table class="table table-bordered table-custom">
				    <tbody>
				      <tr>
				        <td>Domain</td>
				        <td>{{$information['domain']}}</td>
				      </tr>
				      <tr>
				        <td>Ngày hết hạn</td>
				        <td>{{date('d-m-Y', strtotime($information['expire_at']))}}</td>
				      </tr>
				      <tr>
				        <td>Loại server</td>
				        <td>{{$information['server_type']}}</td>
				      </tr>
				    </tbody>
				</table>
			</div>
			<div class="col-md-6 col-md-6-custom">
				<table class="table table-bordered">
				    <tbody>
				      <tr>
				        <td>Tên tổ chức</td>
				        <td>{{$information['brand']}}</td>
				      </tr>
				      <tr>
				        <td>Signature Algorithm</td>
				        <td>{{$information['sign_algo']}}</td>
				      </tr>
				      <tr>
				        <td>Số ngày còn lại</td>
				        <td>{{$information['dayleft']}}</td>
				      </tr>
				    </tbody>
				</table>
			</div>
			<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
			  Thêm vào My SSL
			</button>
		</div>
		<div class="row row-custom down-10">
			<div class="col-md-6"><p>3. Cung cấp bởi</p></div>
			<div class="col-md-6"><p>4. Cung cấp cho</p></div>
		</div>
		<div class="row" style="margin-left: 0px;">
			<div class="col-md-6 col-md-6-custom">
				<table class="table table-bordered table-custom">
				    <tbody>
				      <tr>
				        <td>Tổ chức</td>
				        <td>{{$information['issuer_infor']['organization']}}</td>
				      </tr>
				      <tr>
				        <td>Tên</td>
				        <td>{{$information['issue_by']}}</td>
				      </tr>
				      <tr>
				        <td>Quốc gia</td>
				        <td>{{$information['issuer_infor']['country']}}</td>
				      </tr>
				      <tr>
				        <td>Tỉnh thành</td>
				        <td>{{$information['issuer_infor']['state']}}</td>
				      </tr>
				      <tr>
				        <td>Vị trí</td>
				        <td>{{$information['issuer_infor']['locality']}}</td>
				      </tr>
				    </tbody>
				</table>
			</div>
			<div class="col-md-6 col-md-6-custom">
				<table class="table table-bordered">
				    <tbody>
				      <tr>
				        <td>Tổ chức</td>
				        <td>{{$information['organization']}}</td>
				      </tr>
				      <tr>
				        <td>Quốc gia</td>
				        <td>{{$information['country']}}</td>
				      </tr>
				      <tr>
				        <td>Tỉnh thành</td>
				        <td>{{$information['state']}}</td>
				      </tr>
				      <tr>
				        <td>Vị trí</td>
				        <td>{{$information['locality']}}</td>
				      </tr>
				      <tr>
				        <td>Địa chỉ</td>
				        <td>{{$information['address']}}</td>
				      </tr>
				    </tbody>
				</table>
			</div>
		</div>
		<div class="row row-custom down-10">
			<div class="col-md-6"><p>5. Domain liên quan</p></div>
		</div>
		<div class="row">
			<ul>
				@foreach($information['more_domain'] as $related_domain)
				<li>
					{{$related_domain}}
				</li>
				@endforeach
			</ul>
		</div>
	</div>
@stop