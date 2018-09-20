<div class="form-group row">
    <label class="form-control-label col-sm-2" for="email">{{trans($module_name.'.admin.email')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="email" name="email" placeholder="{{trans($module_name.'.admin.email')}}" value="{{@$data->email}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="password">{{trans($module_name.'.admin.password')}} <span class="text-danger">
    @if($data)
    *
    @endif
    </span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="password" name="password" placeholder="{{trans($module_name.'.admin.password')}}" value="{{@$data->password}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
    @if($data)
    <div class="">
        @lang($module_name.'.admin.password_notice')
    </div>
    @endif
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="name">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="name" name="name" placeholder="{{trans($module_name.'.admin.name')}}" value="{{@$data->name}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>