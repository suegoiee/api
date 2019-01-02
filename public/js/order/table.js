function statusFormatter(value,row,index){
	switch(value){
		case 0:return '未付款';
		case 1:return '已付款';
		case 2:return '付款失敗';
		case 3:return '已取號';
		case 4:return '取好失敗';
	}
	return value;
}

function user_nick_nameFormatter(value,row,index){
	return value;
}

function user_emailFormatter(value,row,index){
	return value;
}