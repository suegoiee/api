<div class="form-group row">
    <label class="form-control-label col-sm-2" for="stock_code">{{trans($module_name.'.admin.stock_code')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="stock_code" name="stock_code" placeholder="{{trans($module_name.'.admin.stock_code')}}" value="{{@$data->stock_code}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="stock_name">{{trans($module_name.'.admin.stock_name')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="stock_name" name="stock_name" placeholder="{{trans($module_name.'.admin.stock_name')}}" value="{{@$data->stock_name}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="stock_industries">{{trans($module_name.'.admin.stock_industries')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="stock_industries" name="stock_industries" placeholder="{{trans($module_name.'.admin.stock_industries')}}" value="{{@$data->stock_industries}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="industries">{{trans($module_name.'.admin.industries')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="industries" name="industries" >
            @foreach( $industries as $key => $industry )
                <option value="{{$industry->industries}}" {{ $data && $data->industries==$industry->industries ? 'selected':'' }} >{{$industry->industries}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="type">{{trans($module_name.'.admin.type')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="type" name="type" >
            <option value="" {{ $data && $data->type=='' ? 'selected':'' }} >{{trans($module_name.'.admin.type_null')}}</option>
            <option value="上市" {{ $data && $data->type=='上市' ? 'selected':'' }} >{{trans($module_name.'.admin.type_listed')}}</option>
            <option value="上櫃" {{ $data && $data->type=='上櫃' ? 'selected':'' }} >{{trans($module_name.'.admin.type_otc')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="info">{{trans($module_name.'.admin.info')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="info" rows="6" name="info" placeholder="{{trans($module_name.'.admin.info')}}">{{@$data->info}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="product">{{trans($module_name.'.admin.product')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="product" rows="6" name="product" placeholder="{{trans($module_name.'.admin.product')}}">{{@$data->product}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="area">{{trans($module_name.'.admin.area')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="area" rows="6" name="area" placeholder="{{trans($module_name.'.admin.area')}}">{{@$data->area}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="supplier">{{trans($module_name.'.admin.supplier')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="supplier" rows="6" name="supplier" placeholder="{{trans($module_name.'.admin.supplier')}}">{{@$data->supplier}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="customer">{{trans($module_name.'.admin.customer')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="customer" rows="6" name="customer" placeholder="{{trans($module_name.'.admin.customer')}}">{{@$data->customer}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="local_related_1">{{trans($module_name.'.admin.local_related_1')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="local_related_1" rows="6" name="local_related_1" placeholder="{{trans($module_name.'.admin.local_related_1')}}">{{@$data->local_related_1}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="local_related_2">{{trans($module_name.'.admin.local_related_2')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="local_related_2" rows="6" name="local_related_2" placeholder="{{trans($module_name.'.admin.local_related_2')}}">{{@$data->local_related_2}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="local_related_3">{{trans($module_name.'.admin.local_related_3')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="local_related_3" rows="6" name="local_related_3" placeholder="{{trans($module_name.'.admin.local_related_3')}}">{{@$data->local_related_3}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="local_related_4">{{trans($module_name.'.admin.local_related_4')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="local_related_4" rows="6" name="local_related_4" placeholder="{{trans($module_name.'.admin.local_related_4')}}">{{@$data->local_related_4}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="local_related_5">{{trans($module_name.'.admin.local_related_5')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="local_related_5" rows="6" name="local_related_5" placeholder="{{trans($module_name.'.admin.local_related_5')}}">{{@$data->local_related_5}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="foreign_related">{{trans($module_name.'.admin.foreign_related')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="foreign_related" rows="6" name="foreign_related" placeholder="{{trans($module_name.'.admin.foreign_related')}}">{{@$data->foreign_related}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>