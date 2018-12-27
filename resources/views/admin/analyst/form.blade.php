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
    @if(!$data)
    *
    @endif
    </span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="password" name="password" placeholder="{{trans($module_name.'.admin.password')}}" value="">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
    @if($data)
    <div class="col-sm-12 text-center text-danger">
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
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="no">{{trans($module_name.'.admin.no')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="no" name="no" placeholder="{{trans($module_name.'.admin.no')}}" value="{{@$data->no}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="ratio">{{trans($module_name.'.admin.ratio')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="ratio" name="ratio" placeholder="{{trans($module_name.'.admin.ratio')}}" value="{{@$data->ratio}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-1"></div>
    <label class="text col-sm-8" for="products">{{trans($module_name.'.admin.products')}}</label>
</div>
<div class="form-group row" id="products_select">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <select class="form-control chosen-select" id="products" name="products[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
            @foreach($products as $product)
                <option value="{{$product->id}}" {{ $data && $data->products->where('id',$product->id)->count()!=0 ? 'selected':''}}>{{$product->name}} ({{$product->status==1 ? '上架':'下架'}})</option>
            @endforeach
        </select>
    </div>
</div>