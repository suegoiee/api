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
            <option value="table" {{ $data && $data->single_type=='table' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_table')}}</option>
            <option value="kLine" {{ $data && $data->single_type=='kLine' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_kLine')}}</option>
            <option value="selection" {{ $data && $data->single_type=='selection' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_selection')}}</option>
            <option value="comboChart" {{ $data && $data->single_type=='comboChart' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_comboChart')}}</option>
            <option value="companyInfo" {{ $data && $data->single_type=='companyInfo' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_companyInfo')}}</option>
            <option value="news" {{ $data && $data->single_type=='news' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_news')}}</option>
            <option value="calculator" {{ $data && $data->single_type=='calculator' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_calculator')}}</option>
            <option value="portfolio" {{ $data && $data->single_type=='portfolio' ? 'selected':'' }} >{{trans($module_name.'.admin.single_type_portfolio')}}</option>
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