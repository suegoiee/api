<div class="row justify-content-center">
   <div class="col-sm-10">
        <table class="table table-bordered table-hover table-striped table-sm">
            <thead>
                <tr class="">
                    <th>{{trans('laboratory.admin.id')}}</th>
                    <th>{{trans('laboratory.admin.title')}}</th>
                    <th>{{trans('laboratory.admin.created_at')}}</th>
                </tr>
            </thead>
            <tbody>
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