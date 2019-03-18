<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	@yield('title')
	<base href="{{asset('')}}">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	
	<link rel="shortcut icon" href="public/static/images/logo-shortcut.jpg" />
	<!-- Local bootstrap CSS & JS -->
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<!-- <link rel="stylesheet" media="screen" href="static/bootstrap/css/bootstrap.min.css">
	<link rel="stylesheet" href="css/web.css">
	<script src="static/jquery/jquery-3.3.1.min.js"></script>
	<script src="static/bootstrap/js/bootstrap.min.js"></script> -->
	<!-- Latest compiled and minified CSS -->
	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css">
	<!-- jQuery library -->
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
	<!-- Popper JS -->
	<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"></script>
	<!-- Latest compiled JavaScript -->
	<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"></script>
	<link rel="stylesheet" href="public/static/css/web.css">
</head>
<body>
	@yield('navbar')
	@yield('content')
	@yield('script')
</body>
</html>