
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="title">{{trans($module_name.'.admin.title')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="title" name="name" placeholder="{{trans($module_name.'.admin.title')}}" value="{{@$data->name}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="date">{{trans($module_name.'.admin.date')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="datetime-local" class="form-control" id="date" name="date" value="{{@$data->date}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="end_date">{{trans($module_name.'.admin.end_date')}} </label>
    <div class="col-sm-8">
        <input type="datetime-local" class="form-control" id="end_date" name="end_date" value="{{@$data->end_date}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="location">{{trans($module_name.'.admin.location')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="location" name="location" value="{{@$data->location}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="quota">{{trans($module_name.'.admin.quota')}}</label>
    <div class="col-sm-8">
        <input type="number" class="form-control" id="quota" name="quota" value="{{@$data->quota}}" min="0">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="physicalCourseContent">{{trans($module_name.'.admin.introduction')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <textarea class="form-control" id="physicalCourseContent" rows="6" name="introduction">{{@$data->introduction}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="seo">{{trans($module_name.'.admin.seo')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="seo" name="seo" value="{{@$data->seo}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="status">{{trans($module_name.'.admin.status')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="status" name="status" >
            <option value="0"  {{ $data && $data->status=='0' ? 'selected':'' }} >{{trans($module_name.'.admin.status_0')}}</option>
            <option value="1"  {{ $data && $data->status=='1' ? 'selected':'' }} >{{trans($module_name.'.admin.status_1')}}</option>
        </select>
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
    <label class="form-control-label col-sm-2" for="experts">{{trans($module_name.'.admin.experts')}}</label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="experts" name="experts[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
            @foreach($experts as $expert)
                <option value="{{$expert->id}}" {{$data && $data->experts->where('id',$expert->id)->count()>0 ? 'selected':''}} >{{$expert->expert_name}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>