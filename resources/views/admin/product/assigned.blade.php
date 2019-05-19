<div class="form-group row">
    <label class="form-control-label col-sm-2" for="send_email">{{trans($module_name.'.admin.send_email')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="checkbox" class="" id="send_email" name="send_email" value="1" >
    </div>
</div>

<div class="form-group row" id="quantity">
    <label class="form-control-label col-sm-2" for="quantity">{{trans($module_name.'.admin.quantity')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="quantity" name="quantity" placeholder="{{trans($module_name.'.admin.quantity')}}" value="1">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-1"></div>
    <label class="text col-sm-8" for="products">{{trans($module_name.'.admin.assigned_products')}}</label>
</div>
<div class="form-group row" id="products_select">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <select class="form-control selectpicker" id="products" name="products[][id]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}" data-live-search="true" data-size="5" data-none-selected-text="{{trans('form.do_select')}}" data-width="100%" data-actions-box="ture">
            @foreach($products as $product)
                <option value="{{$product->id}}">{{$product->name}} ({{$product->status==1 ? '上架':'下架'}})</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-1"></div>
    <label class="text col-sm-8" for="users">{{trans($module_name.'.admin.assigned_users')}}</label>
</div>
<div class="form-group row" id="users_select">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <select class="form-control selectpicker" id="users" name="users[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}" data-live-search="true" data-size="5" data-none-selected-text="{{trans('form.do_select')}}" data-width="100%" data-actions-box="ture">
            @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->email}} (No.{{$user->id}}, {{$user->profile?$user->profile->nickname:''}})</option>
            @endforeach
        </select>
    </div>
</div>