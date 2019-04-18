@extends('layout.index')

@section('title')
<title>Trang cá nhân</title>
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
		<h1>Profile</h1>
		<div class="white-bg p-4">
			<div class="row">
				<div class="col-md-6">
					<form class="edit_user" id="edit_user" action="user/edituser/{{Auth::user()->id}}" accept-charset="UTF-8" method="post">
						<input name="utf8" type="hidden" value="✓">
						<input type="hidden" name="_token" value="{{csrf_token()}}">
						<div class="form-group">
							<label for="user_email">Email</label>
							<input class="form-control" type="email" value="{{Auth::user()->email}}" name="email" id="user_email">
						</div>
						<div class="form-group">
							<label for="user_password">Mật khẩu</label>
							<i class="small">(để trông nếu bạn không muốn thay đổi)</i>
							<input class="form-control" autocomplete="off" type="password" name="password" id="user_password">
							<em class="small">
								ít nhất 6 kí tự
							</em>
						</div>
						<div class="form-group">
							<label for="user_password_confirmation">Nhập lại mật khẩu</label>
							<input class="form-control" autocomplete="off" type="password" name="password_confirmation" id="password-confirm">
						</div>
						<div class="form-group">
							<label for="user_current_password">Mật khẩu hiện tại</label>
							<i class="small font-weight-bold">(chúng tôi cần mật khẩu để xác thực)</i>
							<input class="form-control" autocomplete="off" type="password" name="current_password" id="user_current_password">
						</div>
						<div class="actions">
							<input type="submit" name="commit" value="Cập nhật" class="btn btn-primary" data-disable-with="Update">
						</div>
					</form>
				</div>
			</div>
		</div>
	</div>
@stop