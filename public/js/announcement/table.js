function idFormatter(value,row,index){
	return value;
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
    $.get(url('admin/'+module_name+'/data') + '?' + $.param(params.data)).then(function (res) {
      params.success(res);
    })
  }