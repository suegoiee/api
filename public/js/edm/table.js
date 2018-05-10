function statusFormatter(value, row, index){
	switch(value){
		default:return '';
		case 0:return '未發佈';
		case 1:return '發佈';
	}
}