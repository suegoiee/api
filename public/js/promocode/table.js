$(function(){
	$('#import').click(function(event){
		event.preventDefault();
		if($('#importModalCenter').length!=0){
			$('#importModalCenter').modal('show');
		}else{
			$.get(url('admin/promocodes/import'),function(response){
				$('body').append(response);
				$('#importModalCenter').modal('show');
			});
		}
		return false;
	});

});

function user_nameFormatter(value,row,index){
	if(value == 0){
		return '未分配';
	}
	return value;
}

function deadlineFormatter(value,row,index){
	if(value == null){
		return '無期限';
	}
	return value;
}

function used_atFormatter(value,row,index){
	if(value != null){
		return '是';
	}
	return '否';
}