function nicknameFormatter(value,row,index){
	return row.profile ? row.profile.nickname:'';
}
function actionFormatter(value,row,index){
	var actions='<div class="actions">';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'"><span class="oi oi-eye"></span></a>';
	//actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/edit"><span class="oi oi-pencil"></span></a>';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/delete"><span class="oi oi-trash delete-btn"></span></a>';
	actions+='</div>';
	return actions;
}