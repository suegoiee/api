<div class="form-group row">
	<label for="avatar_small" class="col-sm-2 form-control-label">{{trans('product.admin.avatar_small')}}</label>
	<div class="col-sm-8 row" id="avatar_small">
	@if($data && $data->avatar_small)
		<div class="col-sm-10 text-center">
		    <img src="{{asset('storage/'.$data->avatar_small->path)}}" class="img-responsive img-thumbnail" alt="avatar">
		</div>
		<div class="col-sm-2 text-center">
			<button class="btn btn-danger remove_btn" type="button" data-id="{{$data->avatar_small->id}}"><span class="oi oi-trash"></span></button>
		</div>
	@else
	    <div class="col-sm-10">
			<div class="input-group">
				<input type="file" class="form-control" name="avatars[][avatar]" value="">
				<input type="hidden" class="form-control" name="avatars[][avatar_type]" value="small">
			</div>
		</div>
		<div class="col-sm-2 text-danger msg">
    	</div>
    @endif
    </div>
</div>
<div class="form-group row" >
	<label for="avatar_detail" class="col-sm-2 form-control-label">{{trans('product.admin.avatar_detail')}}</label>
	<div class="col-sm-8" id="upload_list">
		<div class="input-group">
			<input type="file" class="form-control" name="avatars[][avatar]" value="">
			<input type="hidden" class="form-control" name="avatars[][avatar_type]" value="detail">
			<span class="input-group-btn">
				<button class="btn btn-danger remove_btn" type="button"><span class="oi oi-trash"></span></button>
			</span>
		</div>
	</div>	
	<div class="col-2 text-right">
		<button class="btn btn-success" id="new_avatar_detail_btn"><span class="oi oi-plus"></span></button>
	</div>
</div>
<div class="form-group row">
	<label class="col-sm-2 form-control-label"></label>
	<div class="col-sm-8" id="avatar_detail_list">
		@if($data)
			@foreach($data->avatar_detail as $key => $avatar_detail)
			<div class="row">
				<div class="col-10 img-box text-center">
					<img src="{{asset('storage/'.$avatar_detail->path)}}" class="img-responsive img-thumbnail" alt="avatar">
				</div>
				<div class="col-2 text-right">
					 <button class="btn btn-danger remove_btn" data-id="{{$avatar_detail->id}}"><span class="oi oi-trash"></span></button>
				</div>
			</div>
			@endforeach
		@endif
	</div>
</div>