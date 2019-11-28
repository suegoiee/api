function promocodeFormatter(value, row, index)
{
	var promocodes = JSON.parse(value);
	var html='';
	for (var i = 0; i < promocodes.length; i++) {
		html += '<p>'+promocodes[i].name+'：'+promocodes[i].code+'<p>';
	}
	return promocodes.length == 0 ? '未使用' : html;
}