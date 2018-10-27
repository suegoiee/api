<div class="form-group row">
    <label class="form-control-label col-sm-2" for="type">{{trans($module_name.'.admin.type')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="type" name="type" >
            <option value="Announcement"  {{ $data && $data->type=='Announcement' ? 'selected':'' }} >{{trans($module_name.'.admin.type_Announcement')}}</option>
            <!--<option value="TermOfService"  {{ $data && $data->type=='TermOfService' ? 'selected':'' }} >{{trans($module_name.'.admin.type_TermOfService')}}</option>-->
            <!--<option value="NewFeature"  {{ $data && $data->type=='NewFeature' ? 'selected':'' }} >{{trans($module_name.'.admin.type_NewFeature')}}</option>-->
            <!--<option value="NewProduct"  {{ $data && $data->type=='NewProduct' ? 'selected':'' }} >{{trans($module_name.'.admin.type_NewProduct')}}</option>-->
            <option value="Promotion"  {{ $data && $data->type=='Promotion' ? 'selected':'' }} >{{trans($module_name.'.admin.type_Promotion')}}</option>
            <option value="MarketAlert"  {{ $data && $data->type=='MarketAlert' ? 'selected':'' }} >{{trans($module_name.'.admin.type_MarketAlert')}}</option>
            <!--<option value="StockAlert"  {{ $data && $data->type=='StockAlert' ? 'selected':'' }} >{{trans($module_name.'.admin.type_StockAlert')}}</option>-->
            <option value="FavoriteStockAlert"  {{ $data && $data->type=='FavoriteStockAlert' ? 'selected':'' }} >{{trans($module_name.'.admin.type_FavoriteStockAlert')}}</option>
            <!--<option value="ProductReceive"  {{ $data && $data->type=='ProductReceive' ? 'selected':'' }} >{{trans($module_name.'.admin.type_ProductReceive')}}</option>-->
            <!--<option value="PromocodeReceive"  {{ $data && $data->type=='PromocodeReceive' ? 'selected':'' }} >{{trans($module_name.'.admin.type_PromocodeReceive')}}</option>-->
            <option value="Others"  {{ $data && $data->type=='Others' ? 'selected':'' }} >{{trans($module_name.'.admin.type_Others')}}</option>
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
                <option value="{{$user->id}}" {{($data && in_array($user->id, $data->user_ids)) ? 'selected':''}} >{{$user->email}} ( No.{{$user->id}}, {{$user->profile ? $user->profile->nickname:''}})</option>
            @endforeach

        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>