function actionFormatter(value,row,index){
	/*var actions='<div class="actions">';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/grants"><span class="oi oi-document grant-btn"></span></a>';
	
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/edit"><span class="oi oi-pencil edit-btn"></span></a>';
	//actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/delete"><span class="oi oi-trash delete-btn"></span></a>';
	
	actions+='</div>';
	return actions;*/
	var actions='<div class="actions">';
	console.log($("#editPermission").length);
	if($("#grantPermission").length != 0){
		console.log('edit');
		actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/grants"><span class="oi oi-document grant-btn"></span></a>';
	}
	if($("#deletePermission").length != 0){
		actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/edit"><span class="oi oi-pencil edit-btn"></span></a>';
		console.log('delete');
	}
	actions+='</div>';
	return actions;
}