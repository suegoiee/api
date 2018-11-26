@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/multi-select/css/multi-select.css')}}">
@endsection
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{url('/admin/'.str_plural($module_name))}}"><span class="">{{trans($module_name.'.admin.title')}}</span></a></li>
        <li class="breadcrumb-item active">
            <span class="">
                {{trans($module_name.'.admin.'.$actionName.'_title')}}
            </span>
        </li>
    </ol>
    <form id="form" class="mt-5" action="{{url('/admin/'.str_plural($module_name).'/assigned')}}" method="POST" >
        @include('admin.'.$module_name.'.assigned')
        {{ csrf_field() }}
        <div class="actions form-group text-center" >
            <button type="submit" name="action" value="save_exit" class="btn btn-info">{{trans('assigned.assigned')}}</button>
            <a href="{{url('/admin/'.str_plural($module_name))}}" class="btn btn-warning cancel">{{trans('assigned.cancel')}}</a>
        </div>
    </form>
@endsection

@section('javascript')
<script src="{{asset('thirdparty/jquery.quicksearch/jquery.quicksearch.js')}}"></script>
<script src="{{asset('thirdparty/multi-select/js/jquery.multi-select.js')}}"></script>
<script src="{{asset('js/'.$module_name.'/assigned.js')}}"></script>
@endsection
