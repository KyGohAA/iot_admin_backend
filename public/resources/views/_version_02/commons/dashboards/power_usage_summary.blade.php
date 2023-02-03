@extends('_version_02.commons.layouts.admin')
@section('content')
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="table-wrap" style="overflow-x:auto;">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap data-tablesaw-mode-switch>
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
							<td class="text-center"><small class="badge badge-{{$row->leaf_id_user != 0  ? 'success' : 'danger'}} mt-15 mr-10">{{App\Language::trans($mobile_app_status)}}</small></td>
						</tr>
					@endforeach
				</tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@stop
@section('script')
@stop