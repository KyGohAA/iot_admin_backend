@extends('commons.layouts.admin')
@section('content')
@include('commons.layouts.partials._alert')
<!-- Default box -->
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('ProductsController@getNew')}}" class="btn btn-block btn-info">
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
							@elseif(str_contains($col, '_id'))
								<th class="text-center">{{App\Language::trans(ucwords(str_replace('_', ' ', str_replace('_id', '', $col))))}}</th>
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
								@if($key == 'status')
									@if($value==true)
										<td class="text-center">
											<small class="label pull-center bg-green">{{App\Language::trans('Active')}}</small>
										</td>
									@else
										<td class="text-center">
											<small class="label pull-center bg-red">{{App\Language::trans('Inactive')}}</small>
										</td>
									@endif								
								@elseif($key == 'is_obsolete')
									<td class="text-center">{{$row->display_answer_string($key)}}</td>
								@elseif($key == 'cover_photo_path')
									<td class="text-center"><img class="img-responsive" width="50" height="50" src="{{asset($row->cover_photo_path)}}"></td>
								@elseif(str_contains($key, '_id'))
									<td class="text-center">{{$row->display_relationed($key, 'name')}}</td>
								@elseif($key != 'id')
									<td class="text-center">{{$value}}</td>
								@endif
							@endforeach
							<td class="text-center">
								<a onclick="return confirm(confirmMsg)" href="{{action('ProductsController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a> |  
								<a href="{{action('ProductsController@getView', [$row->id])}}">{{App\Language::trans('View')}}</a> | 
								{{-- <a onclick="return confirm(confirmMsg)" href="{{action('ProductsController@getDelete', [$row->id])}}">{{App\Language::trans('Del')}}</a> --}}
							</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
		<!-- ROW START -->
        <div class="row">
            <hr>
            <div class="col-md-12">
                <div>
                    <p class="text-muted well well-sm no-shadow" style="margin-top: 10px;">
                        <strong>{{App\Language::trans('Product Status')}} </strong>
                        <br>{{App\Language::trans("Only the product with 'Active' status will be available on operation")}},
                        {{App\Language::trans('in order to make product available.')}} 
                        <br>{{App\Language::trans('Please ensure the following fields are completed :')}}
                        <br><strong>{{App\Language::trans('Product Code')}} <strong>,
                        <strong>{{App\Language::trans('Sales Tax')}} <strong>,
                        <strong>{{App\Language::trans('Purchase Tax')}} <strong>,
                        <strong>{{App\Language::trans('UOM')}} <strong>,
                        <strong>{{App\Language::trans('Payment Term')}} <strong>
                    </p>
                </div>
            </div>
        </div>
        <!-- ROW END -->
	</div>
	<!-- /.box-body -->
	<div class="box-footer text-center">
		{{$model->links()}}
	</div>
	<!-- /.box-footer-->
</div>
<!-- /.box -->

@endsection
@section('script')
@endsection