@extends('emails.layout')
@section('header')
	<div style="height: 200px;background: url({{url('images/notification_a.jpg')}}) no-repeat; background-position: center center;">
	</div>
@endsection
@section('content')
	{!!$content!!}
@endsection
