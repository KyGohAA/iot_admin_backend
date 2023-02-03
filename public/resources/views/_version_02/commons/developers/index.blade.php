@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-sm">
            <div class="table-wrap">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 w-100 pb-30" data-tablesaw-minimap data-tablesaw-mode-switch>
                    <thead>
                        <tr>
                            @php 
                            	$priority_counter = 1 ; 
                            	$index_columns = App\PaymentTestingAllowList::index_columns;
                            @endphp
                            @foreach($cols as $col)
								@if($col != 'store_id' && in_array($col, $index_columns) == true)
									@if($col == 'id')
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">{{App\Language::trans('Logo')}}</th>
									@elseif(str_contains($col, '_id'))
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_id', '', $col)))}}</th>
									@elseif($col == 'name' || $col == 'tel' || $col == 'email')
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
							<td class="text-center"><img src="{{asset($row['logo_photo_path'])}}" style="height: 50px;width: 50px;"></td>
							@foreach($row->toArray() as $key => $value)
								@if($key == 'name' || $key == 'tel' || $key == 'email')
									<td class="text-center">{{$value}}</td>				
								@endif
							@endforeach
							<td class="text-center">
								<a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('PaymentTestingAllowListsController@getIndex', [$row->id])}}">{{App\Language::trans('Select')}}</a> | 
								<!-- <a href="{{action('HelpsController@getView', [$row->id])}}">{{App\Language::trans('View')}}</a> | 
								<a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('HelpsController@getDelete', [$row->id])}}">{{App\Language::trans('Del')}}</a> -->
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
@endsection