@if (is_array($errors) && count($errors) > 0)
    <ul>
		@foreach ($errors as $error)
			<li class="list-group-item list-group-item-danger">
				{{$error}}
			</li>
		@endforeach
    </ul>
@endif
@if (session('errors'))
    <ul>
		<li class="list-group-item list-group-item-danger">
			{{session('errors')}}
		</li>
    </ul>
    @php 
    	session()->forget('errors');
    @endphp 
@endif