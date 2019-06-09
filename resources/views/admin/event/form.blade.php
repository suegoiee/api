<div class="form-group row">
    <label class="form-control-label col-sm-2" for="code">{{trans($module_name.'.admin.name')}} <span class="text-danger">*</span></label>
    <div class="col-sm-9">
        <input type="text" class="form-control" id="name" name="name" placeholder="{{trans($module_name.'.admin.name')}}" value="{{@$data->name}}">
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="started_at">{{trans($module_name.'.admin.started_at')}} <span class="text-danger"></span></label>
    <div class="col-sm-9 input-group input-daterange">
        <input type="text" class="form-control" id="started_at" name="started_at" placeholder="{{trans($module_name.'.admin.started_at')}}" value="{{$data ? $data->started_at:''}}">
        <div class="input-group-addon"> - </div>
        <input type="text" class="form-control" id="ended_at" name="ended_at" placeholder="{{trans($module_name.'.admin.ended_at')}}" value="{{$data ? $data->ended_at:''}}">
    </div>
    <div class="col-sm-2 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="type">{{trans($module_name.'.admin.type')}} <span class="text-danger"></span></label>
    <div class="col-sm-9">
        <select class="form-control" id="type" name="type" data-placeholder="{{trans('form.do_select')}}" >
	        <option value="1" {{$data && $data->type==1 ? 'selected':''}} >{{trans($module_name.'.admin.type_1')}}</option>
	        <!--option value="2" {{$data && $data->type==2 ? 'selected':''}} >{{trans($module_name.'.admin.type_2')}}</option-->
	    </select>
	    </select>
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
</div>
<div class="form-group row">
    <label class="form-control-label col-sm-2" for="status">{{trans($module_name.'.admin.status')}} <span class="text-danger"></span></label>
    <div class="col-sm-9">
        <select class="form-control" id="status" name="status" data-placeholder="{{trans('form.do_select')}}" >
	        <option value="0" {{$data && $data->status==0 ? 'selected':''}} >{{trans($module_name.'.admin.status_0')}}</option>
	        <option value="1" {{$data && $data->status==1 ? 'selected':''}} >{{trans($module_name.'.admin.status_1')}}</option>
	        <option value="2" {{$data && $data->status==2 ? 'selected':''}} >{{trans($module_name.'.admin.status_2')}}</option>
	    </select>
    </div>
    <div class="col-sm-1 text-danger msg">
                
    </div>
</div>

<div id="products_A">
	<div class="form-group row">
	    <div class="col-sm-1"></div>
	    <label class="text col-sm-9" for="products">{{trans($module_name.'.admin.products_A')}}</label>
	</div>
	<div class="form-group row">
	    <div class="col-sm-1"></div>
	    <div class="col-sm-9">
	        <select class="form-control selectpicker" id="products_A_input" multiple="multiple" data-placeholder="{{trans('form.do_select')}}" data-live-search="true" data-size="5" data-none-selected-text="{{trans('form.do_select')}}" data-width="100%" data-actions-box="ture">
	            @foreach($products as $product)
	                @if(!$data || $condition_products->where('id',$product->id)->count()==0)
	                    <option value="{{$product->id}}" data-name="{{$product->name}}"  data-status="{{trans('product.admin.status_'.$product->status)}}" data-type="{{trans('product.admin.type_'.$product->type)}}">{{$product->name}} [{{trans('product.admin.type_'.$product->type)}}] ({{trans('product.admin.status_'.$product->status)}})</option>
	                @endif
	            @endforeach
	        </select>
	    </div>
	    <div class="col-sm-2">
	        <button class="btn btn-info" id="product_A_select_btn">加入
	        </button>
	    </div>
	</div>
	<div class="form-group row">
	    <div class="col-sm-1"></div>
	    <div class="col-sm-10">
	        <table class="table table-bordered">
	            <thead>
	                <tr>
	                    <th>{{trans($module_name.'.admin.product_name')}}</th>
	                    <th>{{trans($module_name.'.admin.condition_quantity')}}</th>
	                    <th>{{trans('table.action_label')}}</th>
	                </tr> 
	            </thead>
	            <tbody id="product_A_table">
	            @if($data)
	               @foreach( $condition_products as $key => $product)
	                    <tr>
	                        <td>{{$product->name}}
	                        	<input type="hidden" name="condition_products[{{$key}}][id]" value="{{$product->id}}">
	                        	<input type="hidden" name="condition_products[{{$key}}][condition]" value="{{$product->pivot->condition}}">
	                        </td>
	                        <td><input type="text" class="form-control" name="condition_products[{{$key}}][quantity]" value="{{$product->pivot->quantity}}"  placeholder="{{trans('event.admin.quantity_0')}}"></td>
	                       	<td class="text-center"><span class="oi oi-trash remove" data-name="{{$product->name}}" data-type="{{trans('product.admin.type_'.$product->type)}}" data-status="{{trans('product.admin.status_'.$product->status)}}" data-id="{{$product->id}}"></span></td>
	                    </tr> 
	               @endforeach
	               @endif
	            </tbody>
	        </table>
	    </div>
	</div>
</div>
<div id="products_B">
	<div class="form-group row">
	    <div class="col-sm-1"></div>
	    <label class="text col-sm-9" for="products">{{trans($module_name.'.admin.products_B')}}</label>
	</div>
	<div class="form-group row">
	    <div class="col-sm-1"></div>
	    <div class="col-sm-9">
	        <select class="form-control selectpicker" id="products_B_input" multiple="multiple" data-placeholder="{{trans('form.do_select')}}" data-live-search="true" data-size="5" data-none-selected-text="{{trans('form.do_select')}}" data-width="100%" data-actions-box="ture">
	            @foreach($products as $product)
	                @if(!$data || $bonus_products->where('id',$product->id)->count()==0)
	                    <option value="{{$product->id}}" data-name="{{$product->name}}"  data-status="{{trans('product.admin.status_'.$product->status)}}" data-type="{{trans('product.admin.type_'.$product->type)}}">{{$product->name}} [{{trans('product.admin.type_'.$product->type)}}] ({{trans('product.admin.status_'.$product->status)}})</option>
	                @endif
	            @endforeach
	        </select>
	    </div>
	    <div class="col-sm-2">
	        <button class="btn btn-info" id="product_B_select_btn">加入
	        </button>
	    </div>
	</div>
	<div class="form-group row">
	    <div class="col-sm-1"></div>
	    <div class="col-sm-10">
	        <table class="table table-bordered">
	            <thead>
	                <tr>
	                    <th>{{trans($module_name.'.admin.product_name')}}</th>
	                    <th>{{trans($module_name.'.admin.product_quantity')}}</th>
	                    <th>{{trans('table.action_label')}}</th>
	                </tr> 
	            </thead>
	            <tbody id="product_B_table">
	            @if($data)
	               @foreach( $bonus_products as $key => $product)
	                    <tr>
	                        <td>{{$product->name}}
	                        	<input type="hidden" name="products[{{$key}}][id]" value="{{$product->id}}">
	                        	<input type="hidden" name="products[{{$key}}][condition]" value="{{$product->pivot->condition}}">
	                        </td>
	                        <td><input type="text" class="form-control" name="products[{{$key}}][quantity]" value="{{$product->pivot->quantity}}" placeholder="{{trans('event.admin.quantity_0')}}"></td>
	                        <td class="text-center"><span class="oi oi-trash remove" data-name="{{$product->name}}" data-type="{{trans('product.admin.type_'.$product->type)}}" data-status="{{trans('product.admin.status_'.$product->status)}}" data-id="{{$product->id}}"></span></td>
	                    </tr> 
	               @endforeach
	               @endif
	            </tbody>
	        </table>
	    </div>
	</div>
</div>