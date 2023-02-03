@extends('umrah.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.umrah.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Select Users')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('VoucherAssignsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('from_user', App\Language::trans('From User'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{!!$model->from_user!!}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group">
					{!! Form::label('to_user', App\Language::trans('To User'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{!!$model->to_user!!}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Select Packages')}}</h3>
	</div>
	<div class="box-body">
		<div class="table-responsive">
			<table id="leaf_data_table" class="table">
				<thead>
					<tr>
						<th>#</th>
						<th class="col-md-11">{{App\Language::trans('Package Name')}}</th>
						<th class="col-md-1 text-center">{{App\Language::trans('Quantity')}}</th>
					</tr>
				</thead>
				<tbody>
					@foreach($model->items as $index => $row)
						<tr>
							<td>{{$index+1}}</td>
							<td>{{$row->display_relationed('voucher','name')}}</td>
							<td class="text-center">{{$row->quantity}}</td>
						</tr>
					@endforeach
				</tbody>
			</table>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-12 text-right">
				<a href="{{action('VoucherAssignsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection