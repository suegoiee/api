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
        <select class="form-control" id="stock_industries" name="stock_industries">
            <option value="0"{{$data && $data->stock_industries==0 ? "selected":""}}>{{trans($module_name.'.admin.stock_industries_0')}}</option>
            <option value="1"{{$data && $data->stock_industries==1 ? "selected":""}}>{{trans($module_name.'.admin.stock_industries_1')}}</option>
            <option value="2"{{$data && $data->stock_industries==2 ? "selected":""}}>{{trans($module_name.'.admin.stock_industries_2')}}</option>
            <option value="3"{{$data && $data->stock_industries==3 ? "selected":""}}>{{trans($module_name.'.admin.stock_industries_3')}}</option>
            <option value="4"{{$data && $data->stock_industries==4 ? "selected":""}}>{{trans($module_name.'.admin.stock_industries_4')}}</option>
            <option value="5"{{$data && $data->stock_industries==5 ? "selected":""}}>{{trans($module_name.'.admin.stock_industries_5')}}</option>
            <option value="6"{{$data && $data->stock_industries==6 ? "selected":""}}>{{trans($module_name.'.admin.stock_industries_6')}}</option>
            <option value="7"{{$data && $data->stock_industries==7 ? "selected":""}}>{{trans($module_name.'.admin.stock_industries_7')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="industries">{{trans($module_name.'.admin.industries')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="industries" name="industries" >
            @foreach( $industries as $key => $industry )
                <option value="{{$industry->industries}}" {{ $data && $data->industries==$industry->industries ? 'selected':'' }} >{{$industry->industries == '' ? trans('form.do_select') : $industry->industries}}</option>
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
