
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="physicalCourseHost">{{trans($module_name.'.admin.host')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <textarea class="form-control" id="physicalCourseHost" rows="6" name="host" placeholder="{{trans($module_name.'.admin.content')}}">{{@$data->host}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>