@if (count($errors) > 0)
    <ul>
		@foreach ($errors as $error)
			<li class="list-group-item list-group-item-danger">
				{{$error}}
			</li>
		@endforeach
    </ul>
@endif