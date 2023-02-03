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
    </div>
</section>
@stop
@section('script')
@stop

