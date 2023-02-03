@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="table-wrap">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-mode="swipe"  data-tablesaw-minimap data-tablesaw-mode-switch>
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
								@elseif($key == 'is_primary')
									<td class="text-center">{{$row->display_answer_string($key)}}</td>		
								@elseif($key != 'id')
									<td class="text-center">{{$value}}</td>
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