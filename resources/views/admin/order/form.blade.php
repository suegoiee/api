<div class="form-group row">
    <label class="form-control-label col-sm-2" for="id">{{trans($module_name.'.admin.id')}}</label>
    <div class="col-sm-8 text">
        {{$data->id}}
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="price">{{trans($module_name.'.admin.price')}}</label>
    <div class="col-sm-8 text">
        $ {{$data->price}}
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="memo">{{trans($module_name.'.admin.memo')}}</label>
    <div class="col-sm-8 text">
        {{$data->memo}}
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="products">{{trans($module_name.'.admin.products')}}</label>
    <div class="col-sm-8">
        <table class="table table-bordered table-hover table-striped table-sm">
            <thead>
                <tr class="">
                    <th>{{trans('product.admin.id')}}</th>
                    <th>{{trans('product.admin.name')}}</th>
                    <th>{{trans('product.admin.type')}}</th>
                    <th>{{trans('product.admin.price')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->products as $key => $product)
                <tr>
                    <td>{{$product->id}}</td>
                    <td>{{$product->name}}</td>
                    <td>{{trans('product.admin.type_'.$product->type)}}</td>
                    <td>{{$product->price}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="created_at">{{trans($module_name.'.admin.created_at')}}</label>
    <div class="col-sm-8 text">
        {{$data->created_at}}
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="status">{{trans($module_name.'.admin.status')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="status" name="status" >
            <option value="0" {{ $data && $data->status=='0' ? 'selected':'' }} >{{trans($module_name.'.admin.status_0')}}</option>
            <option value="1" {{ $data && $data->status=='1' ? 'selected':'' }} >{{trans($module_name.'.admin.status_1')}}</option>
        </select>
    </div>
</div>