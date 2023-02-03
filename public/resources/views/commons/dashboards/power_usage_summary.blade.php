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
						
						<th>{{App\Language::trans('House Room No.')}}</th>
						<th>{{App\Language::trans('Customer Name')}}</th>
						<th>{{App\Language::trans('Total Payable Amount')}}</th>
						<th>{{App\Language::trans('Total Paid Amount')}}</th>
						<th>{{App\Language::trans('Total Subsidy Amount')}}</th>
						<th>{{App\Language::trans('Total Outstanding Amount')}}</th>
						<th>{{App\Language::trans('Total Usage (kWh)')}}</th>
						<th>{{App\Language::trans('Current Credit')}}</th>
						<th>{{App\Language::trans('Check In Date')}}</th>
						<th>{{App\Language::trans('Check Out Date')}}</th>
						<th>{{App\Language::trans('Last Update At')}}</th>
						<th>{{App\Language::trans('Is Mobile App User')}}</th>
					</tr>
				</thead>
				<tbody>			
					@foreach($listing as $index => $row)
						<?php $mobile_app_status = $row->leaf_id_user != 0 ? "App User" : "Non-App User" ;?>
						<tr>
							<td>{{$index+1}}.</td>
							<td>{{$row['house_name']}}</td>
							<td>{{$row['customer_name']}}</td>
							<td class="text-right">{{$row->setDouble($row['total_payable_amount'])}}</td>
							<td class="text-right">{{$row->setDouble($row['total_paid_amount'])}}</td>
							<td class="text-right">{{$row->setDouble($row['total_subsidy_amount'])}}</td>
							<td class="text-right">{{$row->setDouble($row['total_outstanding_amount'])}}</td>
							<td class="text-center">{{$row['total_usage_kwh']}}</td>
							<td class="text-right">{{$row->setDouble($row['current_credit_amount'])}}</td>
							<td>{{$row['check_in_date']}}</td>
							<td>{{$row['check_out_date']}}</td>
							<td>{{$row['updated_at']}}</td>
							<td class="text-center"><small class="label bg-{{$row->leaf_id_user != 0  ? 'green' : 'red'}}">{{App\Language::trans($mobile_app_status)}}</small></td>
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

