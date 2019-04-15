@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/list.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.css')}}">
    <link rel="stylesheet" href="{{asset('../thirdparty/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
@endsection
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{url('admin/'.str_plural($module_name))}}">
            <span class="">{{trans($module_name.'.admin.menu_title')}}</span>
            </a>
        </li>
        @if(isset($subtitle))
            <li class="breadcrumb-item active">
                <span class="">{{$referrer->name}} {{trans($module_name.'.admin.grant_detail_menu_title')}}</span>
            </li>
        @endif
        @if(@in_array('new',$actions))
            <li class="float-right"><a href="{{url('/admin/'.str_plural($module_name).'/'.$referrer->id.'/grants/create')}}" class="btn btn-info" >{{trans($module_name.'.admin.grant_new_label')}}</a></li>
        @endif
    </ol>
    @include('admin.list_error')
    <div id="table-toolbar">
        <form id="tool_form" action="{{url('/admin/referrers/'.$referrer->id.'/orders')}}" method="GET">
        @if(@in_array('date_range',$tools))
            <div class="input-group input-daterange mt-2" data-provide="datepicker" data-date-language="zh-TW" data-date-format="yyyy-mm-dd">
                <label id="date" class="text-center mr-1">
                    {{trans('table.daterange_label')}}
                </label>
                <input type="text-2 text" class="form-control dates" name="start_date" value="{{isset($query_string['start_date'])? $query_string['start_date']:date('Y-m').'-01'}}">
                <span class="mx-2 line"> - </span>
                <input type="text-2" class="form-control dates" name="end_date" value="{{isset($query_string['end_date'])? $query_string['end_date']:date('Y-m-d')}}" >
                <button type="submit" class="btn ml-3 text-center btn-default">
                    {{trans('table.search_label')}}
                </button>
            </div>
        @endif
        </form>
    </div>
    @include('admin.table')
@endsection

@section('javascript')
<script src="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-table/locale/bootstrap-table-zh-TW.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js')}}"></script>
<script>
var module_name = "{{str_plural($module_name)}}";
var analyst_id = "{{$referrer->id}}";
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
