@if(!$data)
    <div class="col-12 text-center new_solution_btn_container">
        <button class="btn btn-success" id="new_category_btn"><span class="oi oi-plus"></span></button>
    </div>
    <div id="categories">
        <div class="form-group row">
            <label class="form-control-label col-sm-2" for="new_name0">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="new_name0" name="name[0]" placeholder="{{trans($module_name.'.admin.name')}}" value="">
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
            <label class="form-control-label col-sm-2" for="new_products0">{{trans($module_name.'.admin.product')}}</label>
            <div class="col-sm-8">
                <select class="form-control chosen-select" id="new_products0" name="product_id[0][]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
                    @if($products && $products->count()!=0)
                        @foreach($products as $product)
                            <option value="{{$product->id}}">{{$product->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
            <div class="col-2 text-center">
                <button class="btn btn-danger remove_btn" type="button" value=""><span class="oi oi-trash"></span></button>
            </div>
        </div>
    </div>
@else
    <div id="categories">
        <div class="form-group row">
            <label class="form-control-label col-sm-2" for="new_name0">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <input type="text" class="form-control" id="new_name0" name="name" placeholder="{{trans($module_name.'.admin.name')}}" value="{{@$data->name}}">
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
            <label class="form-control-label col-sm-2" for="new_products0">{{trans($module_name.'.admin.product')}}</label>
            <div class="col-sm-8">
                <select class="form-control chosen-select" id="new_products0" name="product_id[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
                    @if($products && $products->count()!=0)
                        @foreach($products as $product)
                            <option value="{{$product->id}}" {{$data && $data->categoryProductRelation->where('product_id',$product->id)->count()>0 ? 'selected':''}}>{{$product->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <input class="form-control" type="hidden" name="update" value="{{@$data->id}}">
            <div class="col-sm-2 text-danger msg">
                        
            </div>
        </div>
    </div>
@endif

<select class="form-control chosen-select" id="hidden_products" style="display:none">
    @if($products && $products->count()!=0)
        @foreach($products as $product)
            <option value="{{$product->id}}">{{$product->name}}</option>
        @endforeach
    @endif
</select>
