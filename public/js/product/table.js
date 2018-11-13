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
		if(value[i].active==0){
			continue;
		}
		if(value[i].price==0){
			if(value[i].expiration==0){
				return '<div>無限期免費</div>';
			}else{
				return '<div>'+value[i].expiration+'個月 免費</div>';
			}
		}else{
			return '<div>'+value[i].expiration+'個月 '+value[i].price+'元</div>';
		}
	}
}