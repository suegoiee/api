
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="title">{{trans($module_name.'.admin.title')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="title" name="expert_name" placeholder="{{trans($module_name.'.admin.title')}}" value="{{@$data->expert_name}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="ExpertCourseContent">{{trans($module_name.'.admin.introduction')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <textarea class="form-control" id="ExpertCourseContent" rows="6" name="introduction">{{@$data->introduction}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="investmentStyle">{{trans($module_name.'.admin.investment_style')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="number" class="form-control" id="investmentStyle" name="investment_style" value="{{@$data->investment_style}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="investmentPeriod">{{trans($module_name.'.admin.investment_period')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="investmentPeriod" name="investment_period" value="{{@$data->investment_period}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="tags">{{trans($module_name.'.admin.tags')}}</label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="tags" name="tags[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
            @foreach($tags as $tag)
                <option value="{{$tag->id}}" {{$data && $data->tags->where('id',$tag->id)->count()>0 ? 'selected':''}} >{{$tag->name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>

<div class="form-group row">
    <label class="form-control-label col-sm-2" for="experts">{{trans($module_name.'.admin.users')}}</label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="experts" name="user_id" data-placeholder="{{trans('form.do_select')}}">
            <option />
            @foreach($users as $user)
                <option value="{{$user->id}}" {{$user->id == $data->user->id ? 'selected':''}} >{{$user->name.'('.$user->email.')'}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>