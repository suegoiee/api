@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/list.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.css')}}">
@endsection
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{url('admin/'.str_plural($module_name))}}">
            <span class="">{{trans($module_name.'.admin.menu_title')}}</span>
            </a>
        </li>
        <li class="breadcrumb-item {{isset($subtitle) ? '':'active'}}">
            <span class="">{{$analyst->name}} {{trans($module_name.'.admin.grant_menu_title')}}</span>
        </li>
        @if(isset($subtitle))
            <li class="breadcrumb-item active">
                <span class="">{{trans($module_name.'.admin.grant_detail_menu_title')}}</span>
            </li>
        @endif
        @if(@in_array('new',$actions))
            <li class="float-right"><a href="{{url('/admin/'.str_plural($module_name).'/'.$analyst->id.'/grants/create')}}" class="btn btn-info" >{{trans($module_name.'.admin.grant_new_label')}}</a></li>
        @endif
    </ol>
    @include('admin.list_error')
    @include('admin.table')
@endsection

@section('javascript')
<script src="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-table/locale/bootstrap-table-zh-TW.min.js')}}"></script>
<script>
var module_name = "{{str_plural($module_name)}}";
var analyst_id = "{{$analyst->id}}";
$(function(){
    var data = {!!json_encode($table_data)!!};
    $('#table').bootstrapTable('load',data);

    $('#delete_action').on('click',function(event){
        selections = $('#table').bootstrapTable('getSelections').map(function(item,index){return item.id});
        if(selections.length==0){
            alert('{{trans("table.no_selected")}}')
        }else{
            if(confirm('{{trans("table.confirm_del")}}')){
                $.ajax({
                    method: "DELETE",
                    url: url('/admin/'+module_name+'/'+analyst_id+'/grants'),
                    data: {id:selections}
                }).done(function( result ) {
                    if(result.status=='success'){                        
                        $('#table').bootstrapTable('remove', {field: 'id', values: selections});
                    }
                });
            }
        }
    });

    $('#table').on('click','.edit-btn',function(event){

    });

    $('#table').on('click','.delete-btn',function(event){
        if(confirm('確定刪除?')){
            return true;
        }
        return false;
    });
});
</script>
<script src="{{asset('js/table.js')}}"></script>
<script src="{{asset('js/'.$module_name.'/grant_table.js')}}"></script>
@if(isset($table_js))
<script src="{{asset('js/'.$module_name.'/'.$table_js.'.js')}}"></script>
@endif
@endsection
