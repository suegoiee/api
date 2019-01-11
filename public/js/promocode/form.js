$(function(){

	$("#user_id").chosen();
    $("#type").change(function(event){
        if( $(this).val()==0){
            $("#user_id").parent().parent().addClass('hide');
        }else{
            $("#user_id").parent().parent().removeClass('hide');
            $("#user_id_chosen").css('width','100%');
        }
    });
    $("#specific").change(function(event){
        if( $(this).prop('checked')){
            $("#products_select").removeClass('hide');
            $("#retrict").removeClass('hide');
        }else{
            $("#products_select").addClass('hide');
            $("#retrict").addClass('hide');
        }
    });
    $("#retrict_type").change(function(event){
        if( $(this).val()=='1'){
            $("#retrict_condition_box").removeClass('hide');
        }else{
            $("#retrict_condition_box").addClass('hide');
        }
    });
    $("#deadline").datetimepicker({
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
        format:'YYYY-MM-DD',
    });
    $('#products').multiSelect({
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
});  