<div class="form-group row">
    <label class="form-control-label col-sm-2" for="name">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="name" name="name" placeholder="{{trans($module_name.'.admin.name')}}" value="{{@$data->name}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="type">{{trans($module_name.'.admin.type')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
         <select class="form-control" id="type" name="type" data-placeholder="{{trans('form.do_select')}}">
            <option value="0" {{$data && $data->type == '0' ? 'selected':''}} >{{trans($module_name.'.admin.type_0')}}</option>
            <option value="1" {{$data && $data->type == '1' ? 'selected':''}} >{{trans($module_name.'.admin.type_1')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="code">{{trans($module_name.'.admin.code')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="code" name="code" placeholder="{{trans($module_name.'.admin.code')}}" value="{{@$data->code}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="offer">{{trans($module_name.'.admin.offer')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="offer" name="offer" placeholder="{{trans($module_name.'.admin.offer')}}" value="{{@$data->offer}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="deadline">{{trans($module_name.'.admin.deadline')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="deadline" name="deadline" placeholder="{{trans($module_name.'.admin.deadline')}}" value="{{@$data->deadline}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row {{!isset($data) || $data->type == '0' ? 'hide':''}}">
    <label class="form-control-label col-sm-2" for="user_id">{{trans($module_name.'.admin.user_id')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
    	 <select class="chosen-select" id="user_id" name="user_id" data-placeholder="{{trans('form.do_select')}}">
    	 	<option hidden selected value="0"> {{trans('form.do_select')}}</option>
            @foreach($users as $user)
                <option value="{{$user->id}}" {{$data && $data->user_id == $user->id ? 'selected':''}} >{{$user->email}} ( No.{{$user->id}}, {{$user->profile->nickname}})</option>
            @endforeach
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="used_at">{{trans($module_name.'.admin.used_at')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="checkbox" class="" id="used_at" name="used_at" value="1" {{ $data && $data->used_at!=null ? 'checked':'' }} >
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>