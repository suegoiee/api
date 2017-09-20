<div class="row">
    <label class="label col-sm-2" for="avatar">{{trans($module_name.'.admin.avatar')}}</label>
    <div class="col-sm-8 text-center">
        <img src="{{$data->avatar ? asset('storage/'.$data->avatar->path):''}}" class="rounded" alt="avatar">
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="id">{{trans($module_name.'.admin.id')}}</label>
    <div class="col-sm-8 text">
        {{$data->id}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="email">{{trans($module_name.'.admin.email')}}</label>
    <div class="col-sm-8 text">
        {{$data->email}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="nick_name">{{trans($module_name.'.admin.nick_name')}}</label>
    <div class="col-sm-8 text">
        {{$data->profile->nick_name}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="name">{{trans($module_name.'.admin.name')}}</label>
    <div class="col-sm-8 text">
        {{$data->profile->name}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="sex">{{trans($module_name.'.admin.sex')}}</label>
    <div class="col-sm-8 text">
        {{trans($module_name.'.admin.sex_'.$data->profile->sex)}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="address">{{trans($module_name.'.admin.address')}}</label>
    <div class="col-sm-8 text">
        {{$data->profile->address}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="birthday">{{trans($module_name.'.admin.birthday')}}</label>
    <div class="col-sm-8 text">
        {{$data->profile->birthday}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="created_at">{{trans($module_name.'.admin.created_at')}}</label>
    <div class="col-sm-8 text">
        {{$data->created_at}}
    </div>
</div>
<div class="row">
    <label class="label col-sm-2" for="updated_at">{{trans($module_name.'.admin.updated_at')}}</label>
    <div class="col-sm-8 text">
        {{$data->updated_at}}
    </div>
</div>
