<table class="table table-bordered table-striped table-hover">
	<thead>
		<tr>
			<th>IP</th>
			<th>Page</th>
			<th>User</th>
			<th>Date</th>
			<th>Useragent</th>
		</tr>
	</thead>

	<tbody>
		@foreach(Visitor::all() as $visitor)
			<tr>
				<td>{{ $visitor->ip }}</td>
				<td>{{ $visitor->page }}</td>
				@if(!is_null($visitor->user))
-					<td>{{ $visitor->user }}</td>
-				@else
-					<td>Guest</td>
-				@endif
				<td>{{ $visitor->updated_at }}</td>
				<td>{{ $visitor->useragent }}</td>
			</tr>
		@endforeach
	</tbody>

</table>
