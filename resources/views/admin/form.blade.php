@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-multiselect/css/bootstrap-multiselect.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/chosen/chosen.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/multi-select/css/multi-select.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-select/css/bootstrap-select.css')}}">

    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min.css')}}">
    @if($module_name=='edm' || $module_name=='product')
        <link rel="stylesheet" href="{{asset('css/'.$module_name.'/sorted.css')}}">
    @endif
@endsection
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/admin/'.str_plural($module_name))}}"><span class="">{{trans($module_name.'.admin.menu_title')}}</span></a></li>
        <li class="breadcrumb-item active">
            <span class="">
                {{trans($module_name.'.admin.'.$actionName.'_title')}}
            </span>
        </li>
    </ol>
    @include('admin.form_error')
    <form id="form" class="mt-5" action="{{$data ? url('/admin/'.str_plural($module_name).'/'.$data->id) : url('/admin/'.str_plural($module_name))}}" method="POST" enctype="multipart/form-data">
        @include('admin.'.$module_name.'.form')
        {{ $data ? method_field('PUT'):'' }}
        {{ csrf_field() }}
        @if(isset($send_actions))
            <div class="actions form-group text-center" >
                <button type="submit" name="action" value="save_exit" class="btn btn-info" id="send_btn">{{trans('form.send')}}</button>
                <a href="{{url('/admin/'.str_plural($module_name))}}" class="btn btn-warning cancel">{{trans('form.cancel')}}</a>
            </div>
        @else
            <div class="actions form-group text-center" >
                <button type="submit" name="action" value="save" class="btn btn-info">{{trans('form.save')}}</button>
                <button type="submit" name="action" value="save_exit" class="btn btn-info">{{trans('form.save_exit')}}</button>
                <a href="{{url('/admin/'.str_plural($module_name))}}" class="btn btn-warning cancel">{{trans('form.cancel')}}</a>
            </div>
        @endif
    </form>
@endsection

@section('javascript')
<script src="{{asset('thirdparty/moment/moment.js')}}"></script>
<script src="{{asset('thirdparty/moment/locales/zh-tw.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-multiselect/js/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('thirdparty/chosen/chosen.jquery.js')}}"></script>
<script src="{{asset('thirdparty/jquery.quicksearch/jquery.quicksearch.js')}}"></script>
<script src="{{asset('thirdparty/multi-select/js/jquery.multi-select.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-select/js/bootstrap-select.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script>
$(function(){
   $('#form').on('keyup keypress','input[type=text]',function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) { 
        e.preventDefault();
        return false;
      }
    });
});
</script>
@if($module_name=='edm' || $module_name=='product')
<script src="{{asset('thirdparty/jquery-ui-sortable/jquery-ui.min.js')}}"></script>
@endif
<script src="{{asset('js/'.$module_name.'/form.js')}}"></script>
<script src="{{asset('thirdparty/ckeditor/ckeditor.js')}}"></script>
@endsection
