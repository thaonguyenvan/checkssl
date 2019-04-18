<h2>
	Những domain sau đây sắp hết hạn:
</h2>
<ul>
	@foreach($ssl_expired as $ssl)
		<li>{{$ssl['domain']}} - Số ngày còn lại: {{$ssl['dayleft']}}</li>
	@endforeach
</ul>
<p>Xem thêm thông tin tại: https://supportdao.io </p>