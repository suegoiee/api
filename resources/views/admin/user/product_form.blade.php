<div class="row justify-content-center">
   <div class="col-sm-10">
        <table class="table table-bordered table-hover table-striped table-sm" data-toggle="table" data-search="true">
            <thead>
                <tr class="">
                    <th data-sortable="true">{{trans('product.admin.id')}}</th>
                    <th data-sortable="true">{{trans('product.admin.name')}}</th>
                    <th data-sortable="true">{{trans('product.admin.type')}}</th>
                    <th data-sortable="true">{{trans('product.admin.deadline')}}</th>
                    <th data-sortable="true">{{trans('product.admin.installed')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->products as $key => $product)
                <tr>
                    <td>{{$product->id}}<input type="hidden" name="products[{{$key}}][id]" value="{{$product->id}}"/></td>
                    <td>{{$product->name}}</td>
                    <td>{{trans('product.admin.type_'.$product->type)}}</td>
                    <td><input type="text" class="form-control" name="products[{{$key}}][deadline]" value="{{($product->pivot->deadline) ? explode(' ', $product->pivot->deadline)[0]:0}}"/></td>
                    <td>{{trans('product.admin.installed_'.$product->pivot->installed)}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>