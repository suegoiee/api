$(function(){
    var ckeditor_config = {
        filebrowserImageUploadUrl : url('admin/ckeditor/images'),
        removeButtons:'About'
    }
    CKEDITOR.replace( 'data_content',ckeditor_config);
    CKEDITOR.config.height=200;
    CKEDITOR.config.extraPlugins = 'uploadimage';
    CKEDITOR.config.uploadUrl = url('admin/ckeditor/images'),
    CKEDITOR.config.filebrowserImageUploadUrl=url('admin/ckeditor/images'),
    CKEDITOR.config.removeButtons='About',
    CKEDITOR.config.extraPlugins = 'youtube';
    $('#all_user').change(function(event){
        if($(this).prop('checked')){
            $('#user_selection').hide();
        }else{
            $('#user_selection').show();
        }
    });
    $('#all_user').change();

    $('#type').change(function(e){
        var type = $(this).val();
        if(type == 'MassiveAnnouncement'){
            $('#send_email_input').addClass('hide');
            $('#send_notice_input').addClass('hide');
        }else if(type == 'RelatedProduct'){
            $('#send_email_input').removeClass('hide');
            $('#send_notice_input').removeClass('hide');
            $('#RelatedProductOption').removeClass('hide');
            $('#user_selection').addClass('hide');
        }else{
            $('#send_email_input').removeClass('hide');
            $('#send_notice_input').removeClass('hide');
            $('#RelatedProductOption').addClass('hide');
            $('#user_selection').removeClass('hide');
        }

    });
    $('#type').change();
    /*
    $('#user_ids').multiSelect({
        selectableHeader: "<div class='text-center'>可選使用者</div><input type='text' class='form-control' autocomplete='off' placeholder='Search'>",
        selectionHeader: "<div class='text-center'>已選使用者</div><input type='text' class='form-control' autocomplete='off' placeholder='Search'>",
        afterInit: function(ms){
            var that = this,
                $selectableSearch = that.$selectableUl.prev(),
                $selectionSearch = that.$selectionUl.prev(),
                selectableSearchString = '#'+that.$container.attr('id')+' .ms-elem-selectable:not(.ms-selected)',
                selectionSearchString = '#'+that.$container.attr('id')+' .ms-elem-selection.ms-selected';

            that.qs1 = $selectableSearch.quicksearch(selectableSearchString)
                .on('keydown', function(e){
                    if (e.which === 40){
                        that.$selectableUl.focus();
                        return false;
                    }
                });

            that.qs2 = $selectionSearch.quicksearch(selectionSearchString)
                .on('keydown', function(e){
                    if (e.which == 40){
                        that.$selectionUl.focus();
                        return false;
                    }
                });
        },
        afterSelect: function(){
            this.qs1.cache();
            this.qs2.cache();
        },
        afterDeselect: function(){
            this.qs1.cache();
            this.qs2.cache();
        }
    });
    */
    $("#form").submit(function(){
        $("body").append('<div class="pop_windows"><div>通知處理中....<div></div>');
    });
});