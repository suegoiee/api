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