@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/sorted.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/jquery-ui-sortable/jquery-ui.min.css')}}">
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
    <form id="form" class="mt-5" method="post"  action="{{url('/admin/'.str_plural($module_name).'/sorted')}}" method="POST" >
            @include('admin.'.$module_name.'.sorted')
            {{ csrf_field() }}
            <div class="actions form-group text-center" >
                <button type="submit" name="action" value="save_exit" class="btn btn-info">{{trans('sorted.sorted')}}</button>
                <a href="{{url('/admin/'.str_plural($module_name))}}" class="btn btn-warning cancel">{{trans('sorted.cancel')}}</a>
            </div>
        </form>
@endsection

@section('javascript')
<script src="{{asset('thirdparty/jquery-ui-sortable/jquery-ui.min.js')}}"></script>
<script src="{{asset('js/'.$module_name.'/sorted.js')}}"></script>
<script>
$(document).ready(function() {
    $( "#sortable" ).sortable({
      placeholder: "ui-state-highlight"
    });
    $('#btn_finish').click(function(event){
        $('#sorted_form').submit();
    });
    $('#btn_reset').click(function(event){
        return confirm('是否要重設所有順序?');
    });
});
</script>
@endsection
