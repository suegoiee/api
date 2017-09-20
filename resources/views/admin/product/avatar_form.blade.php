<div class="form-group row" >
	<label for="avatar_small" class="col-sm-2 form-control-label">{{trans('product.admin.avatar_small')}}</label>
	<div class="col-sm-8">
		<div class="input-group">
			<input type="file" class="form-control" name="avatar_small" value="">
		</div>
	</div>
	<div class="col-sm-2 text-danger msg">
    </div>
</div>
<div class="form-group row" >
	<label for="avatar_detail" class="col-sm-2 form-control-label">{{trans('product.admin.avatar_detail')}}</label>
	<div class="col-sm-8" id="upload_list">
		<div class="input-group">
			<input type="file" class="form-control" name="avatar_detail[]" value="">
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
	<div class="col-sm-10" id="avatar_detail_list">
		@foreach($data->avatar_detail as $key => $avatar_detail)
		<div class="row">
			<div class="col-10 img-box text-center">
				<input type="hidden" name="avatar_detail_ids[]" value="{{$avatar_detail->id}}">
				<img src="{{asset('storage/'.$avatar_detail->path)}}" class="img-responsive img-thumbnail" alt="image">
			</div>
			<div class="col-2 text-right">
				 <button class="btn btn-danger remove_btn" data-id=""><span class="oi oi-trash"></span></button>
			</div>
		</div>
		@endforeach
	</div>
</div>