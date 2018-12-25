<div class="row justify-content-center">
   <div class="col-sm-10">
        <table class="table table-bordered table-hover table-striped table-sm" data-toggle="table" data-search="true">
            <thead>
                <tr class="">
                    <th data-sortable="true">{{trans('order.admin.id')}}</th>
                    <th data-sortable="true">{{trans('order.admin.price')}}</th>
                    <th data-sortable="true">{{trans('order.admin.status')}}</th>
                    <th data-sortable="true">{{trans('order.admin.paymentType')}}</th>
                    <th data-sortable="true">{{trans('order.admin.created_at')}}</th>
                    <th data-sortable="true" data-formatter="promocodeFormatter">{{trans('order.admin.use_promocode')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->orders as $key => $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>{{$order->price}}</td>
                    <td>{{trans('order.admin.status_'.$order->status)}}</td>
                    <td>{{trans('order.admin.paymentType_'.$order->paymentType)}}</td>
                    <td>{{$order->created_at}}</td>
                    <td>{{$order->promocodes}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>