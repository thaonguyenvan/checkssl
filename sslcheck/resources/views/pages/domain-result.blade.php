@extends('layout.index')

@section('title')
<title>Kết quả check</title>
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
					<form action="user/mydomain" method="post">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					<input type="hidden" name="domain_name" value="{{$domain_info['domain']}}">
					<input type="hidden" name="domain_owner" value="{{$domain_info['owner']}}">
					<input type="hidden" name="domain_dayleft" value="{{$domain_info['dayleft']}}">
					<input type="hidden" name="domain_register" value="{{$domain_info['register']}}">
					<input type="hidden" name="domain_create" value="{{date('Y-m-d', $domain_info['created_at'])}}">
					<input type="hidden" name="domain_expire" value="{{date('Y-m-d', $domain_info['expired_at'])}}">
					  <div class="form-group">
					    <label for="noti_before">Gửi thông báo trước khi hết hạn ... ngày?</label>
					    <input type="text" value="{{$domain_info['send_noti_before']}}" class="form-control" name="noti_before" id="noti_before">
					  </div>
					  <div class="form-group">
					    <label for="noti_after">Gửi lại thông báo sau ... ngày?</label>
					    <input type="text" value="{{$domain_info['send_noti_after']}}" class="form-control" name="noti_after" id="noti_after">
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
				<h2 style="margin-top: 30px;">Domain Check</h2><span><a href="{{route('checkdomain')}}">Tool này có thể làm gì?</a></span>
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
					<form class="form-inline" action="user/checkdomain" accept-charset="UTF-8" data-remote="true" method="post">
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
		<div class="row" style="margin-left: 0px;">
			<div class="col-md-6 col-md-6-custom">
				<table class="table table-bordered table-custom">
				    <tbody>
				      <tr>
				        <td>Domain</td>
				        <td>{{$domain_info['domain']}}</td>
				      </tr>
				      <tr>
				        <td>Chủ sở hữu</td>
				        <td>{{$domain_info['owner']}}</td>
				      </tr>
				      <tr>
				        <td>Đơn vị đăng kí</td>
				        <td>{{$domain_info['register']}}</td>
				      </tr>
				      <tr>
				        <td>Ngày khởi tạo</td>
				        <td>{{date('d-m-Y', $domain_info['created_at'])}}</td>
				      </tr>
				      <tr>
				        <td>Ngày hết hạn</td>
				        <td>{{date('d-m-Y', $domain_info['expired_at'])}}</td>
				      </tr>
				      <tr>
				        <td>Số ngày còn lại</td>
				        <td>{{$domain_info['dayleft']}}</td>
				      </tr>
				    </tbody>
				</table>
			</div>
		</div>
		<button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
			Thêm vào My Domain
		</button>

	</div>
@stop