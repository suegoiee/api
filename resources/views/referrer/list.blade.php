@extends('analyst.layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('../css/analyst/list.css')}}">
    <link rel="stylesheet" href="{{asset('../thirdparty/bootstrap-table/analyst/bootstrap-table.min.css')}}">
    <link rel="stylesheet" href="{{asset('../thirdparty/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
@endsection
@section('content')
<div class="card">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" id="bread">
            <span>{{trans($module_name.'.analyst.menu_title')}}</span>
        </li>
        <li class="breadcrumb-item active">
            <span>{{trans($module_name.'.analyst.'.$subtitle.'_title')}}</span>
        </li>
        @if(@in_array('new',$actions))
            <li class="float-right">
                <a href="{{url('/analyst/'.str_plural($module_name).'/create')}}" class="btn btn-info">{{trans($module_name.'.analyst.new_label')}}</a>
            </li>
        @endif
        @if(@in_array('import',$actions))
            <li class="float-right">
                <a href="{{url('/analyst/'.str_plural($module_name).'/import')}}" id="import" class="btn btn-info" >{{trans($module_name.'.analyst.import_label')}}</a>
            </li>
        @endif
        @if(@in_array('assigned',$actions))
            <li class="float-right">
                <a href="{{url('/analyst/'.str_plural($module_name).'/assigned')}}" id="assigned" class="btn btn-primary" >{{trans($module_name.'.analyst.assigned_label')}}</a>
            </li>
        @endif
        @if(@in_array('sorted',$actions))
            <li class="float-right">
                <a href="{{url('/analyst/'.str_plural($module_name).'/sorted')}}" id="sorted" class="btn btn-success" >{{trans($module_name.'.analyst.sorted_label')}}</a>
            </li>
        @endif
    </ol>
    @include('analyst.list_error')
    @if(isset($tabs))
        <ul class="nav nav-tabs" role="tablist">
            @foreach($tabs as $key => $tab)
                @foreach($tab as $value)
                    <li class="nav-item" role="presentation" >
                        <a class="nav-link {!! isset($query_string[$key]) && $query_string[$key] == $value? 'active':'' !!}"  href="{{url('/analyst/'.str_plural($module_name).'?'.$key.'='.$value)}}" >{{trans($module_name.'.analyst.'.$key.'_'.$value)}}
                        </a>
                    </li>
                @endforeach
            @endforeach
        </ul>
    @endif
    @include('analyst.table')
@endsection
</div>
@section('javascript')
<script src="{{asset('thirdparty/bootstrap-table/bootstrap-table.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-table/locale/bootstrap-table-zh-TW.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js')}}"></script>
<script>
var module_name = "{{str_plural($module_name)}}";
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
                    url: url('/analyst/'+module_name),
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
<script src="{{asset('js/analyst/table.js')}}"></script>
<script src="{{asset('js/analyst/'.$module_name.'/table.js')}}"></script>
@endsection
