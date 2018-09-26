@extends('analyst.layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/list.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/grant.css')}}">
@endsection
@section('content')
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><span class="">{{trans($module_name.'.analyst.menu_title')}}</span></li>
        <li class="breadcrumb-item">
            <a href="{{url('analyst/'.str_plural($subtitle))}}">
                <span class="">{{trans('analyst.'.$subtitle.'_title')}}</span>
            </a>
        </li>
        <li class="breadcrumb-item active"><span class="">{{trans('analyst.'.$subtitle.'_detail_title')}}</span></li>
    </ol>
    <div class="container">
        <h3 class="bg-light">商拓財經有限公司</h3>
        <div class="row">
            台北市中山區中山北路三段27號4樓405-1室
        </div>
        <div class="row">
            電話：02-2595-9791
        </div>
        <div class="row">
            電子郵件：service@uanalyze.com.tw
        </div>
        <div class="row">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr  class="bg-light">
                        <th colspan="2">{{trans('analyst.remmit')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>{{trans('analyst.name')}}</th>
                        <td>{{$user->name}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('analyst.no')}}</th>
                        <td>{{$user->no}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('analyst.admin.statement_no')}}</th>
                        <td>{{$grant->statement_no}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('analyst.date')}} <span data-toggle="tooltip" data-placement="?" title="{{trans('analyst.date_q')}}">(?)</span></th>
                        <td>{{date('Y/m/d',strtotime($grant->created_at))}}</td>
                    </tr>
                    <tr>
                        <th>{{trans('analyst.pay_price')}}</th>
                        <td>NT$ {{$trans_price}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <h2 class="text-danger mx-auto">{{$grant->year_month}} {{trans('analyst.grant_detail_table')}}</h2>
        </div>
        <div class="row">
            <table class="table col-sm-8 table-sm detail_table mx-auto" id="detail_table">
                <tbody>
                <tr class="bg-light">
                    <th width="70%">{{trans('analyst.admin.detail_price')}}</th>
                    <td width="5%">$</td>
                    <td>{{$grant->price}}</td>
                    <td width="5%"></td>
                </tr>
                <tr>
                    <th>{{trans('analyst.admin.detail_handle_fee')}}</th>
                    <td>-</td>
                    <td>{{$grant->handle_fee}}</td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{trans('analyst.admin.detail_platform_fee')}}</th>
                    <td>-</td>
                    <td>{{$grant->platform_fee}}</td>
                    <td></td>

                </tr>
                <tr class="bg-light">
                    <th>{{trans('analyst.admin.detail_net_commission')}}</th>
                    <td>$</td>
                    <td><span id="net_commission">{{$net_commission=$grant->price-$grant->handle_fee-$grant->platform_fee}}</span></td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{trans('analyst.admin.detail_income_tax')}} <span data-toggle="tooltip" data-placement="?" title="{{trans('analyst.admin.detail_income_tax_q')}}">(?)</span></th>
                    <td>-</td>
                    <td>{{$grant->income_tax}}</td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{trans('analyst.admin.detail_second_generation_nhi')}} <span data-toggle="tooltip" data-placement="?" title="{{trans('analyst.admin.detail_second_generation_nhi_q')}}">(?)</span></th>
                    <td>-</td>
                    <td>{{$grant->second_generation_nhi}}</td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{trans('analyst.admin.detail_interbank_remittance_fee')}}</th>
                    <td>-</td>
                    <td>{{$grant->interbank_remittance_fee}}</td>
                    <td></td>
                </tr>
                <tr class="bg-light">
                    <th>{{trans('analyst.admin.detail_net_sales_payment')}}</th>
                    <td>$</td>
                    <td><span id="net_sales_payment">{{$net_commission-$grant->income_tax-$grant->second_generation_nhi-$grant->interbank_remittance_fee}}</span></td>
                    <td></td>
                </tr>
                </tbody>
                <tbody id="extra_amount">
                    @foreach($grant->details as $key => $detail)
                        <tr>
                            <th>
                                {{$detail->name}}</th>
                            <td>{{$detail->amount < 0 ? '':'-'}}</td>
                            <td>{{$detail->amount < 0 ? (-$detail->amount) : ($detail->amount)}}</td>
                            <td>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
                <tbody>
                    <tr class="bg-light">
                        <th>{{trans('analyst.admin.detail_transfer_amount')}}</th>
                        <td>$</td>
                        <td><span id="transfer_amount">{{$trans_price}}</span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('javascript')
<script src="{{asset('thirdparty/bootstrap-table/bootstrap-table.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-table/locale/bootstrap-table-zh-TW.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-datepicker/js/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('thirdparty/bootstrap-datepicker/locales/bootstrap-datepicker.zh-TW.min.js')}}"></script>
<script>
var module_name = "{{str_plural($module_name)}}";
$(function(){
});
</script>
@endsection
