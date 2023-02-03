@extends('_version_02.commons.layouts.admin')
@section('content')
@include('_version_02.commons.layouts.partials._alert')
<section class="hk-sec-wrapper">
    <div class="row">
        <div class="col-md">
            <div class="table-wrap">
                <table id="leaf_data_table" class="table tablesaw table-bordered table-hover mb-0 pb-30" data-tablesaw-minimap>
                    <thead>
                        <tr>
                            @php $priority_counter = 1 ; 


                            @endphp
                            @foreach($cols as $col)
								@if($col != 'store_id')
									@if($col == 'id')
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="0">#</th>
									@elseif(str_contains($col, '_id'))
										<th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_id', '', $col)))}}</th>
									@elseif($col == 'power_mangement_start_charging_date')
										@if(App\Company::is_allow_to_access_module(App\Setting::LABEL_MODULE_POWER_MANAGEMENT))
											<!-- <th scope="col" data-tablesaw-sortable-col data-tablesaw-priority="{{$priority_counter}}">{{App\Language::trans(ucwords(str_replace('_id', '', $col)))}}</th> -->
										@endif
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
                         	<tr id="{{$row['id']}}">
								<td class="text-center">{{$index+1}}</td>
								@foreach($row->toArray() as $key => $value)
									@php
										if(!in_array($key,$cols))
										{
											continue;
										}
									@endphp
									@if($key == 'photo')
										<!-- <td class="text-center"><img class="img-responsive" width="50" height="50" src="{{$row->profile_jpg()}}"></td> -->
									@elseif($key == 'status')
										<td class="text-center">{{$row->display_status_string($key)}}</td>
									@elseif($key != 'user_id' && $key != 'id')
										<td class="text-center">{{$value}}</td>
									@endif
								@endforeach
								<td class="text-center">
									<a onclick="return confirm(confirmMsg)" class="loading-label" href="{{action('UsersController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a>
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