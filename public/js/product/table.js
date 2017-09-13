function statusFormatter(value,row,index){
	switch(value){
		case 0:return '下架';
		case 1:return '上架';
	}
	return value;
}