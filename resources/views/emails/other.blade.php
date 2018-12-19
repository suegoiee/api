@extends('emails.layout')
@section('title')
	{{$title}}
@endsection
@section('content')
	{!!$content!!}
@endsection
@section('btn_start')
	<a href="{{env('APP_FRONT_URL','https://pro.uanalyze.com.tw')}}" style="text-decoration: none;color: #FFF;">
@endsection
@section('btn_text')
	查看詳細資料
@endsection
@section('btn_end')
	</a>
@endsection
