@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
<!-- Default box -->
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="table-wrap">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead>
                        <tr>
                            @php $priority_counter = 1 ; @endphp
                            @foreach($cols as $col)
								@if($col != 'store_id')
									@if($col == 'id')
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
									@elseif(str_contains($col, '_id'))
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_id', '', $col)))}}</th>
									@else
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
									@endif
								@endif
								@php $priority_counter ++ ; @endphp
							@endforeach
							<th class="text-center">{{App\Language::trans('Action')}}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $priority_counter = 1 ; @endphp
                        @foreach($model as $index => $row)
						<tr>
							<td class="text-center">{{$index+1}}</td>
							@foreach($row->toArray() as $key => $value)
								@if($key == 'status')
									<td class="text-center">{{$row->display_status_string($key)}}</td>
								@elseif($key == 'total_amount')
									<td class="text-center">{{$row->setDouble($value)}}</td>
								@elseif($key != 'id')
									<td class="text-center">{{$value}}</td>
								@endif
							@endforeach
							<td class="text-center">
								<!-- <a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('ARInvoicesController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a> | 
								<a href="{{action('ARInvoicesController@getView', [$row->id])}}">{{App\Language::trans('View')}}</a> |  -->
								<a target="_blank" href="{{action('ARInvoicesController@getPrint', [$row->id])}}">{{App\Language::trans('Print')}}</a> | 
								<!-- <a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('ARInvoicesController@getDelete', [$row->id])}}">{{App\Language::trans('Del')}}</a> -->
							</td>
						</tr>
						@endforeach

                    </tbody>
                </table>
            </div>
        </div>
    </div>
</section>
@endsection
@section('script')
	init_select2($("select[name=customer_id]"));
	init_select2($("select[name=ar_invoice_id]"));
	init_select2($("select[name=sort_by]"));
@endsection