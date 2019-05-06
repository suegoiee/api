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
@endsection

<hr>
<a href="">
	
</a>
