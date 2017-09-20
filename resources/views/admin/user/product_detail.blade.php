<div class="row justify-content-center">
   <div class="col-sm-10">
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