
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="ExpertBookContent">{{trans($module_name.'.admin.book')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <textarea class="form-control" id="ExpertBookContent" rows="6" name="book" placeholder="{{trans($module_name.'.admin.content')}}">{{@$data->book}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>