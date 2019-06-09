function started_atFormatter(value,row,index){

	if(value){
		return (value.split(' ')[0]);
	}
	return '即日起';
	
}

function ended_atFormatter(value,row,index){
	if(value){
		return (value.split(' ')[0]);
	}
	return '無期限';
	
}
function actionFormatter(value,row,index){
	var actions='<div class="actions">';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/edit"><span class="oi oi-pencil edit-btn"></span></a>';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/delete"><span class="oi oi-trash delete-btn"></span></a>';
	actions+='</div>';
	return actions;
}
function queryParams(params) {
	if('status'  in query_data){
    	params.status = query_data.status;
	}
    return params
  }
 function ajaxRequest(params) {
    $.get(url('admin/events/data') + '?' + $.param(params.data)).then(function (res) {
      params.success(res);
    })
  }
$(function(){
	 $('#table').on('click','.cancel-btn',function(event){
        if(confirm('確定退訂?')){
            return true;
        }
        return false;
    });
})