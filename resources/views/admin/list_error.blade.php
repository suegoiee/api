
@if (count($errors) > 0)
    <ul>
		@foreach ($errors as $error)
			<li class="list-group-item list-group-item-danger">
				{{$error}}
			</li>
		@endforeach
    </ul>
@endif
@if (Session::has('infos'))
    <ul>
		<li class="list-group-item list-group-item-info">
			{{Session::get('infos')}}
		</li>
    </ul>
@endif