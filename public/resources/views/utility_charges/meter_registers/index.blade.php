@extends('commons.layouts.admin')
@section('content')
@include('commons.layouts.partials._alert')
<!-- Default box -->
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('UMeterRegistersController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<table id="leaf_data_table" class="table">
				<thead>
					<tr>
						@foreach($cols as $col)
							@if($col == 'id')
								<th class="text-center">#</th>
							@elseif(str_contains($col, 'leaf_'))
								<th class="text-center">{{App\Language::trans(ucwords(str_replace('leaf_', ' ', str_replace('_id', '', $col))))}}</th>
							@elseif(str_contains($col, '_id'))
								<th class="text-center">{{App\Language::trans(ucwords(str_replace('_', ' ', str_replace('_id', '', $col))))}}</th>
							@else
								<th class="text-center">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
							@endif
							@if($col == 'ip_address')
								<th class="text-center">{{App\Language::trans('House No.')}}</th>
							@endif
						@endforeach
						<th class="text-center">{{App\Language::trans('Action')}}</th>
					</tr>
				</thead>
				<tbody>
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
							<td class="text-center">
								<a onclick="return confirm(confirmMsg)" href="{{action('UMeterRegistersController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a> | 
								<a href="{{action('UMeterRegistersController@getView', [$row->id])}}">{{App\Language::trans('View')}}</a> | 
								<a onclick="return confirm(confirmMsg)" href="{{action('UMeterRegistersController@getDelete', [$row->id])}}">{{App\Language::trans('Del')}}</a>
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<!-- /.box-body -->
	<div class="box-footer">
		{{$model->links()}}
	</div>
	<!-- /.box-footer-->
</div>
<!-- /.box -->

@endsection
@section('script')
@endsection