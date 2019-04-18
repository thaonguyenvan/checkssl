@extends('layout.index')

@section('title')
<title>My SSL</title>
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
			Lưu ý: Khi thêm domain, mặc định thời gian cảnh báo trước khi hết hạn và thời gian cảnh báo lại là lần lượt {{$limit_default->send_noti_before}} và {{$limit_default->send_noti_after}} ngày. Sau thời gian cảnh báo lại, hệ thống sẽ tự động gửi cảnh báo 1 ngày 1 lần nếu SSL chưa được gia hạn. Click vào từng domain để xem thông tin và chỉnh sửa.
		</small>
		<div class="down-10"></div>
		<div class="white-bg p-2">
			<div class="row">
				<div class="col-md-12">
					<a class="btn btn-primary" data-toggle="collapse" href="#show-add-domain" role="button" aria-expanded="false" aria-controls="show-add-domain" href="#">Thêm Domain</a>
					<a class="btn btn-primary" data-toggle="collapse" href="#show-bulk-add" role="button" aria-expanded="false" aria-controls="show-bulk-add" href="#">Thêm nhiều Domains</a>
					<button type="button" class="btn btn-warning" onclick="disable_noti()">Disable cảnh báo</button>
					<button type="button" class="btn btn-success" onclick="enable_noti()">Enable cảnh báo</button>
					<button type="button" class="btn btn-danger" onclick="delete_ssl()">Xóa Domain</button>
					<a class="btn btn-info" href="{{$link_export}}">Export Excel</a>
					<a class="btn btn-secondary" data-toggle="collapse" href="#show-filter" role="button" aria-expanded="false" aria-controls="show-filter">Filter</a>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
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
					<div class="collapse" id="show-filter">
						<div class="down-10">
							<form class="form" action="user/ssl/filter" accept-charset="UTF-8" method="post">
								<input name="utf8" type="hidden" value="✓">
								<input type="hidden" name="_token" value="{{csrf_token()}}">
								<div class="form-row">
									<div class="form-group col-md-2">
								      <select name="column" class="form-control">
								        <option selected value="1">Thời gian còn lại</option>
								      </select>
								    </div>
								    <div class="form-group col-md-2">
								      <select name="expression" class="form-control">
								        <option selected value="1">Nhỏ hơn</option>
								        <option value="2">Lớn hơn</option>
								        <option value="3">Bằng</option>
								      </select>
								    </div>
								    <div class="form-group col-md-2">
								      <input type="text" name="day" placeholder="Nhập vào số ngày" class="form-control">
								    </div>
								</div>
								<input type="submit" name="commit" value="Filter" class="btn btn-primary">
							</form>
						</div>
					</div>
					
				</div>
			</div>
			<div class="row down-10">
				<div class="col">
					<div class="table-responsive table-sm">
						<table class="table table-bordered text-center">
							<thead>
								<tr>
									<th>
										<div class="checkbox">
									      <input type="checkbox" class="check" id="checkAll">
									  	</div>
									</th>
									<th>Domain</th>
									<th>Ngày hết hạn</th>
									<th>Thời gian còn lại</th>
									<th>Cung cấp bởi</th>
									<th>Thông báo</th>
									<th>Thông báo trước khi hết hạn</th>
									<th>Thông báo lại sau</th>
								</tr>
							</thead>
							<tbody>
								@foreach($ssl as $s)
								<tr>
									<td>
										<div class="checkbox">
									      <input type="checkbox" class="check" value="{{$s->id}}">
									  	</div>
									</td>
									<td><a href="user/detail/{{$s->id}}">{{$s->domain}}</a></td>
									<td>{{$s->expire_at}}</td>
									<td>{{$s->dayleft." ngày"}}</td>
									<td>{{$s->issue_by}}</td>
									<td>
										@if($s->notification == 1)
											<i class="fa fa-check-circle"></i>
										@else
											<i class="fa fa-remove"></i>
										@endif
									</td>
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

@section('script')
<script>
	$("#checkAll").click(function () {
	    $(".check").prop('checked', $(this).prop('checked'));
	});
</script>
<script>
	function disable_noti(){
		$(document).ready(function() {
			var ids = new Array();
			$('.check:checked').each(function() {
			    ids.push($(this).val());
			});
			$.ajax({
	            url : "user/ssl/disablenoti",
	            type : "post",
	            dataType:"text",
	            data : {
	            	"_token": "{{ csrf_token() }}",
			        'data': ids
			    },
	            success : function (data){
	                if (data == "refresh"){
				      window.location.reload();
				    } else {
				    	console.log("Lỗi thay đổi");
				    }
	            }
	        });
		});    
    }

    function enable_noti(){
		$(document).ready(function() {
			var ids = new Array();
			$('.check:checked').each(function() {
			    ids.push($(this).val());
			});
			$.ajax({
	            url : "user/ssl/enablenoti",
	            type : "post",
	            dataType:"text",
	            data : {
	            	"_token": "{{ csrf_token() }}",
			        'data': ids
			    },
	            success : function (data){
	                if (data == "refresh"){
				      window.location.reload();
				    } else {
				    	console.log("Lỗi thay đổi");
				    }
	            }
	        });
		});    
    }

    function delete_ssl(){
		$(document).ready(function() {
			var ids = new Array();
			$('.check:checked').each(function() {
			    ids.push($(this).val());
			});
			$.ajax({
	            url : "user/ssl/deletessl",
	            type : "post",
	            dataType:"text",
	            beforeSend:function(){
			        return confirm("Bạn có chắc chắn muốn xóa những domain này?");
			    },
	            data : {
	            	"_token": "{{ csrf_token() }}",
			        'data': ids
			    },
	            success : function (data){
	                if (data == "refresh"){
				      window.location.reload();
				    } else {
				    	console.log("Lỗi thay đổi");
				    }
	            }
	        });
		});    
    }

    function test(){
    	$(document).ready(function() {
	            var favorite = [];
	            $.each($(".check:checked"), function(){            
	                favorite.push($(this).val());
	            });
	            console.log(favorite);
	    });
    }
</script>
@endsection