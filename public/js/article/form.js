$(function(){
    var ckeditor_config = {
        filebrowserImageUploadUrl : url('admin/ckeditor/images'),
        removeButtons:'About'
    }
    CKEDITOR.replace( 'articleContent',ckeditor_config);
    CKEDITOR.config.height=100;
    CKEDITOR.config.filebrowserImageUploadUrl=url('admin/ckeditor/images'),
    CKEDITOR.config.removeButtons='About',
    CKEDITOR.config.extraPlugins = 'youtube';
   
	$("#tags").chosen();

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