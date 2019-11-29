@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/list.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.css')}}">
@endsection
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item active">
            <span>{{trans($module_name.'.admin.menu_title')}}
            </span>
        </li>
        @if(@in_array('new',$actions))
        @can('permission', [ucfirst($module_name).'Controller', 'create'])
            <li class="float-right"><a href="{{url('/admin/'.str_plural($module_name).'/create')}}" class="btn btn-info" >{{trans($module_name.'.admin.new_label')}}</a></li>
        @endcan
        @endif
        @if(@in_array('import',$actions))
        @can('permission', [ucfirst($module_name).'Controller', 'import'])
            <li class="float-right"><a href="{{url('/admin/'.str_plural($module_name).'/import')}}" id="import" class="btn btn-info" >{{trans($module_name.'.admin.import_label')}}</a></li>
        @endcan
        @endif
        @if(@in_array('assigned',$actions))
        @can('permission', [ucfirst($module_name).'Controller', 'assigned'])
            <li class="float-right"><a href="{{url('/admin/'.str_plural($module_name).'/assigned')}}" id="assigned" class="btn btn-primary" >{{trans($module_name.'.admin.assigned_label')}}</a></li>
        @endcan
        @endif
        @if(@in_array('sorted',$actions))
        @can('permission', [ucfirst($module_name).'Controller', 'sorted'])
            <li class="float-right"><a href="{{url('/admin/'.str_plural($module_name).'/sorted')}}" id="sorted" class="btn btn-success" >{{trans($module_name.'.admin.sorted_label')}}</a></li>
        @endcan
        @endif
    </ol>
    @include('admin.list_error')
    @if(isset($tabs))
        <ul class="nav nav-tabs" role="tablist">
            @foreach($tabs as $key => $tab)
                @foreach($tab as $value)
                    <li class="nav-item" role="presentation" ><a class="nav-link {!! isset($query_string[$key]) && $query_string[$key] == $value? 'active':'' !!}"  href="{{url('/admin/'.str_plural($module_name).'?'.$key.'='.$value)}}"  >{{trans($module_name.'.admin.'.$key.'_'.$value)}}</a></li>
                @endforeach
            @endforeach
        </ul>
    @endif
    @include('admin.table')
@endsection

@section('javascript')
<script src="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-table/locale/bootstrap-table-zh-TW.js')}}"></script>
<script src="{{asset('thirdparty/Blob.js/Blob.min.js')}}"></script>
<script src="{{asset('thirdparty/tableExport/libs/FileSaver/FileSaver.min.js')}}"></script>
<script src="{{asset('thirdparty/tableExport/tableExport.min.js')}}"></script>
<script>
var module_name = "{{str_plural($module_name)}}";
var query_data = {!! isset($query_data) ? json_encode($query_data) : "{}"!!};
$(function(){
    var data = {!!json_encode($table_data)!!};
    $('#table').bootstrapTable('load',data);

    $('#delete_action').on('click',function(event){
        var primaryKey = 'id';
        selections = $('#table').bootstrapTable('getSelections').map(function(item,index){ 
            if (typeof item.id == 'undefined') {
                primaryKey = 'no';
                return item.no;
            }
            return item.id;
        });
        if(selections.length==0){
            alert('{{trans("table.no_selected")}}')
        }else{
            if(confirm('{{trans("table.confirm_del")}}')){
                $.ajax({
                    method: "DELETE",
                    url: url('/admin/'+module_name),
                    data: {id:selections}  
                }).done(function( result ) {
                    if(result.status=='success'){                        
                        $('#table').bootstrapTable('remove', {field: primaryKey, values: selections});
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

    $('#export_action').on('click',function(event){
        
        selections = $('#table').bootstrapTable('getSelections').map(function(item,index){
            return item.id || item.no;
        });
        if(selections.length==0){
            alert('{{trans("table.no_selected")}}');
            return;
        }
        var form = $('<form></form>').attr('action', url('/admin/'+module_name+'/export')).attr('method', 'post');
        form.append($("<input></input>").attr('type', 'hidden').attr('name', '_token').attr('value', '{{ csrf_token() }}' ));
        $.each(selections, function(index, value) {
            form.append($("<input></input>").attr('type', 'text').attr('name', 'ids[]').attr('value',  value));
        });
        form.appendTo('body').submit().remove();
    });
});
</script>
<script src="{{asset('js/table.js')}}"></script>
<script src="{{asset('js/'.$module_name.'/table.js')}}"></script>
@endsection
