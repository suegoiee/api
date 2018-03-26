$(function(){

	$("#user_id").chosen();
    $("#deadline").datetimepicker({
        icons:{
            time: 'oi oi-clock',
            date: 'oi oi-calendar',
            up: 'oi oi-chevron-top',
            down: 'oi oi-chevron-bottom',
            previous: 'oi oi-chevron-left',
            next: 'oi oi-chevron-right',
            today: 'oi oi-target',
            clear: 'oi oi-trash',
            close: 'oi oi-remove'
        },
        format:'YYYY-MM-DD',
    });    
});  