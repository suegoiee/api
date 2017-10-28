$(function(){
    var ckeditor_config = {
        filebrowserImageUploadUrl : url('admin/ckeditor/images'),
        removeButtons:'About'
    }
    CKEDITOR.replace( 'info_more',ckeditor_config);
    // CKEDITOR.replace( 'faq', ckeditor_config);
    console.log(CKEDITOR.config);
    CKEDITOR.config.height=100;
    CKEDITOR.config.filebrowserImageUploadUrl=url('admin/ckeditor/images'),
    CKEDITOR.config.removeButtons='About',
    $('#collections').multiSelect({
        selectableHeader: "<div class='text-center'>可選產品</div><input type='text' class='form-control' autocomplete='off' placeholder='Search'>",
        selectionHeader: "<div class='text-center'>已選產品</div><input type='text' class='form-control' autocomplete='off' placeholder='Search'>",
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
    
	$("#tags").chosen();
    $('#type').change(function(event){
        var type = $(this).val();
        if(type=='collection'){
            $('#collections_select').show();
        }else{
            $('#collections').multiSelect('deselect_all');
            $('#collections_select').hide();
        }
    });
    $('#type').change();
	/*
	$('#tags').multiselect({
			enableFiltering: true,s
			templates: {
				ul: '<ul class="multiselect-container dropdown-menu container-fluid"></ul>',
				li: '<li><a href="javascript:void(0);"><label class="w-100"></label></a></li>',
				filter: '<li class="multiselect-item filter"><div class="input-group mx-auto px-1"><span class="input-group-addon"><i class="oi oi-magnifying-glass"></i></span><input class="form-control multiselect-search" type="text"></div></li>',
            	filterClearBtn: '<span class="input-group-btn"><button class="btn btn-default multiselect-clear-filter" type="button"><i class="oi oi-circle-x"></i></button></span>',
            },
            buttonWidth: '100%',
            buttonText: function(options, select) {
                if (options.length === 0) {
                    return '尚未選擇';
                }else {
                    var labels = [];
                    options.each(function() {
                         if ($(this).attr('label') !== undefined) {
                             labels.push($(this).attr('label'));
                         }
                         else {
                             labels.push($(this).html());
                         }
                     });
                     return labels.join(', ') + '';
                 }
            }
        });*/
    var avatar_detail_index=0;
    $("#new_avatar_detail_btn").click(function(event){
        event.preventDefault();
        var avatar_detail_html='<div class="input-group">'+
                                '<input type="file" class="form-control" name="avatars[][avatar]" value="">'+
                                '<input type="hidden" class="form-control" name="avatars[][avatar_type]" value="detail">'+
                                
                                '<span class="input-group-btn">'+
                                    '<button class="btn btn-danger remove_btn" type="button"><span class="oi oi-trash"></span></button>'+
                                '</span>'+
                            '</div>';
        avatar_detail_index++;
        $('#upload_list').append(avatar_detail_html);
    });
    $('#upload_list').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if($('#upload_list input[type=file]').length>1){
            row.parent().parent().remove();
        }
    });
    $('#avatar_detail_list').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if(confirm('確定刪除 ?')){
            row.parent().parent().remove();
           $('#avatar_detail_list').append('<input type="hidden" name="deleted[]" value="'+row.data('id')+'">');
                    
        }
    });

    $('#avatar_small').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if(confirm('確定刪除 ?')){
            $('#avatar_small').html('<div class="col-sm-10">'+
                '<div class="input-group">'+
                    '<input type="file" class="form-control" name="avatars[][avatar]" value="">'+
                    '<input type="hidden" class="form-control" name="avatars[][avatar_type]" value="small">'+
                '</div>'+
            '</div>'+
            '<div class="col-sm-2 text-danger msg">'+
            '</div>');
            if(row.data('id')){
                $('#avatar_small').append('<input type="hidden" name="deleted[]" value="'+row.data('id')+'">');
            } 
        }
    });
    var faq_index=1;
    $("#new_faq_btn").click(function(event){
        event.preventDefault();
        var faq_html='<div class="form-group row">'+
        '<div class="col-10">'+
            '<div class="col-sm-12">'+
                '<div class="row">'+
                    '<label class="form-control-label col-sm-2">Q</label>'+
                    '<div class="col-sm-10">'+
                        '<textarea class="form-control ckeditor" id="faq_q_new_'+faq_index+'" name="faqs[new_'+faq_index+'][question]" placeholder="Q"></textarea>'+
                    '</div>'+
                '</div>'+
            '</div>'+
            '<div class="col-sm-12">'+
                '<div class="row">'+
                    '<div class="col-sm-1">'+
                    '</div>'+
                    '<label class="form-control-label col-sm-2">A</label>'+
                    '<div class="col-sm-9">'+
                        '<textarea class="form-control ckeditor" id="faq_a_new_'+faq_index+'" name="faqs[new_'+faq_index+'][answer]" placeholder="A"></textarea>'+
                    '</div>'+
                '</div>'+
            '</div>'+
        '</div>'+
        '<div class="col-2 text-center">'+
            '<input type="hidden" name="faqs[new_'+faq_index+'][id]" value="0">'+
            '<button class="btn btn-danger remove_btn" type="button"><span class="oi oi-trash"></span></button>'+
       '</div>'+
    '</div>';
        $('#new_faqs').append(faq_html);
        CKEDITOR.replace( 'faq_q_new_'+faq_index );
        CKEDITOR.replace( 'faq_a_new_'+faq_index );
        faq_index++;
    });
    $('#faqs').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if(confirm('確定刪除 ?')){
            row.parent().parent().remove();
            
        }
    });
    $('#new_faqs').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if(confirm('確定刪除 ?')){
            row.parent().parent().remove();
            
        }
    });
});