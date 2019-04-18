@extends('layout.index')

@section('title')
<title>SSL Check</title>
@stop

@section('content')
<div class="jumbotron jumbotron-fluid jumbotron-custom">
		<div class="container">
			<h1 class="center">Nhận thông báo trước khi SSL của bạn hết hạn</h1>
			<p class="lead center mt-2">Hiển thị thông tin SSL domain của bạn</p>
			<div class="mt-5">
				<div class="center">
					<a class="btn btn-success btn-lg" href="signup">ĐĂNG KÍ</a>
				</div>
				<div class="center">
					<div class="mt-2">
						<small class="help-text text-muted"><a class="text-light" href="login">Bạn đã có tài khoản?</a></small>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="container">
		<div class="row align-items-center">
			<div class="col-md-6">
				<div class="row mb-2">
					<div class="col">
						<div class="card">
							<div class="card-body">
								Bước 1: Đăng kí tài khoản
							</div>
						</div>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col">
						<div class="card">
							<div class="card-body">
								Bước 2: Nhập vào domain
							</div>
						</div>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col">
						<div class="card">
							<div class="card-body">
								Bước 3: Click "Check" button
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<h2 class="center">Check SSL chỉ với 3 bước đơn giản</h2>
				<p class="mt-4 center">Thêm domain để theo dõi và nhận cảnh báo một cách tiện lợi</p>
			</div>
		</div>
		<!-- %hr/ -->
		<div class="mt-5"></div>
		<div class="row align-items-center">
			<div class="col-md-6 mt-2">
				<h2 class="center">Làm sao để quản lí và theo dõi SSL một cách tiện lợi nhất?</h2>
				<p class="mt-4 center">
					Đăng nhập và trải nghiệm với tính năng "My SSL"
				</p>
			</div>
			<div class="col-md-6 mt-2">
				<img class="img-fluid" src="https://i.imgur.com/KJgdTsy.png" style="box-shadow: 0px 0px 1rem 0rem #999999">
			</div>
		</div>
		<hr>
		<div class="row mt-4">
			<h2 class="center">Chúng tôi có thể giúp bạn:</h2>
		</div>
		<div class="row mt-4">
			<div class="col-md-6">
				<p class="lead">Nhận thông báo kịp thời trước khi SSL hết hạn</p>
				<ul>
					<li>Tùy chỉnh thời gian thông báo trước khi hết hạn</li>
					<li>Tùy chỉnh thời gian nhận lại thông báo nếu SSL chưa được renew</li>
				</ul>
			</div>
			<div class="col-md-6">
				<p class="lead">Cảnh báo tiện lợi thông qua</p>
				<ul>
					<li>Email</li>
					<li>Telegram</li>
				</ul>
			</div>
		</div>
		<hr>
	</div>
	<div class="footer">
		<div class="center">
			<ul class="list-inline">
				<li class="list-inline-item"><a href="terms">Điều khoản</a></li>
				<li class="list-inline-item"><a href="privacy">Chính sách</a></li>
				<li class="list-inline-item"><a href="#">Liên hệ</a></li>
			</ul>
		</div>
	</div>
@stop