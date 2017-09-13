@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/list.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.css')}}">
@endsection
@section('content')
<div id="content" class="container pt-5">
    <ol class="breadcrumb">
        <li class="breadcrumb-item active"><span class="">{{trans($module_name.'.admin.title')}}</span></li>
        <li class="float-right"><a href="{{url('/admin/'.str_plural($module_name).'/create')}}" class="btn btn-info" >{{trans($module_name.'.admin.new_label')}}</a></li>
    </ol>
    @include('admin.table')
</div>
@endsection

@section('javascript')
<script src="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.js')}}"></script>
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
                    url: url('/admin/'+module_name),
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

        }
    });
});
</script>
<script src="{{asset('js/table.js')}}"></script>
<script src="{{asset('js/'.$module_name.'/table.js')}}"></script>
@endsection
