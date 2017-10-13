<div class="form-group row">
    <label class="form-control-label col-sm-2" for="use_invoice">{{trans($module_name.'.admin.use_invoice')}}</label>
    <div class="col-sm-8 text">
        {{trans($module_name.'.admin.use_invoice_'.$data->use_invoice)}}
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="invoice_type">{{trans($module_name.'.admin.invoice_type')}}</label>
    <div class="col-sm-8 text">
        {{trans($module_name.'.admin.invoice_type_'.$data->invoice_type)}}
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="invoice_name">{{trans($module_name.'.admin.invoice_name')}}</label>
    <div class="col-sm-8 text">
        {{$data->invoice_name}}
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="invoice_phone">{{trans($module_name.'.admin.invoice_phone')}}</label>
    <div class="col-sm-8 text">
        {{$data->invoice_phone}}
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="invoice_address">{{trans($module_name.'.admin.invoice_address')}}</label>
    <div class="col-sm-8 text">
        {{$data->invoice_address}}
    </div>
</div>
@if($data->invoice_type == 2)
    <div class="form-group row">
        <label class="form-control-label col-sm-2" for="invoice_number">{{trans($module_name.'.admin.invoice_number')}}</label>
        <div class="col-sm-8 text">
            {{$data->invoice_number}}
        </div>
    </div>
    <div class="form-group row">
        <label class="form-control-label col-sm-2" for="invoice_head">{{trans($module_name.'.admin.invoice_head')}}</label>
        <div class="col-sm-8 text">
            {{$data->invoice_head}}
        </div>
    </div>
@endif