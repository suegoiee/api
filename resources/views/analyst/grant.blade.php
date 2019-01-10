@extends('analyst.layouts.app')
@section('css_file')
    <link rel="stylesheet" href="{{asset('css/analyst/list.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-table/analyst/bootstrap-table.min.css')}}">
    <link rel="stylesheet" href="{{asset('thirdparty/bootstrap-datepicker/css/bootstrap-datepicker3.min.css')}}">
    <link rel="stylesheet" href="{{asset('css/grant.css')}}">
@endsection
@section('content')
<div class="card">
    <ol class="breadcrumb">
        <li class="breadcrumb-item" id="bread"><span class="">{{trans($module_name.'.analyst.menu_title')}}</span></li>
        <li class="breadcrumb-item">
            <a href="{{url('analyst/'.str_plural($subtitle))}}">
                <span class="">{{trans('analyst.'.$subtitle.'_title')}}</span>
            </a>
        </li>
        <li class="breadcrumb-item active"><span class="">{{trans('analyst.'.$subtitle.'_detail_title')}}</span></li>
    </ol>
    <div class="container mt-5">
        <h3 id="con">
            <i class="fa fa-building"></i>
            商拓財經有限公司
        </h3>
        <div class="row ml-auto info">
            <i class="fa fa-map-marker"></i>
            &nbsp;台北市信義區基隆路一段163號2樓
        </div>
        <div class="row ml-auto info">
            <i class="fa fa-phone"></i>
            &nbsp;電話：02-2747-3447
        </div>
        <div class="row ml-auto info">
            <i class="fa fa-envelope"></i>
            &nbsp;電子郵件：
            <a href="mailto:service@uanalyze.com.tw">service@uanalyze.com.tw</a>
        </div>
        <div class="row text-center">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr class="send">
                        <th class="text-center" colspan="2">{{trans('analyst.remmit')}}</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th class="text-center" >{{trans('analyst.name')}}</th>
                        <td>{{$user->name}}</td>
                    </tr>
                    <tr>
                        <th class="text-center" >{{trans('analyst.no')}}</th>
                        <td>{{$user->no}}</td>
                    </tr>
                    <tr>
                        <th class="text-center" >{{trans('analyst.admin.statement_no')}}</th>
                        <td>{{$grant->statement_no}}</td>
                    </tr>
                    <tr>
                        <th class="text-center" >{{trans('analyst.date')}} 
                            <span data-toggle="tooltip" data-placement="?" title="{{trans('analyst.date_q')}}">
                                <i class="fa fa-question-circle"></i>
                            </span>
                        </th>
                        <td>{{date('Y/m/d',strtotime($grant->created_at))}}</td>
                    </tr>
                    <tr>
                        <th class="text-center" >{{trans('analyst.pay_price')}}</th>
                        <td>NT$ {{$trans_price}}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="row">
            <h2 id="title" class="mx-auto">{{$grant->year_month}} {{trans('analyst.grant_detail_table')}}</h2>
        </div>
        <div class="row">
            <table class="table table-sm detail_table mx-auto" id="detail_table">
                <tbody>
                <tr class="send">
                    <th width="70%">{{trans('analyst.admin.detail_price')}}</th>
                    <td width="5%">
                        <i class="fa fa-dollar"></i>
                    </td>
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
                <tr class="send">
                    <th>{{trans('analyst.admin.detail_net_commission')}}</th>
                    <td>
                        <i class="fa fa-dollar"></i>
                    </td>
                    <td><span id="net_commission">{{$net_commission=$grant->price-$grant->handle_fee-$grant->platform_fee}}</span></td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{trans('analyst.admin.detail_income_tax')}} 
                        <span data-toggle="tooltip" data-placement="?" title="{{trans('analyst.admin.detail_income_tax_q')}}">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </th>
                    <td>-</td>
                    <td>{{$grant->income_tax}}</td>
                    <td></td>
                </tr>
                <tr>
                    <th>{{trans('analyst.admin.detail_second_generation_nhi')}} 
                        <span data-toggle="tooltip" data-placement="?" title="{{trans('analyst.admin.detail_second_generation_nhi_q')}}">
                            <i class="fa fa-question-circle"></i>
                        </span>
                    </th>
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
                <tr class="send">
                    <th>{{trans('analyst.admin.detail_net_sales_payment')}}</th>
                    <td>
                        <i class="fa fa-dollar"></i>
                    </td>
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
                    <tr class="send">
                        <th>{{trans('analyst.admin.detail_transfer_amount')}}</th>
                        <td>
                            <i class="fa fa-dollar"></i>
                        </td>
                        <td><span id="transfer_amount">{{$trans_price}}</span></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
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
