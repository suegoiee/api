<div class="row">
    <label class="label col-sm-2" for="id">{{trans($module_name.'.admin.id')}}</label>
    <div class="col-sm-8 text">
        {{$data->id}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="name">{{trans($module_name.'.admin.name')}}</label>
    <div class="col-sm-8 text">
        {{$data->name}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="email">{{trans($module_name.'.admin.email')}}</label>
    <div class="col-sm-8 text">
        {{$data->email}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="category">{{trans($module_name.'.admin.category')}}</label>
    <div class="col-sm-8 text">
        {{$data->category}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="content">{{trans($module_name.'.admin.content')}}</label>
    <div class="col-sm-8 text">
        {{$data->content}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="created_at">{{trans($module_name.'.admin.created_at')}}</label>
    <div class="col-sm-8 text">
        {{$data->created_at}}
    </div>
</div>
        <form action="{{url('admin/messages/'.$data->id)}}" method="POST">
        {{ $data ? method_field('PUT'):'' }}
        {{ csrf_field() }}
        <div class="row">
        <label class="label col-sm-2" for="status">{{trans($module_name.'.admin.status')}}</label>
        <select class="form-control  col-sm-8"  name="status" id="status">
            <option value="0" {{$data->status==0?'selected':''}}>{{trans($module_name.'.admin.status_0')}}</option>
            <option value="1" {{$data->status==1?'selected':''}}>{{trans($module_name.'.admin.status_1')}}</option>
        </select>
        </div>
            <div class="actions form-group text-center mt-5" >
                    <button type="submit" name="action" value="save" class="btn btn-info">{{trans('form.save')}}</button>
                    <button type="submit" name="action" value="save_exit" class="btn btn-info">{{trans('form.save_exit')}}</button>
                    <a href="{{url('/admin/'.str_plural($module_name))}}" class="btn btn-warning cancel">{{trans('form.cancel')}}</a>
            </div>
        </form>