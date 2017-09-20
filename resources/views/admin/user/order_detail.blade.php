<div class="row justify-content-center">
   <div class="col-sm-10">
        <table class="table table-bordered table-hover table-striped table-sm">
            <thead>
                <tr class="">
                    <th>{{trans('order.admin.id')}}</th>
                    <th>{{trans('order.admin.price')}}</th>
                    <th>{{trans('order.admin.status')}}</th>
                    <th>{{trans('order.admin.created_at')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->orders as $key => $order)
                <tr>
                    <td>{{$order->id}}</td>
                    <td>{{$order->price}}</td>
                    <td>{{trans('order.admin.status_'.$order->status)}}</td>
                    <td>{{$order->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>