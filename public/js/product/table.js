function statusFormatter(value,row,index){
	switch(value){
		case 0:return '下架';
		case 1:return '上架';
	}
	return value;
}
function plansFormatter(value, row, index){
	var html = '';
	for (var i = 0; i < value.length; i++) {
		html += '<div>'+value[i].price+'</div>';
		break
	}
	return html;
}