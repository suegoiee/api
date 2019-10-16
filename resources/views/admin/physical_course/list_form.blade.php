
<div class="form-group row">
	<table class="table">
		<thead>
			<th>名稱</th>
			<th>email</th>
			<th>人數</th>
		</thead>
		<tbody>
		@foreach($students as $student)
			<tr>
				<td>{{$student->name}}</td>
				<td>{{$student->email}}</td>
				<td>{{$student->quantity}}</td>
			</tr>
		@endforeach
		</tbody>
	</table>
</div>