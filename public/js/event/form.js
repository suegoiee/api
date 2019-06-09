$(function(){
    let datetimepickerIconConfig = {
      time: 'far fa-clock',
      date: 'far fa-calendar',
      up: 'fas fa-arrow-up',
      down: 'fas fa-arrow-down',
      previous: 'fas fa-chevron-left',
      next: 'fas fa-chevron-right',
      today: 'fas fa-calendar-check',
      clear: 'far fa-trash-alt',
      close: 'far fa-times-circle'
    };
    $('#started_at').datetimepicker({icons: datetimepickerIconConfig, format:'YYYY/MM/DD'});
    $('#ended_at').datetimepicker({icons: datetimepickerIconConfig, format:'YYYY/MM/DD'});
    
    $('#product_A_select_btn').click(function(event){
        event.preventDefault();
        var products = $('#products_A_input').val();
        $.each(products, function( index, value ) {
            var option = $('#products_A_input option[value="'+value+'"]')
            var tr = '<tr>'+
                        '<td>'+option.data('name')+'<input type="hidden" name="condition_products[new_'+index+'][id]" value="'+value+'"><input type="hidden" name="condition_products[new_'+index+'][condition]" value="1"></td>'+
                        '<td><input type="text" class="form-control" name="condition_products[new_'+index+'][quantity]" value="" placeholder="0為不限月數"></td>'+
                        '<td class="text-center"><span class="oi oi-trash remove" data-name="'+option.data('name')+'" data-type="'+option.data('type')+'" data-status="'+option.data('status')+'" data-id="'+value+'"></span></td>'+
                    '</tr>';
            $('#product_A_table').append(tr);
            $('#products_A_input option[value="'+value+'"]').remove();
        });
        $('#products_A_input').selectpicker('refresh');
    });
    $('#product_B_select_btn').click(function(event){
        event.preventDefault();
        var products = $('#products_B_input').val();
        $.each(products, function( index, value ) {
            var option = $('#products_B_input option[value="'+value+'"]')
            var tr = '<tr>'+
                        '<td>'+option.data('name')+'<input type="hidden" name="products[new_'+index+'][id]" value="'+value+'"><input type="hidden" name="products[new_'+index+'][condition]" value="0"></td>'+
                        '<td><input type="text" class="form-control" name="products[new_'+index+'][quantity]" value="" placeholder="0為不限月數"></td>'+
                        '<td class="text-center"><span class="oi oi-trash remove" data-name="'+option.data('name')+'"  data-type="'+option.data('type')+'" data-status="'+option.data('status')+'" data-id="'+value+'"></span></td>'+
                    '</tr>';
            $('#product_B_table').append(tr);
            $('#products_B_input option[value="'+value+'"]').remove();
        });
        $('#products_B_input').selectpicker('refresh');
    });
    $('#product_A_table').on('click','.remove',function(event){
        event.preventDefault();
        if(confirm('確定刪除？')){
            var option = '<option value="'+$(this).data('id')+'" data-name="'+$(this).data('name')+'" data-type="'+$(this).data('type')+'" data-status="'+$(this).data('status')+'" >'+$(this).data('name')+' ['+$(this).data('type')+'] '+' ('+$(this).data('status')+') '+'</option>'
            $('#products_A_input').prepend(option);
            $('#products_A_input').selectpicker('refresh');
            $(this).parent().parent().remove();
        }
    });
    $('#product_B_table').on('click','.remove',function(event){
        event.preventDefault();
        if(confirm('確定刪除？')){
            var option = '<option value="'+$(this).data('id')+'" data-name="'+$(this).data('name')+'" data-type="'+$(this).data('type')+'" data-status="'+$(this).data('status')+'">'+$(this).data('name')+' ['+$(this).data('type')+'] '+' ('+$(this).data('status')+') '+'</option>'
            $('#products_B_input').prepend(option);
            $('#products_B_input').selectpicker('refresh');
            $(this).parent().parent().remove();
        }
    });
});