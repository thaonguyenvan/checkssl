<h2>
	Những domain sau đây sắp hết hạn:
</h2>
<ul>
	@foreach($domain_expired as $domain)
		<li>{{$domain['domain']}} - Số ngày còn lại: {{$domain['dayleft']}}</li>
	@endforeach
</ul>
<p>Xem thêm thông tin tại: https://supportdao.io </p>