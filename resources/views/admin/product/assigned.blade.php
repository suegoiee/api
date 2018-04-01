<div class="form-group row">
    <div class="col-sm-1"></div>
    <label class="text col-sm-8" for="products">{{trans($module_name.'.admin.assigned_products')}}</label>
</div>
<div class="form-group row" id="products_select">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <select class="form-control chosen-select" id="products" name="products[][id]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
            @foreach($products as $product)
                <option value="{{$product->id}}">{{$product->name}}</option>
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
        <select class="form-control chosen-select" id="users" name="users[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
            @foreach($users as $user)
                <option value="{{$user->id}}">{{$user->profile->nickname}}</option>
            @endforeach
        </select>
    </div>
</div>