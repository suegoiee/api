<div class="form-group row">
    <label class="form-control-label col-sm-2" for="title">{{trans($module_name.'.admin.title')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="title" name="title" placeholder="{{trans($module_name.'.admin.title')}}" value="{{@$data->title}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="articleContent">{{trans($module_name.'.admin.content')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="articleContent" rows="6" name="content" placeholder="{{trans($module_name.'.admin.content')}}">{{@$data->content}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="top">{{trans($module_name.'.admin.top')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="checkbox" class="" id="top" name="top" value="1" {{ $data && $data->top=='1' ? 'checked':'' }} >
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
    <label class="form-control-label col-sm-2" for="posted_at">{{trans($module_name.'.admin.posted_at')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="posted_at" name="posted_at" placeholder="{{trans($module_name.'.admin.posted_at')}}" value="{{@$data->posted_at}}" >
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>