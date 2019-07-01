$(function(){
    var ckeditor_config = {
        filebrowserImageUploadUrl : url('admin/ckeditor/images'),
        removeButtons:'About'
    }
    CKEDITOR.replace( 'info_more',ckeditor_config);
    // CKEDITOR.replace( 'faq', ckeditor_config);
    console.log(CKEDITOR.config);
    CKEDITOR.config.height=400;
    CKEDITOR.config.filebrowserImageUploadUrl=url('admin/ckeditor/images'),
    CKEDITOR.config.removeButtons='About',
    CKEDITOR.config.extraPlugins = 'youtube';
    $('#collections').multiSelect({
        keepOrder: true,
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
        afterSelect: function(value){
            this.qs1.cache();
            this.qs2.cache();
            console.log(value);
            this.$element.find('[value='+value+']').insertAfter($('#collections :last-child'));
            console.log(this.$element.val());
        },
        afterDeselect: function(){
            this.qs1.cache();
            this.qs2.cache();
        }
    });
	$("#tags").chosen();
    $('#category').change(function(event){
        var category = $(this).val();
        $('#affiliated_product_select').hide();
        switch(category){
            case '0':case 0:$('#type').prop('disabled',false);break;
            case '1':case 1:$('#type').val('single').prop('disabled',true).change();break;
            case '2':case 2:$('#type').val('collection').prop('disabled',true).change();break;
            case '3':case 3:$('#type').val('collection').prop('disabled',true).change();break;
            case '4':case 4:$('#type').prop('disabled',false);$('#affiliated_product_select').show();break;
        }
    });
    $('#category').change();

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
    var price_add_index = 1;
    $('#price_add').on('click', function(event){
        event.preventDefault();
        var tr_html = '<tr>'+
                    '<td>'+
                        '<input class="form-control" type="hidden" name="prices[new_'+price_add_index+'][id]" value="0">'+
                        '<input class="form-control" type="text" name="prices[new_'+price_add_index+'][expiration]" value="" placeholder="0為無期限">'+
                    '</td>'+
                    '<td>'+
                        '<input class="form-control" type="text" name="prices[new_'+price_add_index+'][price]" value="" placeholder="0為免費">'+
                    '</td>'+
                    '<td>'+
                        '<button class="btn btn-danger remove"><span class="oi oi-x"></span></button>'+
                    '</td>'+
                '</tr>'; 
        $('#new_price_list').append(tr_html);
        price_add_index++;
    });
    $('#price_table').on('click', '.remove',function(event){
        event.preventDefault();
        if(confirm('確定刪除？')){
            $(this).parent().parent().remove();
        }
    });
    $('#form').submit(function(event){
        $('#type').prop('disabled',false);
        return true;
    });
});