function actionFormatter(value,row,index){
	var actions='<div class="actions">';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.analyst_id+'/grants/'+row.id+'/edit"><span class="oi oi-pencil edit-btn"></span></a>';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.analyst_id+'/grants/'+row.id+'/delete"><span class="oi oi-trash delete-btn"></span></a>';
	actions+='</div>';
	return actions;
}