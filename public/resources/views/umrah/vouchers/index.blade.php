@extends('umrah.layouts.admin')
@section('content')
@include('umrah.layouts.partials._alert')
<!-- Default box -->
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('VouchersController@getNew')}}" class="btn btn-block btn-info">
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
							@else
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
								@if(str_contains($key, '_id'))
									<td class="text-center">{{$row->display_relationed($key, 'name')}}</td>
								@elseif($key != 'id')
									<td class="text-center">{{$value}}</td>
								@endif
							@endforeach
							<td class="text-center">
								<a onclick="return confirm(confirmMsg)" href="{{action('VouchersController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a> | 
								<a href="{{action('VouchersController@getView', [$row->id])}}">{{App\Language::trans('View')}}</a> | 
								<a onclick="return confirm(confirmMsg)" href="{{action('VouchersController@getDelete', [$row->id])}}">{{App\Language::trans('Del')}}</a>
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