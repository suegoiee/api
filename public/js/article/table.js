function titleFormatter(value, row, index){
	return value + (row.top == 1 ? '<span class="topArticle">Top</span>':'');
}
function statusFormatter(value, row, index){
	switch(value){
		case 0:return '未發佈';
		case 1:return '發佈';
	}
	return value;
}