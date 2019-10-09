<div class="form-group row">
    <label class="form-control-label col-sm-2" for="model">{{trans($module_name.'.admin.model')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="model" name="model" placeholder="{{trans($module_name.'.admin.model')}}" value="{{@$data->model}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="column">{{trans($module_name.'.admin.column')}}</label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="column" name="column" placeholder="{{trans($module_name.'.admin.column')}}" value="{{@$data->column}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="type">{{trans($module_name.'.admin.single_type')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="single_type" name="single_type" >
            <option value="chart" {{ $data && $data->single_type=='chart' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_chart')}}</option><option value="score" {{ $data && $data->single_type=='score' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_score')}}</option>
            <option value="news" {{ $data && $data->single_type=='news' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_news')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
	<label class="form-control-label col-sm-2" for="model">{{trans($module_name.'.admin.single_options')}}</label>
    <div class="col-sm-8">
        <textarea class="form-control" id="info_more" rows="12" name="single_options" placeholder="{{trans($module_name.'.admin.single_options')}}">{{$data ? $data->single_options:json_encode(["chart_type"=>"line"],JSON_PRETTY_PRINT)}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>