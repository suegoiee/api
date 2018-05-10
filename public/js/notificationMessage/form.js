$(function(){
    $('#all_user').change(function(event){
        if($(this).prop('checked')){
            $('#user_selection').hide();
        }else{
            $('#user_selection').show();
        }
    });
    $('#all_user').change();
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
    $("#form").submit(function(){
        $("body").append('<div class="pop_windows"><div>通知處理中....<div></div>');
    });
});