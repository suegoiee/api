
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="ExpertInterviewContent">{{trans($module_name.'.admin.interview')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <textarea class="form-control" id="ExpertInterviewContent" rows="6" name="interview" placeholder="{{trans($module_name.'.admin.content')}}">{{@$data->interview}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>