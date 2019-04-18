@extends('layout.index')

@section('title')
<title>Chi tiết SSL</title>
@stop
@section('content')
<div class="modal" id="myModal">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content">
				<!-- Modal Header -->
				<div class="modal-header">
					<h4 class="modal-title">Chỉnh sửa {{$ssl->domain}}</h4>
					<button type="button" class="close" data-dismiss="modal">&times;</button>
				</div>
				<!-- Modal body -->
				<div class="modal-body">
					<form action="user/detail/edit/{{$ssl->id}}" method="post">
					<input type="hidden" name="_token" value="{{csrf_token()}}">
					  <div class="form-group">
					    <label for="noti_before">Gửi thông báo trước khi hết hạn ... ngày?</label>
					    <input type="text" value="{{$ssl->send_noti_before}}" class="form-control" name="noti_before" id="noti_before">
					  </div>
					  <div class="form-group">
					    <label for="noti_after">Gửi lại thông báo sau ... ngày?</label>
					    <input type="text" value="{{$ssl->send_noti_after}}" class="form-control" name="noti_after" id="noti_after">
					  </div>
					  <button type="submit" class="btn btn-primary">Cập nhật</button>
					  <button type="button" class="btn btn-danger" data-dismiss="modal">Đóng</button>
					</form>
				</div>
			</div>
		</div>
	</div>
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
				<h1>
					{{$ssl->domain}}
					<img class="img-responsive" src="" style="max-width: 32px">
				</h1>
			</div>
		</div>
		<div class="down-10"></div>
		<div class="white-bg p-2">
			<a href="#" class="btn btn-info mb-2" data-toggle="modal" data-target="#myModal">Chỉnh sửa {{$ssl->domain}}</a>
			<form style="display:inline-block;" action="user/detail/delete/{{$ssl->id}}" method="post">
				{{ csrf_field() }}
			    {{ method_field('DELETE') }}
			    <button type="submit" class="btn btn-danger mb-2" onclick="return checkDelete()">Xóa {{$ssl->domain}}</button>
			</form>
			<hr>
			<div class="row">
				<div class="col-md-3 mt-2">
					<div class="text-secondary">Hết hạn vào</div>
					<h3 title="">{{date('d-m-Y', strtotime($ssl->expire_at))}}</h3>
				</div>
				<div class="col-md-3 mt-2">
					<div class="text-secondary">Thời gian còn lại</div>
					<h3>{{$ssl->dayleft.' ngày'}}</h3>
				</div>
				<div class="col-md-3 mt-2">
					<div class="text-secondary">Cung cấp bởi</div>
					<h3>{{$ssl->issue_by}}</h3>
				</div>
				<div class="col-md-3 mt-2">
					<div class="text-secondary">Ngày khởi tạo</div>
					<h3>{{date('d-m-Y', strtotime($ssl->created_at))}}</h3>
				</div>
			</div>
			<div class="row">
				<div class="col-md-3 mt-2">
					<div class="text-secondary">Gửi cảnh báo trước khi hết hạn</div>
					<h3 title="">{{$ssl->send_noti_before." ngày"}}</h3>
				</div>
				<div class="col-md-3 mt-2">
					<div class="text-secondary">Gửi cảnh lại sau</div>
					<h3>{{$ssl->send_noti_after." ngày"}}</h3>
				</div>
			</div>
		</div>
		<div aria-labelledby="Certificate Details" class="modal fade w-100" id="full-certificate-text" role="dialog" style="display: none;" aria-hidden="true">
			<div class="modal-dialog" role="document">
				<div class="modal-content">
					<div class="modal-header">
						<div class="modal-title">Certificate Details</div>
						<button aria-label="Close" class="close" data-dismiss="modal" type="button">
							<span aria-hidden="true">×</span>
						</button>
					</div>
					<div class="modal-body">
					</div>
				</div>
			</div>
		</div>
	</div>
@stop

@section('script')
	<script language="JavaScript" type="text/javascript"> 
    function checkDelete(){ 
        return confirm('Bạn có chắc chắn muốn xóa?'); 
    } 
  </script>
@stop