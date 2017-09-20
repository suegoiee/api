@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/detail.css')}}">
@endsection
@section('content')
<div id="content" class="container pt-5">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/admin/'.str_plural($module_name))}}"><span class="">{{trans($module_name.'.admin.title')}}</span></a></li>
        <li class="breadcrumb-item active">
            <span class="">
                {{$data ? $title_field:''}}
            </span>
        </li>
    </ol>
        @include('admin.'.$module_name.'.detail')
</div>
@endsection

@section('javascript')
<script>
$(function(){
   
});
</script>
@endsection
