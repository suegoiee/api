$(function(){
    var ckeditor_config = {
        filebrowserImageUploadUrl : url('admin/ckeditor/images'),
        removeButtons:'About'
    }
    CKEDITOR.replace( 'info',ckeditor_config);
    //CKEDITOR.replace( 'product',ckeditor_config);
    //CKEDITOR.replace( 'area',ckeditor_config);
    //CKEDITOR.replace( 'supplier',ckeditor_config);
    //CKEDITOR.replace( 'customer',ckeditor_config);
    //CKEDITOR.replace( 'local_related_1',ckeditor_config);
    //CKEDITOR.replace( 'local_related_2',ckeditor_config);
    //CKEDITOR.replace( 'local_related_3',ckeditor_config);
    //CKEDITOR.replace( 'local_related_4',ckeditor_config);
    //CKEDITOR.replace( 'local_related_5',ckeditor_config);
    //CKEDITOR.replace( 'foreign_related',ckeditor_config);
    // CKEDITOR.replace( 'faq', ckeditor_config);
    CKEDITOR.config.height=400;
    CKEDITOR.config.filebrowserImageUploadUrl=url('admin/ckeditor/images'),
    CKEDITOR.config.removeButtons='About',
    CKEDITOR.config.extraPlugins = 'youtube';

    listEditor("product");
    listEditor("area");
    listEditor("supplier");
    listEditor("customer");
    listEditor("local_related");
    listEditor("foreign_related");

    var event_index=0;
    $("#new_event_btn").click(function(event){
        event.preventDefault();
        var tr_html = '<tr data-index="new_'+event_index+'">'+
                        '<input type="hidden" class="form-control" name="events[new_'+event_index+'][id]" value="0">'+
                        '<td class="year_col">'+
                            '<div class="data"></div>'+
                            '<input type="hidden" name="events[new_'+event_index+'][year]">'+
                            '<div class="editor">'+
                                '<input type="text" class="form-control">'+
                            '</div>'+
                        '</td>'+
                        '<td class="content_col">'+
                            '<div class="data"></div>'+
                            '<input type="hidden" name="events[new_'+event_index+'][content]">'+
                            '<div class="editor">'+
                                '<textarea id="ckeditor_new_'+event_index+'"></textarea>'+
                            '</div>'+
                        '</td>'+
                        '<td>'+
                            '<div class="action_btns hide">'+
                                '<span class="oi oi-pencil edit_btn"></span>'+
                                '<span class="oi oi-trash remove_btn"></span>'+
                            '</div>'+
                            '<div class="confirm_btns">'+
                                '<span class="oi oi-check confirm_btn"></span>'+
                                '<span class="oi oi-x cancel_btn"></span>'+
                            '</div>'+
                        '</td>'+
                    '</tr>';
        $('#new_event_list').append(tr_html);
        CKEDITOR.replace('ckeditor_new_'+event_index);
        event_index++;
    });

    $('#events').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if(confirm('確定刪除 ?')){
            row.parent().parent().parent().remove();
            
        }
    });
    $('#events').on('click','.edit_btn',function(event){
        event.preventDefault();
        var tr = $(this).parent().parent().parent();
        tr.find('.data').addClass('hide');
        var year = tr.find('.year_col').find('input').val();
        var content = tr.find('.content_col').find('input').val();
        tr.find('.year_col').find('.editor').html('<input type="text" class="form-control" value="'+year+'">');
        tr.find('.content_col').find('.editor').html('<textarea id="ckeditor_'+tr.data('index')+'">'+content+'</textarea>');
        var textarea = tr.find('.content_col').find('.editor').find('textarea');
        CKEDITOR.replace(textarea[0]);
        tr.find('.action_btns').addClass('hide');
        tr.find('.confirm_btns').removeClass('hide');
    });
    $('#events').on('click','.confirm_btn',function(event){
        event.preventDefault();
        var tr = $(this).parent().parent().parent();
        var year = tr.find('.year_col').find('.editor').find('input').val();
        tr.find('.year_col').find('input').val(year);
        tr.find('.year_col').find('.data').html(year);
        var content = CKEDITOR.instances['ckeditor_'+tr.data('index')].getData();
        tr.find('.content_col').find('input').val(content);
        tr.find('.content_col').find('.data').html(content.trim());
        tr.find('.data').removeClass('hide');

        CKEDITOR.instances['ckeditor_'+tr.data('index')].destroy();
        tr.find('.year_col').find('.editor').empty();
        tr.find('.content_col').find('.editor').empty();
        tr.find('.action_btns').removeClass('hide');
        tr.find('.confirm_btns').addClass('hide');
    });
    $('#events').on('click','.cancel_btn',function(event){
        event.preventDefault();
        var tr = $(this).parent().parent().parent();
        CKEDITOR.instances['ckeditor_'+tr.data('index')].destroy();
        tr.find('.year_col').find('.editor').empty();
        tr.find('.content_col').find('.editor').empty();
        tr.find('.data').removeClass('hide');

        tr.find('.action_btns').removeClass('hide');
        tr.find('.confirm_btns').addClass('hide');
    });
    function listEditor(feildName){
        var listIndex = 0;
        $("#new_"+feildName+"_btn").click(function(event){
            event.preventDefault();
            var tr_html = '<tr data-index="new_'+listIndex+'">'+
                            '<input type="hidden" class="form-control" name="'+feildName+'s[new_'+listIndex+'][id]" value="0">';
            if(feildName == 'product'){
                tr_html += '<td class="year_col">'+
                                '<div class="data"></div>'+
                                '<input type="hidden" name="'+feildName+'s[new_'+listIndex+'][year]">'+
                                '<div class="editor">'+
                                    '<input type="text" class="form-control">'+
                                '</div>'+
                            '</td>';
            }
            tr_html += '<td class="name_col">'+
                                '<div class="data"></div>'+
                                '<input type="hidden" name="'+feildName+'s[new_'+listIndex+'][name]">'+
                                '<div class="editor">'+
                                    '<input type="text" class="form-control">'+
                                '</div>'+
                            '</td>'+
                            '<td class="value_col">'+
                                '<div class="data"></div>'+
                                '<input type="hidden" name="'+feildName+'s[new_'+listIndex+'][value]">'+
                                '<div class="editor">'+
                                    '<input type="text" class="form-control">'+
                                '</div>'+
                            '</td>'+
                            '<td>'+
                                '<div class="action_btns hide">'+
                                    '<span class="oi oi-pencil edit_btn"></span>'+
                                    '<span class="oi oi-trash remove_btn"></span>'+
                                '</div>'+
                                '<div class="confirm_btns">'+
                                    '<span class="oi oi-check confirm_btn"></span>'+
                                    '<span class="oi oi-x cancel_btn"></span>'+
                                '</div>'+
                            '</td>'+
                        '</tr>';
            $('#new_'+feildName+'_list').append(tr_html);
            listIndex++;
        });
        $("#"+feildName+"s").on('click','.remove_btn',function(event){
            event.preventDefault();
            var row = $(this);
            if(confirm('確定刪除 ?')){
                row.parent().parent().parent().remove();
            }
        });
        $("#"+feildName+"s").on('click','.edit_btn',function(event){
            event.preventDefault();
            var tr = $(this).parent().parent().parent();
            tr.find('.data').addClass('hide');
            var name = tr.find('.name_col').find('input').val();
            var value = tr.find('.value_col').find('input').val();
            tr.find('.name_col').find('.editor').html('<input type="text" class="form-control" value="'+name+'">');
            tr.find('.value_col').find('.editor').html('<input type="text" class="form-control" value="'+value+'">');
            if(feildName == 'product'){
                var year = tr.find('.year_col').find('input').val();
                tr.find('.year_col').find('.editor').html('<input type="text" class="form-control" value="'+year+'">');
            }
            tr.find('.action_btns').addClass('hide');
            tr.find('.confirm_btns').removeClass('hide');
        });
        $("#"+feildName+"s").on('click','.confirm_btn',function(event){
            event.preventDefault();
            var tr = $(this).parent().parent().parent();
            var name = tr.find('.name_col').find('.editor').find('input').val();
            tr.find('.name_col').find('input').val(name);
            tr.find('.name_col').find('.data').html(name);
            
            var value = tr.find('.value_col').find('.editor').find('input').val();
            tr.find('.value_col').find('input').val(value);
            tr.find('.value_col').find('.data').html(value);

            tr.find('.name_col').find('.editor').empty();
            tr.find('.value_col').find('.editor').empty();
            
            if(feildName == 'product'){
                var year = tr.find('.year_col').find('.editor').find('input').val();
                tr.find('.year_col').find('input').val(year);
                tr.find('.year_col').find('.data').html(year);
                tr.find('.year_col').find('.editor').empty();
            }

            tr.find('.data').removeClass('hide');

            tr.find('.action_btns').removeClass('hide');
            tr.find('.confirm_btns').addClass('hide');
        });
        $("#"+feildName+"s").on('click','.cancel_btn',function(event){
            event.preventDefault();
            var tr = $(this).parent().parent().parent();
            tr.find('.name_col').find('.editor').empty();
            tr.find('.value_col').find('.editor').empty();

            if(feildName == 'product'){
                 tr.find('.year_col').find('.editor').empty();
            }

            tr.find('.data').removeClass('hide');

            tr.find('.action_btns').removeClass('hide');
            tr.find('.confirm_btns').addClass('hide');
        });
    }
});