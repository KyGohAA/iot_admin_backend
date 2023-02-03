@extends('commons.layouts.admin')
@section('content')
{!! Form::model($model, ['class'=>'form-horizontal']) !!}
@include('commons.layouts.partials._alert')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('Detail Form')}}</h3>
		<div class="box-tools pull-right">
			<a href="{{action('UtilityChargesController@getNew')}}" class="btn btn-block btn-info">
				<i class="fa fa-file"></i> {{App\Language::trans('New File')}}
			</a>
		</div>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('code') ? ' has-error' : '' }}">
					{!! Form::label('code', App\Language::trans('Code'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('code', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('code', '<label for="code" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
					{!! Form::label('name', App\Language::trans('Name'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::text('name', null, ['class'=>'form-control','required']) !!}
                        {!!$errors->first('name', '<label for="name" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('is_hourly') ? ' has-error' : '' }}">
					{!! Form::label('is_hourly', App\Language::trans('Hourly Rate'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('is_hourly', 1, true, ['id'=>'is_hourly_yes']) !!} {{App\Language::trans('Yes')}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('is_hourly', 0, false, ['id'=>'is_hourly_no']) !!} {{App\Language::trans('No')}}
						</label>
                        {!!$errors->first('is_hourly', '<label for="is_hourly" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('hourly_rate') ? ' has-error' : '' }}">
					{!! Form::label('hourly_rate', App\Language::trans('Hourly / Rate'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						{!! Form::number('hourly_rate', null, ['class'=>'form-control','min'=>'0','step'=>'0.01']) !!}
                        {!!$errors->first('hourly_rate', '<label for="hourly_rate" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@include('utility_charges.charges.__partials.__price_range')
<div class="box">
	<div class="box-header with-border">
		<h3 class="box-title">{{App\Language::trans('More Detail Form')}}</h3>
	</div>
	<div class="box-body">
		<div class="row">
			<div class="col-md-6">
				<div class="form-group{{ $errors->has('status') ? ' has-error' : '' }}">
					{!! Form::label('status', App\Language::trans('Status'), ['class'=>'control-label col-md-4']) !!}
					<div class="col-md-8">
						<label class="radio-inline">
							{!! Form::radio('status', 1, true) !!} {{App\Language::trans('Enabled')}}
						</label>
						<label class="radio-inline">
							{!! Form::radio('status', 0, false) !!} {{App\Language::trans('Disabled')}}
						</label>
                        {!!$errors->first('status', '<label for="status" class="help-block error">:message</label>')!!}
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="box-footer">
		<div class="row">
			<div class="col-md-offset-2 col-md-10">
				<button type="submit" class="btn btn-primary"><i class="fa fa-floppy-o fa-fw"></i>{{App\Language::trans('Save')}}</button>
				<a href="{{action('UtilityChargesController@getIndex')}}" class="btn btn-danger"><i class="fa fa-ban fa-fw"></i>{{App\Language::trans('Close')}}</a>
			</div>
		</div>
	</div>
</div>
{!! Form::close() !!}
@endsection
@section('script')
@if($model->is_hourly || old('is_hourly'))
	$("#prices").hide();
@else
	$("input[name=hourly_rate]").closest(".form-group").hide();
@endif
$("#is_hourly_yes").on("click", function(){
	$("input[name=hourly_rate]").closest(".form-group").show();
	$("#prices").hide();
});
$("#is_hourly_no").on("click", function(){
	$("#prices").show();
	$("input[name=hourly_rate]").closest(".form-group").hide();
});
@endsection