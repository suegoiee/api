@extends('layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/form.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-multiselect/css/bootstrap-multiselect.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/chosen/chosen.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/multi-select/css/multi-select.css')}}">

    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap4-datetimepicker/css/bootstrap-datetimepicker.min.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">

    <link rel="stylesheet" href="{{asset('css/analyst_grant.css')}}">
@endsection
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item">
            <a href="{{url('admin/'.str_plural($module_name))}}">
                <span class="">{{trans($module_name.'.admin.menu_title')}}</span>
            </a>
        </li>
        <li class="breadcrumb-item">
            <a href="{{url('admin/'.str_plural($module_name).'/'.$analyst->id.'/grants')}}">
                <span class="">{{$analyst->name}} {{trans($module_name.'.admin.grant_menu_title')}}</span>
            </a>
        </li>
        <li class="breadcrumb-item active">
            <span class="">
                {{trans($module_name.'.admin.'.($actionName).'_title')}}
            </span>
        </li>
    </ol>
    
    @include('admin.form_error')
    <form id="form" class="mt-5" action="{{$data ? url('/admin/'.str_plural($module_name).'/'.$analyst->id.'/grants/'.$data->id) : url('/admin/'.str_plural($module_name).'/'.$analyst->id.'/grants')}}" method="POST" enctype="multipart/form-data">
        {{ $data ? method_field('PUT'):'' }}
        {{ csrf_field() }}
        <div class="form-group row">
            <label class="form-control-label col-sm-2">{{trans($module_name.'.admin.name')}}</label>
            <div class="form-control-label col-sm-8 text-left">
                {{$analyst->name}}
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
        </div>
        <div class="form-group row">
            <label class="form-control-label col-sm-2">{{trans($module_name.'.admin.no')}}</label>
            <div class="form-control-label col-sm-8 text-left">
                {{$analyst->no}}
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
        </div>
        <div class="form-group row">
            <label class="form-control-label col-sm-2" for="statement_no">{{trans($module_name.'.admin.statement_no')}} <span class="text-danger"></span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="statement_no" name="statement_no" placeholder="{{trans($module_name.'.admin.statement_no')}}" value="{{@$data->statement_no}}">
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
        </div>
        <div class="form-group row">
            <label class="form-control-label col-sm-2" for="ratio">{{trans($module_name.'.admin.ratio')}} <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="ratio" name="ratio" placeholder="{{trans($module_name.'.admin.ratio')}}" value="{{$data ? $data->ratio:$analyst->ratio}}">
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
        </div>
        <div class="form-group row">
            <label class="form-control-label col-sm-2" for="year_month">{{trans($module_name.'.admin.year_month')}} <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="year_month" name="year_month" placeholder="{{trans($module_name.'.admin.year_month')}}" value="{{@$data->year_month}}" data-provide="datepicker" data-date-language="zh-TW" data-date-format="yyyy-mm" data-date-orientation="bottom" data-date-start-view="months" data-date-min-view-mode="months">
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
        </div>
        <div class="form-group row">
            <label class="form-control-label col-sm-2">{{trans($module_name.'.admin.detail')}} <span class="text-danger"></span></label>
            <label class="form-control-label col-sm-8"><a href="#" target="_blank" class="btn btn-info" id="detail_page"><span></span>{{trans($module_name.'.admin.detail_page')}}</a></label>
        </div>
        <div class="form-group row">
            <div class="col-sm-2">
                        
            </div>
            <table class="col-sm-8 table-sm detail_table" id="detail_table">
                <tbody>
                <tr class="bg-light">
                    <th width="70%">{{trans($module_name.'.admin.detail_price')}}</th>
                    <td width="5%"></td>
                    <td><input type="text" name="price" id="price" class="form-control" value="{{@$data->price}}"></td>
                    <td width="5%"></td>
                </tr>
                <tr>
                    <th>{{trans($module_name.'.admin.detail_handle_fee')}}</th>
                    <td>-</td>
                    <td><input type="text" name="handle_fee" id="handle_fee" class="form-control" value="{{@$data->handle_fee}}"></td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{trans($module_name.'.admin.detail_platform_fee')}}</th>
                    <td>-</td>
                    <td><input type="text" name="platform_fee" id="platform_fee" class="form-control" value="{{@$data->platform_fee}}"></td>
                    <td></td>

                </tr>
                <tr class="bg-light">
                    <th>{{trans($module_name.'.admin.detail_net_commission')}}</th>
                    <td>$</td>
                    <td><span id="net_commission">0</span></td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{trans($module_name.'.admin.detail_income_tax')}} <span data-toggle="tooltip" data-placement="?" title="{{trans($module_name.'.admin.detail_income_tax_q')}}">(?)</span></th>
                    <td>-</td>
                    <td><input type="text" name="income_tax" id="income_tax" class="form-control" value="{{@$data->income_tax}}"></td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{trans($module_name.'.admin.detail_second_generation_nhi')}} <span data-toggle="tooltip" data-placement="?" title="{{trans($module_name.'.admin.detail_second_generation_nhi_q')}}">(?)</span></th>
                    <td>-</td>
                    <td><input type="text" name="second_generation_nhi" id="second_generation_nhi" class="form-control" value="{{@$data->second_generation_nhi}}"></td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{trans($module_name.'.admin.detail_interbank_remittance_fee')}}</th>
                    <td>-</td>
                    <td><input type="text" name="interbank_remittance_fee" id="interbank_remittance_fee" class="form-control" value="{{@$data->interbank_remittance_fee}}"></td>
                    <td></td>
                </tr>
                <tr class="bg-light">
                    <th>{{trans($module_name.'.admin.detail_net_sales_payment')}}</th>
                    <td>$</td>
                    <td><span id="net_sales_payment">0</span></td>
                    <td></td>
                </tr>
                </tbody>
                <tbody id="extra_amount">
                    @forelse($details as $key => $detail)
                        <tr>
                            <th>
                                <input type="hidden" name="grant_amounts[{{$key}}][id]" value="{{$detail->id}}">
                                <input type="text" name="grant_amounts[{{$key}}][name]" placeholder="{{trans($module_name.'.admin.detail_exrta_amount')}}" class="form-control" value="{{$detail->name}}"></th>
                            <td>-</td>
                            <td><input type="text" name="grant_amounts[{{$key}}][amount]" class="form-control extra_amounts"  value="{{$detail->amount}}"></td>
                            <td>
                                @if($key == 0)
                                    <button class="btn btn-success text-center" id="extra_amount_add"><span class="oi oi-plus"></span></button>
                                @else
                                    <span class="oi oi-x remove"></span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <th>
                                <input type="hidden" name="grant_amounts[0][id]" value="0">
                                <input type="text" name="grant_amounts[0][name]" placeholder="{{trans($module_name.'.admin.detail_exrta_amount')}}" class="form-control"></th>
                            <td>-</td>
                            <td><input type="text" name="grant_amounts[0][amount]" class="form-control extra_amounts" value="0"></td>
                            <td><button class="btn btn-success text-center" id="extra_amount_add"><span class="oi oi-plus"></span></button></td>
                        </tr>
                    @endforelse
                </tbody>
                <tbody>
                    <tr class="bg-light">
                        <th>{{trans($module_name.'.admin.detail_transfer_amount')}}</th>
                        <td>$</td>
                        <td><span id="transfer_amount">0</span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="actions form-group text-center" >
            <button type="submit" name="action" value="save" class="btn btn-info">{{trans('form.save')}}</button>
            <button type="submit" name="action" value="save_exit" class="btn btn-info">{{trans('form.save_exit')}}</button>
            <a href="{{url('/admin/'.str_plural($module_name))}}" class="btn btn-warning cancel">{{trans('form.cancel')}}</a>
        </div>
    </form>
@endsection

@section('javascript')
<script src="{{asset('thirdparty/moment/moment.js')}}"></script>
<script src="{{asset('thirdparty/moment/locales/zh-tw.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-multiselect/js/bootstrap-multiselect.js')}}"></script>
<script src="{{asset('thirdparty/chosen/chosen.jquery.js')}}"></script>
<script src="{{asset('thirdparty/jquery.quicksearch/jquery.quicksearch.js')}}"></script>
<script src="{{asset('thirdparty/multi-select/js/jquery.multi-select.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap4-datetimepicker/js/bootstrap-datetimepicker.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js')}}"></script>
<script>
var module_name = "{{str_plural($module_name)}}";
var analyst_id = "{{$analyst->id}}";
$(function(){
    updateAmount();
   $('#form').on('keyup keypress','input[type=text]',function(e) {
      var keyCode = e.keyCode || e.which;
      if (keyCode === 13) { 
        e.preventDefault();
        return false;
      }
    });
    $('#year_month').on('change',function(e) {
        $('#detail_page span').html($(this).val());
        if($('#ratio').val()!=''){
            getAmount();
        }
    });
    $('#ratio').on('change',function(e) {
        if($('#year_month').val()!=''){
            getAmount();
        }
    });
   $('#extra_amount_add').click(function(event){
        event.preventDefault();
        var index = $('#extra_amount tr').length;
        var tr_html='<tr>'+
                        '<th>'+
                            '<input type="hidden" name="grant_amounts['+index+'][id]" value="0">'+
                            '<input type="text" name="grant_amounts['+index+'][name]" placeholder="{{trans($module_name.'.admin.detail_exrta_amount')}}" class="form-control"></th>'+
                        '<td></td>'+
                        '<td><input type="text" name="grant_amounts['+index+'][amount]" class="form-control extra_amounts" value="0"></td>'+
                        '<td><span class="oi oi-x remove"></span></td>'+
                    '</tr>';
        $('#extra_amount').append(tr_html);
   });
   $('#detail_table').on('click','.remove',function(event) {
        event.preventDefault();
        $(this).parent().parent().remove();
    });
   $('#detail_table').on('change','.extra_amounts',function(e) {
        updateAmount();
    });
   $('#detail_page').click(function(event){
        event.preventDefault();
        var query = '?year_month='+$('#year_month').val();
        if($('#ratio').val()!=''){
           query += '&ratio='+$('#ratio').val();
        }
        window.open(url('admin/'+module_name+'/'+analyst_id+'/grants/details'+query));
   });
   function getAmount(){
        $.get(url('admin/'+module_name+'/'+analyst_id+'/grants/amounts'),{year_month:$('#year_month').val(),ratio:$('#ratio').val()},function(response){
            $('#price').val(response.price);
            $('#handle_fee').val(response.handle_fee);
            $('#platform_fee').val(response.platform_fee);
            var net_commission = response.price-response.handle_fee-response.platform_fee;
            $('#income_tax').val(net_commission>=20000?Math.floor(net_commission/10):0);
            $('#second_generation_nhi').val(net_commission>=20000? Math.floor(net_commission*0.0191):0);
            $('#interbank_remittance_fee').val(30);
            updateAmount();
        });
   }
   function updateAmount(){
        var net_commission = parseInt($('#price').val(),10)-parseInt($('#handle_fee').val(),10)-parseInt($('#platform_fee').val(),10);
        $('#net_commission').html(net_commission);
        var net_sales_payment = net_commission - parseInt($('#income_tax').val(),10) - parseInt($('#second_generation_nhi').val(),10) - parseInt($('#interbank_remittance_fee').val(),10);
        $('#net_commission').html(net_commission);
        $('#net_sales_payment').html(net_sales_payment);
        var transfer_amount = net_sales_payment;
        $('.extra_amounts').each(function(index,d){
            transfer_amount -= parseInt($(d).val(),10);
        });
        $('#transfer_amount').html(transfer_amount);
   }
});
</script>
<script src="{{asset('js/'.$module_name.'/form.js')}}"></script>
@endsection
