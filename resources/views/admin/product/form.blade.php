<div class="form-group row">
    <label class="form-control-label col-sm-2" for="name">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="name" name="name" placeholder="{{trans($module_name.'.admin.name')}}" value="{{@$data->name}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="type">{{trans($module_name.'.admin.type')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="type" name="type" >
            <option value="single" {{ $data && $data->type=='single' ? 'selected':'' }} >{{trans($module_name.'.admin.type_single')}}</option>
            <option value="collection" {{ $data && $data->type=='collection' ? 'selected':'' }} >{{trans($module_name.'.admin.type_collection')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="model">{{trans($module_name.'.admin.model')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="model" name="model" placeholder="{{trans($module_name.'.admin.model')}}" value="{{@$data->model}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="info_short">{{trans($module_name.'.admin.info_short')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="info_short" name="info_short" placeholder="{{trans($module_name.'.admin.info_short')}}" value="{{@$data->info_short}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="info_more">{{trans($module_name.'.admin.info_more')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="info_more" rows="6" name="info_more" placeholder="{{trans($module_name.'.admin.info_more')}}">{{@$data->info_more}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="price">{{trans($module_name.'.admin.price')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8 input-group">
        <span class="input-group-addon">$</span>
        <input type="text" class="form-control" id="price" name="price" placeholder="{{trans($module_name.'.admin.price')}}" value="{{@$data->price}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="expiration">{{trans($module_name.'.admin.expiration')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8 input-group">
        <input type="text" class="form-control" id="expiration" name="expiration" placeholder="{{trans($module_name.'.admin.expiration')}}" value="{{@$data->expiration}}">
        <span class="input-group-addon">{{trans('form.day')}}</span>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="status">{{trans($module_name.'.admin.status')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="status" name="status" >
            <option value="0"  {{ $data && $data->status=='0' ? 'selected':'' }} >{{trans($module_name.'.admin.status_0')}}</option>
            <option value="1"  {{ $data && $data->status=='1' ? 'selected':'' }} >{{trans($module_name.'.admin.status_1')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
