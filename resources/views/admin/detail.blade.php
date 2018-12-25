@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/detail.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.css')}}">
@endsection
@section('content')
<ol class="breadcrumb">
    <li class="breadcrumb-item"><a href="{{url('/admin/'.str_plural($module_name))}}"><span class="">{{trans($module_name.'.admin.title')}}</span></a></li>
    <li class="breadcrumb-item active">
        <span class="">
            {{$data ? $title_field:''}}
        </span>
    </li>
</ol>
@include('admin.'.$module_name.'.detail')
@endsection

@section('javascript')

<script src="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-table/locale/bootstrap-table-zh-TW.min.js')}}"></script>
<script src="{{asset('js/order/detail.js')}}"></script>
<script>
$(function(){
   
});
</script>
@endsection
