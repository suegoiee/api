$(function(){

	$("#user_id").chosen();
    $("#type").change(function(event){
        if( $(this).val()==0){
            $("#user_id").parent().parent().addClass('hide');
        }else{
            $("#user_id").parent().parent().removeClass('hide');
            $("#user_id_chosen").css('width','100%');
        }
    });
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