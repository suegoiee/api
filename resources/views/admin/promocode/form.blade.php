<div class="form-group row">
    <label class="form-control-label col-sm-2" for="name">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="name" name="name" placeholder="{{trans($module_name.'.admin.name')}}" value="{{@$data->name}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="type">{{trans($module_name.'.admin.type')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
         <select class="form-control" id="type" name="type" data-placeholder="{{trans('form.do_select')}}">
            <option value="0" {{$data && $data->type == '0' ? 'selected':''}} >{{trans($module_name.'.admin.type_0')}}</option>
            <option value="1" {{$data && $data->type == '1' ? 'selected':''}} >{{trans($module_name.'.admin.type_1')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="code">{{trans($module_name.'.admin.code')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="code" name="code" placeholder="{{trans($module_name.'.admin.code')}}" value="{{@$data->code}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="specific">{{trans($module_name.'.admin.specific')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="checkbox" class="" id="specific" name="specific" value="1" {{ $data && $data->specific != null ? 'checked':'' }} >
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row {{ (!isset($data) || $data->specific==0)? 'hide':''}}" id="products_select">
    <label class="form-control-label col-sm-2" for="specific">{{trans($module_name.'.admin.products')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="products" name="products[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
            @foreach($products as $product)
                <option value="{{$product->id}}" {{ $data && $data->products()->where('id',$product->id)->count()!=0 ? 'selected':''}} >{{$product->name}} ({{$product->status==1 ? '上架':'下架'}})</option>
            @endforeach
        </select>
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="offer">{{trans($module_name.'.admin.offer')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="offer" name="offer" placeholder="{{trans($module_name.'.admin.offer')}}" value="{{@$data->offer}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="deadline">{{trans($module_name.'.admin.deadline')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="deadline" name="deadline" placeholder="{{trans($module_name.'.admin.deadline')}}" value="{{@$data->deadline}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row {{!isset($data) || $data->type == '0' ? 'hide':''}}">
    <label class="form-control-label col-sm-2" for="user_id">{{trans($module_name.'.admin.user_id')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
    	 <select class="chosen-select" id="user_id" name="user_id" data-placeholder="{{trans('form.do_select')}}">
    	 	<option hidden selected value="0"> {{trans('form.do_select')}}</option>
            @foreach($users as $user)
                <option value="{{$user->id}}" {{$data && $data->user_id == $user->id ? 'selected':''}} >{{$user->email}} ( No.{{$user->id}}, {{$user->profile? $user->profile->nickname:''}})</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="used_at">{{trans($module_name.'.admin.used_at')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="checkbox" class="" id="used_at" name="used_at" value="1" {{ $data && $data->used_at!=null ? 'checked':'' }} >
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row {{ (!isset($data) || $data->type==0)? '':'hide'}}">
    <label class="form-control-label col-sm-2" for="times_limit">{{trans($module_name.'.admin.times_limit')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="times_limit" name="times_limit" placeholder="{{trans($module_name.'.admin.times_limit_0')}}" value="{{@$data->times_limit}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="disabled">{{trans($module_name.'.admin.disabled')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="checkbox" class="" id="disabled" name="disabled" value="1" {{ $data && $data->disabled!=null ? 'checked':'' }} >
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div id ="retrict" class="{{ (!isset($data) || $data->specific==0)? 'hide':''}}">
<hr>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="retrict_type">{{trans($module_name.'.admin.retrict_type')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <select class="form-control" id="retrict_type" name="retrict_type" data-placeholder="{{trans('form.do_select')}}">
            <option value="0" {{$data && $data->retrict_type == 0 ? 'selected':''}}>{{trans($module_name.'.admin.retrict_type_0')}}</option>
            <option value="1" {{$data && $data->retrict_type == 1 ? 'selected':''}}>{{trans($module_name.'.admin.retrict_type_1')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row {{ (!isset($data) || $data->retrict_type!=1)? 'hide':''}}" id="retrict_condition_box">
    <label class="form-control-label col-sm-2" for="retrict_condition">{{trans($module_name.'.admin.retrict_condition')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="retrict_condition" name="retrict_condition" placeholder="{{trans($module_name.'.admin.retrict_condition')}}" value="{{@$data->retrict_condition}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
</div>