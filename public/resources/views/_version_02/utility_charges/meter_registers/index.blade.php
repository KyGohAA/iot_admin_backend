@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
@include('_version_02.commons.layouts.partials._content_title')
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-lg">
            <div class="table-wrap" style="overflow-x:auto;">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead>
                        <tr>
                            @php $priority_counter = 1 ; @endphp
                            @foreach($cols as $col)
								@if($col != 'store_id')
									@if($col == 'id')
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
									@elseif(str_contains($col, 'leaf_'))
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('leaf_', ' ', str_replace('_id', '', $col))))}}</th>
									@elseif(str_contains($col, '_id'))
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_', ' ', str_replace('_id', '', $col))))}}</th>
									@else
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
									@endif

									@if($col == 'ip_address')
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans('House No.')}}</th>
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
									@elseif(str_contains($key, 'leaf_room_id'))
										<td class="text-center">{{$row->convert_room_no($value, $rooms)}}</td>
									@elseif(str_contains($key, '_id') && $key != 'meter_id')
										<td class="text-center">{{$row->display_relationed($key, 'name')}}</td>
									@elseif($key != 'id')
										<td class="text-center">{{$value}}</td>
									@endif
									@if($key == 'ip_address')
										<td class="text-center">{{$row->convert_house_no($row->leaf_room_id, $rooms)}}</td>
									@endif
								@endforeach
								@include('_version_02.commons.layouts.partials._table_action_column')
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
@endsection
