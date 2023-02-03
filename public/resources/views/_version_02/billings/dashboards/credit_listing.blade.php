@extends('billings.layouts.admin')
@section('content')
<table class="table table-bordered table-hover">
	<thead>
		<tr>
			<th>#</th>
			<th>{{('House No.')}}</th>
			<th>{{('Room No.')}}</th>
			<th>{{('Account No.')}}</th>
			<th>{{('Contact No.')}}</th>
			<th class="text-center">{{('IP Address')}}</th>
			<th class="text-center">{{('Meter ID')}}</th>
			<th class="text-center">{{('Credit')}}</th>
		</tr>
	</thead>
	<tbody>
		@foreach($listing as $index => $row)
			<tr>
				<td>{{$index+1}}.</td>
				<td>{{$row->convert_house_no($row['leaf_room_id'], $house_list)}}</td>
				<td>{{$row->convert_room_no($row['leaf_room_id'], $house_list)}}</td>
				<td>{{$row['account_no']}}</td>
				<td>{{$row['contract_no']}}</td>
				<td class="text-center">{{$row['ip_address']}}</td>
				<td class="text-center">{{$row['meter_id']}}</td>
				<td class="text-center">0</td>
			</tr>
		@endforeach
	</tbody>
</table>
@stop
@section('script')
@stop