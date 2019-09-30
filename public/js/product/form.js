$(function(){
    var ckeditor_config = {
        filebrowserImageUploadUrl : url('admin/ckeditor/images'),
        removeButtons:'About'
    }
    CKEDITOR.replace( 'info_more',ckeditor_config);
    var replace_texareas = $('.solution_intro');
    replace_texareas.each(function(){
        CKEDITOR.replace($(this).attr("id"), ckeditor_config);
    });
    /*if($("#plan_intro0").length != 0){
        CKEDITOR.replace( 'plan_intro0',ckeditor_config);
    }*/
    CKEDITOR.replaceClass = 'intro_textarea';
    // CKEDITOR.replace( 'faq', ckeditor_config);
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
            this.$element.find('[value='+value+']').insertAfter($('#collections :last-child'));
        },
        afterDeselect: function(){
            this.qs1.cache();
            this.qs2.cache();
        }
    });

    $('#affiliated_products').multiSelect({
        keepOrder: true,
        selectableHeader: "<div class='text-center'>可選附屬產品</div><input type='text' class='form-control' autocomplete='off' placeholder='Search'>",
        selectionHeader: "<div class='text-center'>已選附屬產品</div><input type='text' class='form-control' autocomplete='off' placeholder='Search'>",
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
            //this.$element.find('[value='+value+']').insertAfter($('#affiliated_products :last-child'));
        },
        afterDeselect: function(){
            this.qs1.cache();
            this.qs2.cache();
        }
    });
    multisolutions('expert_affiliated_products', 1);
    /*var $selectedOptions = $('#affiliated_products').find('option:selected');
    $("#expert_affiliated_products0").empty();
    $selectedOptions.each(function(){
        $("#expert_affiliated_products0").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
    });
    $('#expert_affiliated_products0').multiselect("refresh");*/

    function multisolutions(id, byclass){
        if(byclass){
            var selector = '.'+id;
        }
        else{
            var selector = '#'+id;
        }
        $(selector).multiSelect({
            keepOrder: true,
            selectableHeader: "<div class='text-center'>可選附屬產品</div><input type='text' class='form-control' autocomplete='off' placeholder='Search'>",
            selectionHeader: "<div class='text-center'>已選附屬產品</div><input type='text' class='form-control' autocomplete='off' placeholder='Search'>",
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
                //this.$element.find('[value='+value+']').insertAfter($('#affiliated_products :last-child'));
            },
            afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    }
    
    $('#affiliated_products').on('change', function() {
        var $selectedOptions = $(this).find('option:selected');
        $(".expert_affiliated_products").empty();
        $selectedOptions.each(function(){
            $(".expert_affiliated_products").append('<option value="'+$(this).val()+'">'+$(this).text()+'</option>');
        });
        $('.expert_affiliated_products').multiselect("refresh");
    });

    $('#category').on('change', function() {
        var $selectedOptions = $(this).find('option:selected');
        if($selectedOptions.val() == 3){
            $("#solutions").empty();
            $("#new_solutions").empty();
            $("#new_solution_btn").click();
            $(".new_solution_btn_container").show();
        }
        else{
            $("#solutions").empty();
            $("#new_solutions").empty();
            $("#new_solutions").append(""+
            '<div  class="form-group row">'+
                '<div class="col-sm-2">'+
                '</div>'+
                '<div class="col-sm-8 ">'+
                    '<table class="table" id="price_table">'+
                        '<thead>'+
                            '<tr>'+
                                '<th>使用期限(月)</th>'+
                                '<th>價格(元)</th>'+
                                '<th>啟用方案</th>'+
                            '</tr>'+
                        '</thead>'+
                        '<tbody id="plan_list">'+
                            '<tr>'+
                                '<td>'+
                                    '<input class="form-control" type="hidden" name="plans[new_0][id]" value="0">'+
                                    '<input class="form-control" type="hidden" name="plans[new_0][expiration]" value="0">'+
                                    '無期限'+
                                '</td>'+
                                '<td>'+
                                '<input class="form-control" type="text" name="plans[new_0][price]" value=""  placeholder="0為免費">'+
                                '</td>'+
                                '<td>'+
                                '<input class="form-control" type="checkbox" name="plans[new_0][active]" value="1" >'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td> '+
                                    '<input class="form-control" type="hidden" name="plans[new_1][id]" value="0">'+
                                    '<input class="form-control" type="hidden" name="plans[new_1][expiration]" value="1">'+
                                    '一個月'+
                                '</td>'+
                                '<td>'+
                                '<input class="form-control" type="text" name="plans[new_1][price]" value=""  placeholder="0為免費">'+
                                '</td>'+
                                '<td>'+
                                '<input class="form-control" type="checkbox" name="plans[new_1][active]" value="1" >'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td>'+
                                    '<input class="form-control" type="hidden" name="plans[new_2][id]" value="0">'+
                                    '<input class="form-control" type="hidden" name="plans[new_2][expiration]" value="6">'+
                                    '半年'+
                                '</td>'+
                                '<td>'+
                                '<input class="form-control" type="text" name="plans[new_2][price]" value=""  placeholder="0為免費">'+
                                '</td>'+
                                '<td>'+
                                '<input class="form-control" type="checkbox" name="plans[new_2][active]" value="1" >'+
                                '</td>'+
                            '</tr>'+
                            '<tr>'+
                                '<td>'+
                                    '<input class="form-control" type="hidden" name="plans[new_3][id]" value="0">'+
                                    '<input class="form-control" type="hidden" name="plans[new_3][expiration]" value="12">'+
                                    '一年'+
                                '</td>'+
                                '<td>'+
                                '<input class="form-control" type="text" name="plans[new_3][price]" value=""  placeholder="0為免費">'+
                                '</td>'+
                                '<td>'+
                                '<input class="form-control" type="checkbox" name="plans[new_3][active]" value="1" >'+
                                '</td>'+
                            '</tr>'+
                        '</tbody>'+
                    '</table>'+
                '</div>'+
            '</div>');
            $(".new_solution_btn_container").hide();
        }
    });

    var solution_index=1;
    $("#new_solution_btn").click(function(event){
        event.preventDefault();
        var $selectedOptions = $('#affiliated_products').find('option:selected');
        var append_options = '';
        $selectedOptions.each(function(){
            append_options += '<option value="'+$(this).val()+'">'+$(this).text()+'</option>';
        });
        var solution_html='<div  class="form-group row">'+
                '<div class="col-sm-10 ">'+
                    '<div class="form-group row expert_affiliated_product_select_container">'+
                        '<label class="form-control-label col-sm-2" for="expert_affiliated_products'+solution_index+'">方案附屬產品</label>'+
                        '<div class="col-sm-8">'+
                            '<select class="form-control chosen-select expert_affiliated_products" id="expert_affiliated_products'+solution_index+'" name="plans[new_'+solution_index+'][expert_affiliated_product_select][]" multiple="multiple">'+
                            append_options+
                            '</select>'+
                        '</div>'+
                        '<div class="col-sm-2 text-danger msg">'+  
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<label class="form-control-label col-sm-2" for="plan_intro'+solution_index+'">方案簡介</label>'+
                        '<div class="col-sm-8">'+
                            '<textarea class="form-control" id="plan_intro'+solution_index+'" rows="6" name="plans[new_'+solution_index+'][plan_intro]" placeholder="方案簡介"></textarea>'+
                        '</div>'+
                        '<div class="col-sm-2 text-danger msg">'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row free_courses_container">'+
                        '<label class="form-control-label col-sm-2" for="free_courses'+solution_index+'">上課卷</label>'+
                        '<div class="col-sm-8">'+
                            '<input type="number" class="form-control" id="free_courses'+solution_index+'" name="plans[new_'+solution_index+'][free_courses]" placeholder="上課卷" value="" min="0">'+
                        '</div>'+
                        '<div class="col-sm-2 text-danger msg">'+
                        '</div>'+
                    '</div>'+
                    '<div class="form-group row">'+
                        '<label class="form-control-label col-sm-2" for="year_price'+solution_index+'">價格(一年)</label>'+
                        '<div class="col-sm-8">'+
                            '<input class="form-control" type="hidden" name="plans[new_'+solution_index+'][id]" value=0>'+
                            '<input class="form-control" type="hidden" name="plans[new_'+solution_index+'][expiration]" value="12">'+
                            '<input class="form-control" type="hidden" name="plans[new_'+solution_index+'][active]" value="1" >'+
                            '<input type="text" class="form-control" id="year_price'+solution_index+'" name="plans[new_'+solution_index+'][price]" placeholder="價格(一年)" value="">'+
                        '</div>'+
                        '<div class="col-sm-2 text-danger msg">'+
                        '</div>'+
                    '</div>'+
                '</div>'+
                '<div class="col-2 text-center delete_btn_container">'+
                    '<input type="hidden" name="solutions[][id]" value="">'+
                    '<button class="btn btn-danger remove_btn" type="button"><span class="oi oi-trash"></span></button>'+
                '</div>'+
            '</div>';
        $('#new_solutions').append(solution_html);
        CKEDITOR.replace( 'plan_intro'+solution_index );
        multisolutions('expert_affiliated_products'+solution_index);
        solution_index++;
    });
    if($("#solutions").length == null || !$("#solutions").length || $("#solutions").length == 0){
        $("#new_solution_btn").click();
    }

    $('#solutions').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if(confirm('確定刪除 ?')){
            $("#new_solutions").append('<input type="hidden" name="delete_solutions[]" value="'+$(this).val()+'">');
            row.parent().parent().remove();
        }
    });
    $('#new_solutions').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if(confirm('確定刪除 ?')){
            $("#new_solutions").append('<input type="hidden" name="delete_solutions[]" value="'+$(this).val()+'">');
            row.parent().parent().remove();
        }
    });

	$("#tags").chosen();
	$("#belongs_to_experts").chosen();
    $('#category').change(function(event){
        var category = $(this).val();
        $('#affiliated_product_select').hide();
        $('#belongs_to_expert').hide();
        switch(category){
            case '0':case 0:$('#type').prop('disabled',false);break;
            case '1':case 1:$('#type').val('single').prop('disabled',true).change();break;
            case '2':case 2:$('#type').val('collection').prop('disabled',true).change();break;
            case '3':case 3:$('#type').val('collection').prop('disabled',true).change();$('#affiliated_product_select').show();$('#belongs_to_expert').show();break;
            case '4':case 4:$('#type').prop('disabled',false);break;
        }
    });
    /* trigger*/
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