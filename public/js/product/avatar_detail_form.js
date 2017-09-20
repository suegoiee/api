$(function(){
 	var avatar_detail_index=0;
	$("#new_avatar_detail_btn").click(function(event){
		event.preventDefault();
		var avatar_detail_html='<div class="input-group">'+
								'<input type="file" class="form-control" name="avatar_detail[]" value="">'+
								'<span class="input-group-btn">'+
									'<button class="btn btn-danger remove_btn" type="button"><span class="oi oi-trash"></span></button>'+
								'</span>'+
							'</div>';
		avatar_detail_index++;
		$('#file_list').prepend(avatar_detail_html);
	});
	$('#file_list').on('click','.remove_btn',function(event){
		event.preventDefault();
		var row = $(this);
		if($('#file_list input[type=file]').length>1){
       		row.parent().parent().remove();
       	}
	});
	$('#avatar_detail_list').on('click','.remove_btn',function(event){
		event.preventDefault();
		var row = $(this);
		if(confirm('確定刪除 ?')){
        	row.parent().parent().remove();
    	}
	});
});
