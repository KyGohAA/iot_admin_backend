@extends('commons.layouts.admin')
@section('content')
@include('commons.layouts.partials._alert')
<!-- Default box -->
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
		<div class="box-tools pull-right">
	
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
							@elseif($col == 'name' || $col == 'tel' || $col == 'email' || $col == 'logo_photo_path')
								<th class="text-center">{{App\Language::trans(ucwords(str_replace('_', ' ', $col)))}}</th>
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
								@if($key == 'name' || $key == 'tel' || $key == 'email')
									<td class="text-center">{{$value}}</td>
								@elseif($key == 'logo_photo_path')
									<td class="text-center"><img src="{{asset($value)}}" style="height: 50px;width: 50px;"></td>
								@endif
							@endforeach
							<td class="text-center">
								<a onclick="return confirm(confirmMsg)" href="{{action('MobileSettingsController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a> | 
								<!-- <a href="{{action('HelpsController@getView', [$row->id])}}">{{App\Language::trans('View')}}</a> | 
								<a onclick="return confirm(confirmMsg)" href="{{action('HelpsController@getDelete', [$row->id])}}">{{App\Language::trans('Del')}}</a> -->
							</td>
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

@endsection
@section('script')
@endsection