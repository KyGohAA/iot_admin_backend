@extends('_version_02.commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('_version_02.commons.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			{{-- <a href="{{action('ProductsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a> --}}
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
					{!! Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->code}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->name}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('barcode') ? ' has-error' : '' }}">
					{!! Form::label('barcode', App\Language::trans('Barcode'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->barcode}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('lead_time') ? ' has-error' : '' }}">
					{!! Form::label('lead_time', App\Language::trans('Lead Time'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->lead_time}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('product_category_id') ? ' has-error' : '' }}">
					{!! Form::label('product_category_id', App\Language::trans('Category'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('product_category', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('cost_method') ? ' has-error' : '' }}">
					{!! Form::label('cost_method', App\Language::trans('Cost Method'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{ucfirst($model->cost_method)}}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Price & Quantity Form')}}</h3>
		<div class="box-tools pull-right">
			{{-- <a href="{{action('ProductsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a> --}}
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('uom_id') ? ' has-error' : '' }}">
					{!! Form::label('uom_id', App\Language::trans('Unit Of Measurement'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_relationed('uom', 'name')}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('selling_price') ? ' has-error' : '' }}">
					{!! Form::label('selling_price', App\Language::trans('Selling Price'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->selling_price}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('purchase_price') ? ' has-error' : '' }}">
					{!! Form::label('purchase_price', App\Language::trans('Purchase Price'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->purchase_price}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('standard_cost') ? ' has-error' : '' }}">
					{!! Form::label('standard_cost', App\Language::trans('Standard Cost'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->standard_cost}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('min_quantity') ? ' has-error' : '' }}">
					{!! Form::label('min_quantity', App\Language::trans('Min. Quantity'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->min_quantity}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('max_quantity') ? ' has-error' : '' }}">
					{!! Form::label('max_quantity', App\Language::trans('Max. Quantity'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->max_quantity}}</p>
					</div>
				</div>
				<div class="form-group{{ $errors->has('reorder_quantity') ? ' has-error' : '' }}">
					{!! Form::label('reorder_quantity', App\Language::trans('Reorder Quantity'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->reorder_quantity}}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('_version_02.commons.products.partials.__prices_view')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Other Detail Form')}}</h3>
		<div class="box-tools pull-right">
			{{-- <a href="{{action('ProductsController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a> --}}
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_status_string('status')}}</p>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_obsolete') ? ' has-error' : '' }}">
					{!! Form::label('is_obsolete', App\Language::trans('Is Obsolete'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<p class="form-control-static">{{$model->display_answer_string('is_obsolete')}}</p>
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="form-group{{ $errors->has('remark') ? ' has-error' : '' }}">
					{!! Form::label('remark', App\Language::trans('Remark'), ['class'=>'control-label col-md-2']) !!}
					<div class="col-md-10">
						<p class="form-control-static">{!!nl2br($model->remark)!!}</p>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<a href="{{action('ProductsController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@endsection