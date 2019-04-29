function nicknameFormatter(value,row,index){
	return row.profile ? row.profile.nickname:'';
}
function actionFormatter(value,row,index){
	var actions='<div class="actions">';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'"><span class="oi oi-eye"></span></a>';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/edit"><span class="oi oi-pencil"></span></a>';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/delete"><span class="oi oi-trash delete-btn"></span></a>';
	actions+='</div>';
	return actions;
}
 function ajaxRequest(params) {
    $.get(url('admin/users/data') + '?' + $.param(params.data)).then(function (res) {
      params.success(res);
    })
  }
function mail_verified_atFormatter(value,row,index){
	return row.is_socialite == 1 ? 'Facebook': ( value ? '已驗證':'尚未驗證');
}