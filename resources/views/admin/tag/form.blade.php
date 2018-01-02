<div class="form-group row">
    <label class="form-control-label col-sm-2" for="name">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="name" name="name" placeholder="{{trans($module_name.'.admin.name')}}" value="{{@$data->name}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row" id="stocks_select">
    <label class="form-control-label col-sm-2" for="stocks">{{trans($module_name.'.admin.stocks')}}</label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="stocks" name="stocks[]" multiple="multiple" data-placeholder="{{trans('form.do_select')}}">
            @foreach($stocks as $stock)
                <option value="{{$stock->no}}" {{$data && $data->stocks->where('no',$stock->no)->count()>0 ? 'selected':''}} >{{$stock->stock_code}}</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>