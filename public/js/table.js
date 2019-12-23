function actionFormatter(value,row,index){
	var actions='<div class="actions">';
	console.log($("#editPermission").length);
	if($("#editPermission").length != 0){
		console.log('edit');
		actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/edit"><span class="oi oi-pencil edit-btn"></span></a>';
	}
	if($("#deletePermission").length != 0){
		actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/delete"><span class="oi oi-trash delete-btn"></span></a>';
		console.log('delete');
	}
	actions+='</div>';
	return actions;
}
function queryParams(params){
	return params;
}