function actionFormatter(value,row,index){
	var actions='<div class="actions">';
	actions+='</div>';
	return actions;
}
function platform_feeFormatter(value,row,index){
	
	return Math.round(value*100)/100;
}
function paymentTypeFormatter(value,row,index){
	switch(value){
		case '':return '免費';
        case 'credit':return '信用卡';
        case 'webatm':return '網路ATM';
        case 'atm':return 'ATM櫃員機';
        case 'cvs':return '超商代碼';
        case 'barcode':return '超商條碼';
	}
	return value;
}
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
function statement_noFormatter(value,row,index){
	return '<a href="'+url('/analyst')+'/grants/'+row.id+'">'+(value ? value :'')+' <span class="oi oi-link-intact"></span></a>';
	
}