$(function(){
    
    CKEDITOR.config.height=400;
    CKEDITOR.config.extraPlugins = 'uploadimage';
    CKEDITOR.config.uploadUrl = url('admin/ckeditor/images'),
    CKEDITOR.config.filebrowserImageUploadUrl=url('admin/ckeditor/images'),
    CKEDITOR.config.removeButtons='About',
    CKEDITOR.config.extraPlugins = 'youtube';

    $("#title").change(function(){
        $("#slug").val($("#title").val());
    });
    
    multisolutions('new_products0');

    $('#categories').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if(confirm('確定刪除 ?')){
            row.parent().parent().remove();
        }
    });

    var new_count = 1;
    $("#new_category_btn").click(function(event){
        event.preventDefault();
        var $productOptions = $('#hidden_products').find('option');
        var append_options = '';
        $productOptions.each(function(){
            append_options += '<option value="'+$(this).val()+'">'+$(this).text()+'</option>';
        });
        var category_html=''+
            '<div class="form-group row">'+
                '<label class="form-control-label col-sm-2" for="new_name'+new_count+'">文章分類名稱<span class="text-danger">*</span></label>'+
                '<div class="col-sm-8">'+
                    '<input type="text" class="form-control" id="new_name'+new_count+'" name="name['+new_count+']" placeholder="請輸入文章分類名稱" value="">'+
                '</div>'+
                '<div class="col-sm-2 text-danger msg">'+          
                '</div>'+
                '<label class="form-control-label col-sm-2" for="new_products">產品</label>'+
                '<div class="col-sm-8">'+
                    '<select class="form-control chosen-select" id="new_products'+new_count+'" name="product_id['+new_count+'][]" multiple="multiple" data-placeholder="請選擇">'+
                        append_options+
                    '</select>'+
                '</div>'+
                '<div class="col-sm-2 text-danger msg">'+
                '</div>'+
                '<div class="col-2 text-center">'+
                    '<button class="btn btn-danger remove_btn" type="button" value=""><span class="oi oi-trash"></span></button>'+
                '</div>'+
            '</div>';
        $("#categories").append(category_html);
        multisolutions('new_products'+new_count);
        new_count++;
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
    
    function multisolutions(id, byclass){
        if(byclass){
            var selector = '.'+id;
        }
        else{
            var selector = '#'+id;
        }
        $(selector).multiSelect({
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
                //this.$element.find('[value='+value+']').insertAfter($('#affiliated_products :last-child'));
            },
            afterDeselect: function(){
                this.qs1.cache();
                this.qs2.cache();
            }
        });
    }       
});