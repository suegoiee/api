@extends('emails.layout')
@section('title')
	付款確認通知
@endsection
@section('content')
	<p>感謝您訂購優分析商品，我們已於({{$payment_date}})收到您的付款資料(訂單編號{{$order_no}})</p>
	<p>系統會自動開通您購買的模組，並顯示於"我的達人策略"裡，<a href="{{env('APP_FRONT_URL','https://pro.uanalyze.com.tw')}}" target="_blank">登入帳號</a>即可立即使用。</p>
	<p>操作方式如下圖所示：</p>
	<div style="width:100%;padding:5px;">
		<img src="{{url('images/PaymentReceive_pic.jpg')}}" style="max-width: 100%">
	</div>
    <p style="margin-bottom: 0;">投資順利</p>
@endsection
@section('btn_start')
	<a href="{{env('APP_FRONT_URL','https://pro.uanalyze.com.tw')}}" style="text-decoration: none;color: #FFF;">
@endsection
@section('btn_text')
	前往優分析
@endsection
@section('btn_end')
</a>
<hr/>
	<p style="font-size:11px;margin:0;padding-left: 10px;">本信件由系統自動發送，請勿直接回信，感謝您的配合!</p>
	<p style="font-size:11px;margin:0;padding-left: 10px;">如果您有任何疑問或建議，歡迎您直接連絡我們。</p>
@endsection