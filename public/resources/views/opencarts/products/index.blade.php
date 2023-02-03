@extends('commons.layouts.admin')
@section('content')
@include('commons.layouts.partials._alert')
@include('opencarts.products.partials.save_by_url')
@include('opencarts.products.partials.search_bar')

<div id="alert_msg_div" class="alert alert-success alert-dismissible hide">
		<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
		<i id="alert_msg" class="icon fa fa-check"></i>
</div>


<!-- Default box -->
@if(!$is_index)
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Listing Information')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('OCProductsController@getNew')}}" class="btn btn-block btn-info">
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
								<th class="text-center">{{App\Language::trans('Image')}}</th>
							@elseif($col == 'product_url' || $col == 'status')
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
							<td class="text-center"><p style="color:{{$row->status == true ? 'green':'red'}};">{{$index+1}}</p></td>
							<td class="text-center"><img class="img-responsive" width="50" height="50" src="{{asset($row->image)}}"></td>
							<td class="text-center"><a href="http://{{$row->product_url}}">{{App\Language::trans($row->model)}}</a></td>
							@foreach($row->toArray() as $key => $value)
								@if($key == 'date_started' || $key == 'date_started')
									<td class="text-center">{{$value}}</td>
								@elseif($key == 'image')
									<td class="text-center"><img class="img-responsive" width="50" height="50" src="{{asset($value)}}"></td>
								@elseif($key == 'cost' || $key == 'price' || $key == 'selling_price')
									<td class="text-center">
										{!! Form::number($key, null, ['id'=>$key.'_'.$row->id, 'rows'=>'10' , 'cols'=>'80','class'=>'form-control','placeholder'=>App\Language::trans(ucwords(str_replace('_', ' ', $key))." : ".$row->setDouble($value))]) !!}
									</td>
								@elseif($key != 'id' && $key != 'model' && $key != 'product_url' && $key != 'status')
									<td class="text-center">{{$value}}</td>
								@endif
							@endforeach
							<td class="text-center">
								<a onclick="return confirm(confirmMsg)" href="{{action('OCProductsController@getEdit', [$row->id])}}">{{App\Language::trans('Edit')}}</a> | <a class='btn' onclick='update_selected_product({{$row->id}});'>{{App\Language::trans('Update')}}</a>
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
		{{$model->links()}}
	</div>
	<!-- /.box-footer-->
</div>
<!-- /.box -->
@endif

@endsection
@section('script')
@endsection