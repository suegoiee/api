@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/form.css')}}">
@endsection
@section('content')
<div id="content" class="container pt-5">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/admin/'.str_plural($module_name))}}"><span class="">{{trans($module_name.'.admin.title')}}</span></a></li>
        <li class="breadcrumb-item active">
            <span class="">
               {{ $data ? trans($module_name.'.admin.edit_title') : trans($module_name.'.admin.new_title') }}
            </span>
        </li>
    </ol>
    
    @include('admin.form_error')
    <form id="form" class="mt-5" action="{{$data ? url('/admin/'.str_plural($module_name).'/'.$data->id) : url('/admin/'.str_plural($module_name))}}" method="POST" enctype="multipart/form-data">
        @include('admin.'.$module_name.'.form')
        {{ $data ? method_field('PUT'):'' }}
        {{ csrf_field() }}
        <div class="actions form-group text-center" >
            <button type="submit" name="action" value="save" class="btn btn-info">{{trans('form.save')}}</button>
            <button type="submit" name="action" value="save_exit" class="btn btn-info">{{trans('form.save_exit')}}</button>
            <a href="{{url('/admin/'.str_plural($module_name))}}" class="btn btn-warning cancel">{{trans('form.cancel')}}</a>
        </div>
    </form>
</div>
@endsection

@section('javascript')
<script>
$(function(){
   
});
</script>
<script src="{{asset('js/'.$module_name.'/form.js')}}"></script>
@endsection
