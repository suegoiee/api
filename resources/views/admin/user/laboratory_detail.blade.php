<div class="row justify-content-center">
   <div class="col-sm-10">
        <table class="table table-bordered table-hover table-striped table-sm" data-toggle="table" data-search="true">
            <thead>
                <tr class="">
                    <th data-sortable="true">{{trans('laboratory.admin.id')}}</th>
                    <th data-sortable="true">{{trans('laboratory.admin.title')}}</th>
                    <th data-sortable="true">{{trans('laboratory.admin.created_at')}}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($data->master_laboratories as $key => $laboratory)
                <tr>
                    <td>{{$laboratory->id}}</td>
                    <td>{{$laboratory->title}}</td>
                    <td>{{$laboratory->created_at}}</td>
                </tr>
                @endforeach
                @foreach($data->laboratories as $key => $laboratory)
                <tr>
                    <td>{{$laboratory->id}}</td>
                    <td>{{$laboratory->title}}</td>
                    <td>{{$laboratory->created_at}}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>