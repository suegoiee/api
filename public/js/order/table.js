function idFormatter(value,row,index){
	return value;
}
function statusFormatter(value,row,index){
	switch(value){
		case 0:return '未付款';
		case 1:return '已付款';
		case 2:return '付款失敗';
		case 3:return '已取號';
		case 4:return '取好失敗';
		case 5:return '已退訂';
	}
	return value;
}

function user_profile_nicknameFormatter(value,row,index){
	return value;
}

function user_emailFormatter(value,row,index){
	return value;
}

function productsFormatter(value, row, index){
	var html = '';
	for (var i = 0; i < value.length; i++) {
		html += '<div>'+value[i].name+'</div>';
	}
	return html;
}
function actionFormatter(value,row,index){
	var actions='<div class="actions">';
	actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/edit"><span class="oi oi-pencil edit-btn"></span></a>';
	actions+=' ';
	if(row.status==1 && row.price!=0){
		actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/cancel"><span class="oi oi-action-undo cancel-btn"></span>';
	}
	//actions+='<a href="'+url('/admin/'+module_name)+'/'+row.id+'/delete"><span class="oi oi-trash delete-btn"></span></a>';
	actions+='</div>';
	return actions;
}
function queryParams(params) {
	if( 'free'  in query_data){
    	params.free = query_data.free;
	}
	if('status'  in query_data){
    	params.status = query_data.status;
	}
    return params
  }
 function ajaxRequest(params) {
    $.get(url('admin/orders/data') + '?' + $.param(params.data)).then(function (res) {
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