<div class="form-group row">
    <label class="form-control-label col-sm-2" for="name">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="name" name="name" placeholder="{{trans($module_name.'.admin.name')}}" value="{{@$data->name}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>

<div class="form-group row">
    <label class="form-control-label col-sm-2" for="category">{{trans($module_name.'.admin.category')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="category" name="category" >
            <option value="1" {{ $data && $data->category=='1' ? 'selected':'' }}>{{trans($module_name.'.admin.category_1')}}</option>
            <option value="2" {{ $data && $data->category=='2' ? 'selected':'' }}>{{trans($module_name.'.admin.category_2')}}</option>
            <option value="3" {{ $data && $data->category=='3' ? 'selected':'' }}>{{trans($module_name.'.admin.category_3')}}</option>
            <option value="4" {{ $data && $data->category=='4' ? 'selected':'' }}>{{trans($module_name.'.admin.category_4')}}</option>
            <option value="0" {{ $data && $data->category=='0' ? 'selected':'' }}>{{trans($module_name.'.admin.category_0')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row" id="belongs_to_expert">
    <label class="form-control-label col-sm-2" for="belongs_to_experts">{{trans($module_name.'.admin.expert')}}</label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="belongs_to_experts" name="experts[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
            @foreach($experts as $expert)
                <option value="{{$expert->id}}" {{$data && $data->experts->where('id',$expert->id)->count()>0 ? 'selected':''}} >{{$expert->expert_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row" id="affiliated_product_select">
    <label class="form-control-label col-sm-2" for="affiliated_products">{{trans($module_name.'.admin.affiliated_product')}}</label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="affiliated_products" name="affiliated_products[]" multiple="multiple">
            @foreach($affiliated_products as $product)
                <option class="collections_unaffiliated" value="{{$product->id}}" {{($data && $data->affiliated_products->where('id',$product->id)->count())? 'selected':''}} >{{$product->name}}</option>
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
            <option value="single" {{ $data && $data->type=='single' ? 'selected':'' }} >{{trans($module_name.'.admin.type_single')}}</option>
            <option value="collection" {{ $data && $data->type=='collection' ? 'selected':'' }} >{{trans($module_name.'.admin.type_collection')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row" id="pathname_box">
    <label class="form-control-label col-sm-2" for="pathname">{{trans($module_name.'.admin.pathname')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="pathname" name="pathname" placeholder="{{trans($module_name.'.admin.pathname')}}" value="{{@$data->pathname}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row" id="collections_select">
    <label class="form-control-label col-sm-2" for="collections">{{trans($module_name.'.admin.collections')}}</label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="collections" name="collections[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
            @if($data)
                @foreach($data->collections->sortBy('sort') as $product)
                    <option value="{{$product->id}}" selected>{{$product->name}}</option>
                @endforeach
            @endif
            @foreach($singles as $product)
                <option value="{{$product->id}}" {{$data && $data->collections->where('id',$product->id)->count()>0 ? 'selected':''}} >{{$product->name}}</option>
            @endforeach
        </select>
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
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="tags">{{trans($module_name.'.admin.tags')}}</label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="tags" name="tags[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
            @foreach($tags as $tag)
                <option value="{{$tag->id}}" {{$data && $data->tags->where('id',$tag->id)->count()>0 ? 'selected':''}} >{{$tag->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="seo">{{trans($module_name.'.admin.seo')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="seo" name="seo" placeholder="{{trans($module_name.'.admin.seo')}}" value="{{@$data->seo}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="date_range">{{trans($module_name.'.admin.date_range')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="date_range" name="date_range" placeholder="{{trans($module_name.'.admin.date_range')}}" value="{{@$data->date_range}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="inflated">{{trans($module_name.'.admin.inflated')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="inflated" name="inflated" placeholder="{{trans($module_name.'.admin.inflated')}}" value="{{@$data->inflated}}">
    </div>
    <div class="col-sm-2 text-danger msg">
    </div>
</div>