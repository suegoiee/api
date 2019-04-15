<div class="form-group row">
    <label class="form-control-label col-sm-2" for="code">{{trans($module_name.'.admin.code')}} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="code" name="code" placeholder="{{trans($module_name.'.admin.code')}}" value="{{@$data->code}}">
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="no">{{trans($module_name.'.admin.no')}} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="no" name="no" placeholder="{{trans($module_name.'.admin.no')}}" value="{{@$data->no}}">
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="email">{{trans($module_name.'.admin.email')}} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="email" name="email" placeholder="{{trans($module_name.'.admin.email')}}" value="{{@$data->email}}">
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="password">{{trans($module_name.'.admin.password')}} <span class="text-danger">
    @if(!$data)
    *
    @endif
    </span></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="password" name="password" placeholder="{{trans($module_name.'.admin.password')}}" value="">
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
    @if($data)
    <div class="col-sm-12 text-center text-danger">
        @lang($module_name.'.admin.password_notice')
    </div>
    @endif
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="name">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="name" name="name" placeholder="{{trans($module_name.'.admin.name')}}" value="{{@$data->name}}">
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="divided">{{trans($module_name.'.admin.divided')}} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="divided" name="divided" placeholder="{{trans($module_name.'.admin.divided')}}" value="{{@$data->divided}}">
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="bank_code">{{trans($module_name.'.admin.bank_code')}} <span class="text-danger"></span></label>
    <div class="col-sm-3">
        <input type="text" class="form-control" id="bank_code" name="bank_code" placeholder="{{trans($module_name.'.admin.bank_code')}}" value="{{@$data->bank_code}}">
    </div>
    <label class="form-control-label col-sm-2" for="bank_branch">{{trans($module_name.'.admin.bank_branch')}} <span class="text-danger"></span></label>
    <div class="col-sm-4">
        <input type="text" class="form-control" id="bank_branch" name="bank_branch" placeholder="{{trans($module_name.'.admin.bank_branch')}}" value="{{@$data->bank_branch}}">
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
</div>

<div class="form-group row">
    <label class="form-control-label col-sm-2" for="bank_name">{{trans($module_name.'.admin.bank_name')}} <span class="text-danger"></span></label>
    <div class="col-sm-3">
        <input type="text" class="form-control" id="bank_name" name="bank_name" placeholder="{{trans($module_name.'.admin.bank_name')}}" value="{{@$data->bank_name}}">
    </div>
    <label class="form-control-label col-sm-1" for="bank_account">{{trans($module_name.'.admin.bank_account')}} <span class="text-danger"></span></label>
    <div class="col-sm-5">
        <input type="text" class="form-control" id="bank_account" name="bank_account" placeholder="{{trans($module_name.'.admin.bank_account')}}" value="{{@$data->bank_account}}">
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
</div>
<hr>
<div class="form-group row">
    <div class="col-sm-1"></div>
    <label class="text col-sm-9" for="products">{{trans($module_name.'.admin.products')}}</label>
</div>
<div class="form-group row">
    <div class="col-sm-1"></div>
    <div class="col-sm-9">
        <select class="form-control selectpicker" id="product_selects" multiple="multiple" data-placeholder="{{trans('form.do_select')}}" data-live-search="true" data-size="5" data-none-selected-text="{{trans('form.do_select')}}" data-width="100%" data-actions-box="ture">
            @foreach($products as $product)
                @if(!$data || $data->products->where('id',$product->id)->count()==0)
                    <option value="{{$product->id}}" data-name="{{$product->name}}" >{{$product->name}}</option>
                @endif
            @endforeach
        </select>
    </div>
    <div class="col-sm-2">
        <button class="btn btn-info" id="product_select_btn">加入
        </button>
    </div>
</div>
<div class="form-group row">
    <div class="col-sm-1"></div>
    <div class="col-sm-10">
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>{{trans($module_name.'.admin.product_name')}}</th>
                    <th>{{trans($module_name.'.admin.ratio')}}</th>
                    <th>{{trans('table.action_label')}}</th>
                </tr> 
            </thead>
            <tbody id="product_table">
               @foreach( $data->products as $key => $product)
                    <tr>
                        <td>{{$product->name}}<input type="hidden" name="products[{{$key}}][id]" value="{{$product->id}}"></td>
                        <td><input type="text" class="form-control" name="products[{{$key}}][divided]" value="{{$product->pivot->divided}}"></td>
                        <td class="text-center"><span class="oi oi-trash remove" data-name="{{$product->name}}" data-id="{{$product->id}}"></span></td>
                    </tr> 
               @endforeach
            </tbody>
        </table>
    </div>
</div>