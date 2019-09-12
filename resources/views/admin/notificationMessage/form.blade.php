<div class="form-group row">
    <label class="form-control-label col-sm-2" for="data_title">{{trans($module_name.'.admin.title')}} <span class="text-danger">*</span></label>
    <div class="col-sm-8">
        <input type="text" class="form-control" id="data_title" name="title" placeholder="{{trans($module_name.'.admin.title')}}" value="{{@$data->title}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="type">{{trans($module_name.'.admin.type')}}</label>
    <div class="col-sm-8">
        <select class="form-control" id="type" name="type" >
            <option value="Announcement"  {{ $data && $data->type=='Announcement' ? 'selected':'' }} >{{trans($module_name.'.admin.type_Announcement')}}</option>
            <!--<option value="TermOfService"  {{ $data && $data->type=='TermOfService' ? 'selected':'' }} >{{trans($module_name.'.admin.type_TermOfService')}}</option>-->
            <!--<option value="NewFeature"  {{ $data && $data->type=='NewFeature' ? 'selected':'' }} >{{trans($module_name.'.admin.type_NewFeature')}}</option>-->
            <option value="NewProduct"  {{ $data && $data->type=='NewProduct' ? 'selected':'' }} >{{trans($module_name.'.admin.type_NewProduct')}}</option>
            <option value="Promotion"  {{ $data && $data->type=='Promotion' ? 'selected':'' }} >{{trans($module_name.'.admin.type_Promotion')}}</option>
            <option value="MarketAlert"  {{ $data && $data->type=='MarketAlert' ? 'selected':'' }} >{{trans($module_name.'.admin.type_MarketAlert')}}</option>
            <!--<option value="StockAlert"  {{ $data && $data->type=='StockAlert' ? 'selected':'' }} >{{trans($module_name.'.admin.type_StockAlert')}}</option>-->
            <option value="FavoriteStockAlert"  {{ $data && $data->type=='FavoriteStockAlert' ? 'selected':'' }} >{{trans($module_name.'.admin.type_FavoriteStockAlert')}}</option>
            <!--<option value="ProductReceive"  {{ $data && $data->type=='ProductReceive' ? 'selected':'' }} >{{trans($module_name.'.admin.type_ProductReceive')}}</option>-->
            <!--<option value="PromocodeReceive"  {{ $data && $data->type=='PromocodeReceive' ? 'selected':'' }} >{{trans($module_name.'.admin.type_PromocodeReceive')}}</option>-->
            <option value="Others"  {{ $data && $data->type=='Others' ? 'selected':'' }} >{{trans($module_name.'.admin.type_Others')}}</option>
            <option value="MassiveAnnouncement"  {{ $data && $data->type=='MassiveAnnouncement' ? 'selected':'' }} >{{trans($module_name.'.admin.type_MassiveAnnouncement')}}</option>
            <option value="RelatedProduct"  {{ $data && $data->type=='RelatedProduct' ? 'selected':'' }} >{{trans($module_name.'.admin.type_RelatedProduct')}}</option>
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
        <input type="checkbox" class="" id="all_user" name="all_user" value="1" {{ $data && $data->user_ids==null && $data->type!='RelatedProduct' ? 'checked':'' }} >
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div id="send_email_input" class="form-group row {{ $data && $data->type=='MassiveAnnouncement' ? 'hide':'' }}">
    <label class="form-control-label col-sm-2" for="send_email">{{trans($module_name.'.admin.send_email')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="checkbox" class="" id="send_email" name="send_email" value="1" {{ $data && $data->send_email==1 ? 'checked':'' }} >
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div id="send_notice_input" class="form-group row {{ $data && $data->type=='MassiveAnnouncement' ? 'hide':'' }}">
    <label class="form-control-label col-sm-2" for="send_notice">{{trans($module_name.'.admin.send_notice')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
        <input type="checkbox" class="" id="send_notice" name="send_notice" value="1" {{ $data && $data->send_notice==1 ? 'checked':'' }} >
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row {{ $data && $data->type=='RelatedProduct' ? 'hide':'' }}" id="user_selection" > 
    <label class="form-control-label col-sm-2" for="user_ids">{{trans($module_name.'.admin.user_ids')}} <span class="text-danger"></span></label>
    <div class="col-sm-8">
    	 <select class="form-control selectpicker" id="user_ids" name="user_ids[]" data-placeholder="{{trans('form.do_select')}}"  multiple="multiple" data-live-search="true" data-size="5" data-none-selected-text="{{trans('form.do_select')}}" data-width="100%" data-actions-box="ture">
            @foreach($users as $user)
                <option value="{{$user->id}}" {{($data && in_array($user->id, $data->user_ids)) ? 'selected':''}} >{{$user->email}} ( No.{{$user->id}}, {{$user->profile ? $user->profile->nickname:''}})</option>
            @endforeach

        </select>
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div id="RelatedProductOption" class=" {{ $data && $data->type!='RelatedProduct' ? 'hide':'' }}">
    <div class="form-group row" id="product_selection" > 
        <label class="form-control-label col-sm-2" for="product_ids">{{trans($module_name.'.admin.product_ids')}} <span class="text-danger"></span></label>
        <div class="col-sm-8">
             <select class="form-control selectpicker" id="product_ids" name="product_ids[]" data-placeholder="{{trans('form.do_select')}}"  multiple="multiple" data-live-search="true" data-size="5" data-none-selected-text="{{trans('form.do_select')}}" data-width="100%" data-actions-box="ture">
                @foreach($products as $product)
                    <option value="{{$product->id}}" {{($data && in_array($user->id, $data->product_ids)) ? 'selected':''}} >{{$product->name}} ({{ trans('product.admin.status_'.$product->status)}}) </option>
                @endforeach
            </select>
        </div>
        <div class="col-sm-2 text-danger msg">
                    
        </div>
    </div>
    <div class="form-group row">
        <label class="form-control-label col-sm-2" for="expired_user">{{trans($module_name.'.admin.expired_user')}} <span class="text-danger"></span></label>
        <div class="col-sm-1">
            <input type="checkbox" class="" id="expired_user" name="expired_user" value="1" {{ $data && $data->expired_user==1 ? 'checked':'' }} >
        </div>
        <label class="form-control-label col-sm-2" for="non_expired_user">{{trans($module_name.'.admin.non_expired_user')}} <span class="text-danger"></span></label>
        <div class="col-sm-5">
            <input type="checkbox" class="" id="non_expired_user" name="non_expired_user" value="1" {{ $data && $data->non_expired_user==1 ? 'checked':'' }} >
        </div>
        <div class="col-sm-2 text-danger msg">
                    
        </div>
    </div>
</div>