<div class="form-group row">
    <label class="form-control-label col-sm-2" for="title">{{trans($module_name.'.admin.title')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="title" name="title" placeholder="{{trans($module_name.'.admin.title')}}" value="{{@$data->title}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="type">{{trans($module_name.'.admin.type')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <select class="form-control" id="type" name="type" placeholder="{{trans($module_name.'.admin.type')}}">
            <option value="news" {{ $data && $data->type=='news' ? 'selected':'' }}>
                {{trans($module_name.'.admin.type_news')}}
            </option>
            <option value="system" {{ $data && $data->type=='system' ? 'selected':'' }}>
                {{trans($module_name.'.admin.type_system')}}
            </option>
        </select> 
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