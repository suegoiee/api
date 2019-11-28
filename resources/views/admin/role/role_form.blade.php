
<div class="form-group row">

    <label class="form-control-label col-sm-2" for="title">{{trans($module_name.'.admin.title')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="title" name="name" placeholder="{{trans($module_name.'.admin.title')}}" value="{{isset($data) ? $data->name : ''}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>

    <label class="form-control-label col-sm-2" for="new_admin_relation0">{{trans($module_name.'.admin.roles')}}</label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="new_admin_relation0" name="admin_id[]" data-placeholder="{{trans('form.do_select')}}" multiple="multiple">
            @if($users && $users->count()!=0)
                @foreach($users as $user)
                    <option value="{{$user->id}}" {{isset($data) && $data->users->contains('id', $user->id) ? 'selected' : ''}}>{{$user->name}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>

    <label class="form-control-label col-sm-2" for="new_admin_function">{{trans($module_name.'.admin.roles')}}</label>
    <div class="col-sm-8">
        <select class="form-control chosen-select" id="new_admin_function" name="function_id[]" data-placeholder="{{trans('form.do_select')}}" multiple="multiple">
            @if($functions && $functions->count()!=0)
                @foreach($functions as $function)
                    <option value="{{$function->id}}" {{isset($data) && $data->permissions->contains('id', $function->id) ? 'selected' : ''}}>{{$function->controller}}->{{$function->method}}</option>
                @endforeach
            @endif
        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
    
</div>