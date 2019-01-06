$(function(){

   var image_index=1;
    $("#new_image_btn").click(function(event){
        event.preventDefault();
        var image_html='<li class="ui-state-default list-group-item">'+
                        '<div class="cursor"><span class="oi oi-menu"></span>'+
                            '<input type="hidden" name="images[new_'+image_index+'][id]" value="0">'+
                            '<input type="hidden" name="images[new_'+image_index+'][sort]" value="0" class="input_sort">'+
                        '</div>'+
                        '<div class="col_image"><input type="file" class="form-control" name="images[new_'+image_index+'][image]" value=""></div>'+
                        '<div class="col_title"><input type="text" class="form-control" name="images[new_'+image_index+'][title]" value=""></div>'+
                        '<div class="col_link"><input type="text" class="form-control" name="images[new_'+image_index+'][link]" value=""></div>'+
                        '<div class="col_seo"><input type="text" class="form-control" name="images[new_'+image_index+'][seo]" value=""></div>'+
                        '<div class="col_actions"><button class="btn btn-danger remove_btn" type="button"><span class="oi oi-trash"></span></button></div>'+
                    '</li>';
        image_index++;
        $('#sortable').prepend(image_html);
    });
    $('#sortable').on('click','.remove_btn',function(event){
        event.preventDefault();
        var row = $(this);
        if(confirm('確定刪除 ?')){
            row.parent().parent().remove();       
        }
    });
    $( "#sortable" ).sortable({
            placeholder: "ui-state-highlight",
            stop: function( event, ui ) {
                $('#sortable .list-group-item .input_sort').each(function(index, item){
                    $(item).val(index);
                });
            }
    });
});