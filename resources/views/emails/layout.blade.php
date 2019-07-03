<div style="background-color:#f4f4f4;width:100%;padding: 50px 0; font-family: arial,'微軟正黑體','黑體';">
	<div style="width:80%; margin:0 auto; min-width: 320px; border-bottom: 3px solid #C8C8C8;">
		<div style="background:transparent url({{url('images/ua-logo-b.png')}}) no-repeat; background-size: 25%; background-position: left center; height: 60px;"></div>
		<div style="background-color:#FFF;padding: 10px 0 20px 0; font-size:16px;color:#000;">
			@if(!isset($is_pic))
				@if(!isset($type))
				<div style="height: 240px;margin: 0 10px;background: url({{url('images/'.$header_pic)}}) no-repeat; background-position: center center;">
				</div>
				@endif
				<p style="color:#000;font-size: 20px;font-weight: bolder;padding:0 35px;">
					@yield('title')
				</p>
				<hr style="border-bottom: 1px solid #C8C8C8;border-top: 0; width:95%;margin-top: 0;" />
			@endif
			<div style="padding:0 30px;color:#000;font-size: 14px;">
			@if(!isset($type))
				<p>{{ $nickname != '' ? $nickname.'您好：': ''  }}</p>
			@endif
				<div style="min-height: 200px">
					@yield('content')
				</div>
			@if(!isset($type))
				<p>優分析會員服務部</p>
			@endif
			</div>
			@yield('btn_start')
			<div style="width:50%; margin:40px auto 0 auto;background-color: #f29600;color: #fff;font-size: 18px;text-align: center;cursor: pointer;padding:10px">
				@yield('btn_text')
			</div>
			@yield('btn_end')
		</div>
	</div>
</div>