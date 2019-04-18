@extends('layout.index')

@section('title')
<title>Cài đặt</title>
@stop
@section('content')
<div class="modal" id="myModal">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Thêm Telegram</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal body -->
				<div class="modal-body">
					<form action="user/addtele" method="post">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					  <div class="form-group">
					    <label for="name">Tên</label>
					    <input type="text" class="form-control" name="name" id="name">
					  </div>
					  <button type="submit" class="btn btn-primary">Thêm</button>
					  <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
					</form>
				</div>
			</div>
		</div>
	</div>
<div class="container mt-1">
		<h1>Cài đặt</h1>
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
		<div class="white-bg p-4">
			<div class="row">
				<div class="col">
					<h3>Cấu hình thông báo</h3>
					<hr>
					<p style="color: red;">Số email giới hạn: {{$limit_email}}</p>
					<p style="color: red;">Số telegram giới hạn: {{$limit_tele}}</p>
				</div>
			</div>
			<div class="row">
				<div class="col-md-4">
					<div class="card no-turbolinks" data-turbolinks="false">
						<strong class="card-header">Gửi thông báo tới</strong>
						<ul class="list-group list-group-flush">
							@foreach($email_noti as $e)
								<li class="list-group-item">
									<span>{{$e->email}}</span>
									<small class="small">
										{{-- <form action="user/delmail/{{$e->id}}" method="post">
											{{ csrf_field() }}
										    {{ method_field('DELETE') }}
										    <button type="submit" class="text-danger" onclick="return checkDelete()">Xóa</button>
										</form> --}}
										<a class="text-danger" onclick="return checkDelete()" rel="nofollow" href="user/delmail/{{$e->id}}">Xóa</a>
									</small>
								</li>
							@endforeach
							<li class="list-group-item">
								<form class="form-inline" data-turbolinks="false" action="user/addemail" accept-charset="UTF-8" data-remote="true" method="post">
									<input name="utf8" type="hidden" value="✓">
									<input type="hidden" name="_token" value="{{csrf_token()}}">
									<div class="form-group">
										<label class="sr-only" for="notification_email_email">Email</label>
										<input class="form-control mb-1" placeholder="Thêm Email" type="text" name="email">
										<input type="submit" name="commit" value="Thêm" class="btn btn-primary mb-1">
									</div>
								</form>
							</li>
						</ul>
					</div>
				</div>
				<div class="col-md-6">
					<div class="card">
						<strong class="card-header">Telegram</strong>
						<ul class="list-group list-group-flush">
							@foreach($tele_noti as $tele)
							<li class="list-group-item">
								<div class="row">
									<div class="col-md-4">
										<span>{{$tele->name}}</span>
									</div>
									<div class="col-md-4">
										<small class="small">
											{{-- <form action="user/deltele/{{$tele->id}}" method="post">
												{{ csrf_field() }}
											    {{ method_field('DELETE') }}
											    <button type="submit" class="text-danger" onclick="return checkDelete()">Xóa</button>
											</form> --}}
											<a class="text-danger" onclick="return checkDelete()" rel="nofollow" href="user/deltele/{{$tele->id}}">Xóa</a>
										</small>
									</div>
									<div class="col-md-4">
										@if(!$tele->chat_id)
											<small class="small"><a class="text-danger" rel="nofollow" target="_blank" href="https://telegram.me/sslautobot?start={{$tele->status_code}}">Xác thực</a></small>
										@endif
									</div>
								</div>
							</li>
							@endforeach
							<li class="list-group-item">
									<button class="btn btn-primary" data-toggle="modal" data-target="#myModal">Thêm telegram</button>
							</li>
						</ul>
					</div>
				</div>
			</div>
		</div>

	</div>
@stop

@section('script')
	<script language="JavaScript" type="text/javascript"> 
    function checkDelete(){ 
        return confirm('Bạn có chắc chắn?'); 
    } 
  </script>
@stop