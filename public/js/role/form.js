$(function(){
	$(".chosen-select").chosen({
        allow_single_deselect:true,
    });

    $('#ExpertAvatarContent').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if(confirm('確定刪除 ?')){
            $('#ExpertAvatarContent').html('<div class="col-sm-10">'+
                '<div class="input-group">'+
                    '<input type="file" class="form-control" name="avatar" value="">'+
                    '<input type="hidden" name="delete_image" value="1">'+
                '</div>'+
            '</div>'+
            '<div class="col-sm-2 text-danger msg">'+
            '</div>');
        }
    });

    $('#posted_at').datetimepicker({
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
        //sideBySide:true,
        stepping:15,
        format:'YYYY-MM-DD HH:mm',
        showTodayButton:true

    });
});