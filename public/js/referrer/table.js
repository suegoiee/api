function actionFormatter(value,row,index){
	var actions='<div class="actions">';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/orders"><span class="oi oi-credit-card order-btn"></span></a>';
	
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/grants"><span class="oi oi-document grant-btn"></span></a>';
	
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/edit"><span class="oi oi-pencil edit-btn"></span></a>';
	//actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/delete"><span class="oi oi-trash delete-btn"></span></a>';
	
	actions+='</div>';
	return actions;
}