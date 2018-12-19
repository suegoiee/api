@extends('emails.layout')
@section('title')
	恭喜您獲得優惠券
@endsection
@section('content')
	@foreach($promocodes as $promocode)
	<div style="float:left; width: 60%;">
		<div style="margin-bottom: 5px;">
			恭喜您獲得優分析{{$promocode->name}}1組，
		</div>
		<div style="margin-bottom: 5px;">
			使用期限：{{$promocode->deadline ? date('Y/m/d', strtotime($promocode->deadline)) :'無期限'}}
		</div>
		<div style="margin-bottom: 5px;">
			優惠碼
		</div>
		<div style="background-color: #d2f5ff;padding: 10px;text-align: center;">
			{{$promocode->code}}
		</div>
	</div>
	<div style="float:left; width: 40%;text-align: right; font-weight: bold;">
		<div>$<span style="font-size:72px;vertical-align: -webkit-baseline-middle;">{{$promocode->offer}}</span>
		</div>
		<div style="font-weight: bolder;">優惠券</div>
	</div>
	@endforeach
@endsection
@section('btn_start')
	<a href="{{env('APP_FRONT_URL','https://pro.uanalyze.com.tw')}}e-com/product-view/all" style="text-decoration: none;color: #FFF;">
@endsection
@section('btn_text')
	立即使用
@endsection
@section('btn_end')
	</a>
@endsection