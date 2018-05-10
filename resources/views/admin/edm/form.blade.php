<div class="form-group row">
    <label class="form-control-label col-sm-2" for="title">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="name" name="name" placeholder="{{trans($module_name.'.admin.name')}}" value="{{@$data->name}}">
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
<div class="form-group row p-3">
    <label class="form-control-label col-sm-2">{{trans($module_name.'.admin.images')}}</label>
    <div class="col-8 text-center pb-3">
        <button class="btn btn-success" id="new_image_btn"><span class="oi oi-plus"></span></button>
    </div>
    <li class="list-group-item list-group-header">
        <div class="cursor"> </div>
        <div class="col_image">{{trans($module_name.'.admin.image')}}</div>
        <div class="col_title">{{trans($module_name.'.admin.col_title')}}</div>
        <div class="col_link">{{trans($module_name.'.admin.link')}}</div>
        <div class="col_actions">{{trans($module_name.'.admin.actions')}}</div>
    </li>
    <ul id="sortable" class="list-group mb-5">
        <li class="ui-state-default list-group-item">
            <div class="cursor"><span class="oi oi-menu"></span>
                <input type="hidden" name="images[new_0][id]" value="0">
                <input type="hidden" name="images[new_0][sort]" value="0" class="input_sort">
            </div>
            <div class="col_image"><input type="file" class="form-control" name="images[new_0][image]" value=""></div>
            <div class="col_title"><input type="text" class="form-control" name="images[new_0][title]" value=""></div>
            <div class="col_link"><input type="text" class="form-control" name="images[new_0][link]" value=""></div>
            <div class="col_actions"><button class="btn btn-danger remove_btn" type="button"><span class="oi oi-trash"></span></button></div>
        </li>   
        @if($data)
            @foreach($data->images->sortBy('sort') as $key => $image)
                <li class="ui-state-default list-group-item">
                    <div class="cursor"><span class="oi oi-menu"></span>
                        <input type="hidden" name="images[{{$key}}][id]" value="{{$image->id}}">
                        <input type="hidden" name="images[{{$key}}][sort]" value="{{$image->sort}}" class="input_sort">
                    </div>
                    <div class="col_image">
                        @if($image->path!="")
                            <img src="{{$image->url}}">
                        @endif
                    </div>
                    <div class="col_title"><input type="text" class="form-control" name="images[{{$key}}][title]" value="{{$image->title}}"></div>
                    <div class="col_link"><input type="text" class="form-control" name="images[{{$key}}][link]" value="{{$image->link}}"></div>
                    <div class="col_actions"><button class="btn btn-danger remove_btn" type="button"><span class="oi oi-trash"></span></button></div>
                </li>   
            @endforeach
        @endif
    </ul>
</div>
