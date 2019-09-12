@if(!$data)
    <div class="col-12 text-center new_solution_btn_container">
        <button class="btn btn-success" id="new_category_btn"><span class="oi oi-plus"></span></button>
    </div>
    <div id="permissions">
        <div class="form-group row">
            <label class="form-control-label col-sm-2" for="new_user_relation0">{{trans($module_name.'.admin.product')}}</label>
            <div class="col-sm-8">
                <select class="form-control chosen-select" id="new_user_relation0" name="user_id[0]" data-placeholder="{{trans('form.do_select')}}">
                    @if($users && $users->count()!=0)
                        @foreach($users as $user)
                            <option value="{{$user->id}}">{{$user->name}}({{$user->email}})</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
            <label class="form-control-label col-sm-2" for="new_category_relation0">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <select class="form-control chosen-select" id="new_category_relation0" name="category_id[0][]" data-placeholder="{{trans('form.do_select')}}" multiple="multiple">
                    @if($categories && $categories->count()!=0)
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
            <div class="col-2 text-center">
                <button class="btn btn-danger remove_btn" type="button" value=""><span class="oi oi-trash"></span></button>
            </div>
        </div>
    </div>
@else
    <div id="permissions">
        <div class="form-group row">
            <label class="form-control-label col-sm-2" for="new_user_relation0">{{trans($module_name.'.admin.product')}}</label>
            <div class="col-sm-8">
                <p>{{$data->user->name}}({{$data->user->email}})</p>
            </div>
            <input class="form-control" type="hidden" name="update" value="{{@$data->id}}">
            <div class="col-sm-2 text-danger msg">
                        
            </div>
            <label class="form-control-label col-sm-2" for="new_category_relation0">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
            <div class="col-sm-8">
                <select class="form-control chosen-select" id="new_category_relation0" name="category_id[]" data-placeholder="{{trans('form.do_select')}}">
                    @if($categories && $categories->count()!=0)
                        @foreach($categories as $category)
                            <option value="{{$category->id}}">{{$category->name}}</option>
                        @endforeach
                    @endif
                </select>
            </div>
            <div class="col-sm-2 text-danger msg">
                        
            </div>
        </div>
    </div>
@endif

<select class="form-control chosen-select" id="hidden_users" style="display:none">
    @if($users && $users->count()!=0)
        @foreach($users as $user)
            <option value="{{$user->id}}">{{$user->name}}</option>
        @endforeach
    @endif
</select>
