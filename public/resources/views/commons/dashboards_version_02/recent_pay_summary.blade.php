@extends('commons.layouts.admin')
@section('content')

<!-- Default box -->
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
		<div class="box-tools pull-right">
	
		</div>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			
			<table id="leaf_data_table" class="table table-bordered table-hover">
				<thead>
					<tr>
						<th>#</th>
						<th>{{('Doc No.')}}</th>
						<th>{{('Ref No.')}}</th>
						<th>{{('House Room No.')}}</th>
						<th>{{('Customer Name')}}</th>
						<th>{{('Document Date')}}</th>
						<th class="text-center">{{('Total Amount')}}</th>

					</tr>
				</thead>
				<tbody>
					@foreach($listing as $index => $row)
						<tr>
							<td>{{$index+1}}.</td>
							<td>{{$row['document_no']}}</td>
							<td>{{$row['reference_no']}}</td>
							<td>{{$row['house_name']}}</td>
							<td>{{$row['customer_name']}}</td>
							<td>{{$row['document_date']}}</td>
							<td class="text-right"> {{$row->setDouble($row['total_amount'])}} </td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer text-center">
		
	</div>
	<!-- /.box-footer-->
</div>
<!-- /.box -->
@stop
@section('script')
@stop

