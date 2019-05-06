@extends('emails.layout')
@section('title')
	系統公告
@endsection
@section('content')
	{!!$content!!}
@endsection
@section('btn_start')
	<a href="{{env('APP_FRONT_URL','https://pro.uanalyze.com.tw')}}" style="text-decoration: none;color: #FFF;">
@endsection
@section('btn_text')
	前往優分析
@endsection
@section('btn_end')
	</a>
	<hr>
	<div style="font-size:9px; text-align: right;">
		欲取消訂閱優分析最新消息請於網站內的個人資料頁面取消訂閱(
		<a href="{{env('APP_FRONT_URL','https://pro.uanalyze.com.tw').'porfile'}}">
			個人資料
		</a>)
	</div>
@endsection

