$(function(){
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
});