<div class="form-group row">
	<label for="ExpertAvatarContent" class="col-sm-2 form-control-label">{{trans($module_name.'.admin.avatar')}}</label>
	<div class="col-sm-8 row" id="ExpertAvatarContent">
	@if($data && $data->avatar)
		<div class="col-sm-10 text-center">
		    <img src="{{asset('storage/'.$data->avatar)}}" class="img-responsive img-thumbnail" alt="avatar">
		</div>
		<div class="col-sm-2 text-center">
			<button class="btn btn-danger remove_btn" type="button" data-id="{{$data->id}}"><span class="oi oi-trash"></span></button>
		</div>
	@else
	    <div class="col-sm-10">
			<div class="input-group">
				<input type="file" class="form-control" name="avatar" value="">
			</div>
		</div>
		<div class="col-sm-2 text-danger msg">
    	</div>
    @endif
    </div>
</div>