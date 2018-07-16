function stock_industriesFormatter(value,row,index){
	switch(value){default:return '無';
		case '0':return '無';
		case '1':return 'CI';
		case '2':return 'BASI';
		case '3':return 'BD';
		case '4':return 'FB';
		case '5':return 'INS';
		case '6':return 'MIM';
	}
	return '無';
}
function actionFormatter(value,row,index){
	var actions='<div class="actions">';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.no+'/edit"><span class="oi oi-pencil edit-btn"></span></a>';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.no+'/delete"><span class="oi oi-trash delete-btn"></span></a>';
	actions+='</div>';
	return actions;
}