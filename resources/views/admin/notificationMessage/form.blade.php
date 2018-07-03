<div class="form-group row">
    <label class="form-control-label col-sm-2" for="type">{{trans($module_name.'.admin.type')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="type" name="type" >
            <option value="0"  {{ $data && $data->type=='0' ? 'selected':'' }} >{{trans($module_name.'.admin.type_0')}}</option>
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="data_content">{{trans($module_name.'.admin.content')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <textarea class="form-control" rows="10" id="data_content" name="content" placeholder="{{trans($module_name.'.admin.content')}}">{{@$data->content}}</textarea>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="all_user">{{trans($module_name.'.admin.all_user')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="checkbox" class="" id="all_user" name="all_user" value="1" {{ $data && $data->user_ids==null ? 'checked':'' }} >
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="send_email">{{trans($module_name.'.admin.send_email')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="checkbox" class="" id="send_email" name="send_email" value="1" {{ $data && $data->send_email==1 ? 'checked':'' }} >
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row" id="user_selection" > 
    <label class="form-control-label col-sm-2" for="user_ids">{{trans($module_name.'.admin.user_ids')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
    	 <select class="chosen-select" id="user_ids" name="user_ids[]" data-placeholder="{{trans('form.do_select')}}"  multiple="multiple">
            @foreach($users as $user)
                <option value="{{$user->id}}" {{($data && in_array($user->id, $data->user_ids)) ? 'selected':''}} >{{$user->email}} ( No.{{$user->id}}, {{$user->profile->nickname}})</option>
            @endforeach

        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>