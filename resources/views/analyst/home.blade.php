@extends('analyst.layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/home.css')}}">
    <link rel="stylesheet" href="{{asset('css/dashboard.css')}}">
@endsection
@section('content')
    @include('analyst.dashboard.counter')
@endsection

@section('javascript')
<script src="{{url('thirdparty/d3/d3.min.js')}}"></script>
<script>
$(function(){
	$('.number').each(function(d){
		var that = $(this);
		var end_number = that.data('endnumber');
		var increase = Math.ceil(end_number/30);
		var current = 0;
		var number_interval_id = setInterval(function(){
			if(current<end_number){
				current += increase;
				that.html(current);
			}else{
				clearInterval(number_interval_id);
			}
		},30);
	});
});
</script>
@endsection