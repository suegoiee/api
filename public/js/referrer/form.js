$(function(){
    $('#product_select_btn').click(function(event){
        event.preventDefault();
        var products = $('#product_selects').val();
        $.each(products, function( index, value ) {
            var option = $('#product_selects option[value="'+value+'"]')
            var tr = '<tr>'+
                        '<td>'+option.data('name')+'<input type="hidden" name="products[new_'+index+'][id]" value="'+value+'"></td>'+
                        '<td><input type="text" class="form-control" name="products[new_'+index+'][divided]" value="'+$('#divided').val()+'"></td>'+
                        '<td class="text-center"><span class="oi oi-trash remove" data-name="'+option.data('name')+'" data-id="'+value+'"></span></td>'+
                    '</tr>';
            $('#product_table').append(tr);
            $('#product_selects option[value="'+value+'"]').remove();
        });
        $('#product_selects').selectpicker('refresh');
    });
    $('#product_table').on('click','.remove',function(event){
        event.preventDefault();
        if(confirm('確定刪除？')){
            var option = '<option value="'+$(this).data('id')+'" data-name="'+$(this).data('name')+'" >'+$(this).data('name')+'</option>'
            $('#product_selects').prepend(option);
            $('#product_selects').selectpicker('refresh');
            $(this).parent().parent().remove();
        }
    });
});