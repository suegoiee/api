function actionFormatter(value,row,index){
	var actions='<div class="actions">';
	console.log($("#editPermission"));
	if($("#editPermission")){
		actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/edit"><span class="oi oi-pencil edit-btn"></span></a>';
	}
	if($("#deletePermission")){
		actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/delete"><span class="oi oi-trash delete-btn"></span></a>';
	}
	actions+='</div>';
	return actions;
}
function queryParams(params){
	return params;
}