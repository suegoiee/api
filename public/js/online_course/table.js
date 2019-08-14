function titleFormatter(value, row, index){
	if ( row.top == 1 ){
		return value + '<span class="topArticle">Top</span>';
	}
	return value ;
}
function statusFormatter(value, row, index){
	switch(value){
		case 0:return '未發佈';
		case 1:return '發佈';
	}
	return value;
}