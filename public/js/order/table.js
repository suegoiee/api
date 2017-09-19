function statusFormatter(value,row,index){
	switch(value){
		case 0:return '未付款';
		case 1:return '已付款';
	}
	return value;
}

function user_nick_nameFormatter(value,row,index){
	return value;
}