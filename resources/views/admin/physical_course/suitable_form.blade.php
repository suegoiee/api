
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="physicalCourseSuitable">{{trans($module_name.'.admin.suitable')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <textarea class="form-control" id="physicalCourseSuitable" rows="6" name="suitable" placeholder="{{trans($module_name.'.admin.content')}}">{{@$data->Suitable}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>