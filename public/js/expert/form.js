$(function(){
    var ckeditor_config = {
        filebrowserImageUploadUrl : url('admin/ckeditor/images'),
        removeButtons:'About'
    }
    CKEDITOR.replace( 'ExpertCourseContent',ckeditor_config);
    CKEDITOR.replace( 'ExpertAvatarContent',ckeditor_config);
    CKEDITOR.replace( 'ExpertExperienceContent',ckeditor_config);
    CKEDITOR.replace( 'ExpertBookContent',ckeditor_config);
    CKEDITOR.replace( 'ExpertInterviewContent',ckeditor_config);
    CKEDITOR.config.height=400;
    CKEDITOR.config.extraPlugins = 'uploadimage';
    CKEDITOR.config.uploadUrl = url('admin/ckeditor/images'),
    CKEDITOR.config.filebrowserImageUploadUrl=url('admin/ckeditor/images'),
    CKEDITOR.config.removeButtons='About',
    CKEDITOR.config.extraPlugins = 'youtube';

    $("#title").change(function(){
        $("#slug").val($("#title").val());
    });
    
	$("#tags").chosen();
	$("#experts").chosen({
        allow_single_deselect:true,
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