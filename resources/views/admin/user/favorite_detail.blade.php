<div class="row justify-content-center">
   <div class="col-sm-10">
        <table class="table table-bordered table-hover table-striped table-sm" data-toggle="table" data-search="true">
            <thead>
                <tr class="">
                    <th data-sortable="true">{{trans('favorite.admin.code')}}</th>
                    <th data-sortable="true">{{trans('favorite.admin.name')}}</th>
                    <th data-sortable="true">{{trans('favorite.admin.created_at')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->favorites as $key => $favorite)
                <tr>
                    <td>{{$favorite->stock_code}}</td>
                    <td>{{$favorite->stock_name}}</td>
                    <td>{{$favorite->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>